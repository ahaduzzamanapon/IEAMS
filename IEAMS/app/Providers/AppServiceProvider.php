<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Override SQLite grammar to support older SQLite versions (< 3.16) on shared hosting
        \Illuminate\Database\Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
            return new CustomSQLiteConnection($connection, $database, $prefix, $config);
        });
    }
}

// Custom Schema Builder for older SQLite versions on shared hosting
class CustomSQLiteBuilder extends \Illuminate\Database\Schema\SQLiteBuilder
{
    public function getColumns($table)
    {
        [$schema, $table] = $this->parseSchemaAndTable($table);
        $table = $this->connection->getTablePrefix().$table;

        // Fetch column info directly using PRAGMA (works on ALL SQLite versions!)
        $results = $this->connection->selectFromWriteConnection("PRAGMA table_info(\"{$table}\")");

        $columns = [];
        foreach ($results as $row) {
            $row = (array) $row;
            $typeName = strtolower(explode('(', $row['type'])[0]);
            $columns[] = [
                'name' => $row['name'],
                'type' => strtolower($row['type']),
                'type_name' => $typeName,
                'collation' => null,
                'nullable' => !((bool) $row['notnull']),
                'default' => $row['dflt_value'],
                'auto_increment' => false, // fallback
                'comment' => null,
                'generation' => null
            ];
        }

        return $this->connection->getPostProcessor()->processColumns(
            $columns,
            $this->connection->scalar($this->grammar->compileSqlCreateStatement($schema, $table))
        );
    }

    public function getIndexes($table)
    {
        [$schema, $table] = $this->parseSchemaAndTable($table);
        $table = $this->connection->getTablePrefix().$table;

        $indexes = [];

        // Check for primary key by checking columns pk status
        $tableInfo = $this->connection->selectFromWriteConnection("PRAGMA table_info(\"{$table}\")");
        $pkColumns = [];
        foreach ($tableInfo as $col) {
            $col = (array) $col;
            if ($col['pk'] > 0) {
                $pkColumns[$col['pk']] = $col['name'];
            }
        }

        if (!empty($pkColumns)) {
            ksort($pkColumns);
            $indexes[] = [
                'name' => 'primary',
                'columns' => array_values($pkColumns),
                'type' => 'primary',
                'unique' => true,
                'primary' => true,
            ];
        }

        // Fetch index list
        $indexList = $this->connection->selectFromWriteConnection("PRAGMA index_list(\"{$table}\")");
        foreach ($indexList as $idx) {
            $idx = (array) $idx;
            $idxName = $idx['name'];

            // Get index columns info
            $idxInfo = $this->connection->selectFromWriteConnection("PRAGMA index_info(\"{$idxName}\")");
            $cols = [];
            foreach ($idxInfo as $idxCol) {
                $idxCol = (array) $idxCol;
                $cols[] = $idxCol['name'];
            }

            $indexes[] = [
                'name' => $idxName,
                'columns' => $cols,
                'type' => $idx['unique'] ? 'unique' : 'index',
                'unique' => (bool)$idx['unique'],
                'primary' => false,
            ];
        }

        return $this->connection->getPostProcessor()->processIndexes($indexes);
    }
}

class CustomSQLiteConnection extends \Illuminate\Database\SQLiteConnection
{
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new CustomSQLiteBuilder($this);
    }
}
