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
        // Override SQLite grammar to support older SQLite versions (< 3.26) on shared hosting
        \Illuminate\Database\Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
            $conn = new \Illuminate\Database\SQLiteConnection($connection, $database, $prefix, $config);
            
            $grammar = new class($conn) extends \Illuminate\Database\Schema\Grammars\SQLiteGrammar {
                public function __construct(\Illuminate\Database\Connection $connection)
                {
                    parent::__construct($connection);
                }

                public function compileColumns($schema, $table)
                {
                    return sprintf(
                        'select name, type, not "notnull" as "nullable", dflt_value as "default", pk as "primary", 0 as "extra" '
                        .'from pragma_table_info(%s) order by cid asc',
                        $this->quoteString($table)
                    );
                }

                public function compileIndexes($schema, $table)
                {
                    return sprintf(
                        'select \'primary\' as name, group_concat(col) as columns, 1 as "unique", 1 as "primary" '
                        .'from (select name as col from pragma_table_info(%s) where pk > 0 order by pk, cid) group by name '
                        .'union select name, group_concat(col) as columns, "unique", origin = \'pk\' as "primary" '
                        .'from (select il.*, ii.name as col from pragma_index_list(%s, %s) il, pragma_index_info(il.name, %s) ii order by il.seq, ii.seqno) '
                        .'group by name, "unique", "primary"',
                        $table = $this->quoteString($table),
                        $table,
                        $schema = $this->quoteString($schema ?? 'main'),
                        $schema
                    );
                }
            };
            
            $conn->setSchemaGrammar($grammar);
            return $conn;
        });
    }
}
