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

                public function compileTableInfo($table)
                {
                    return sprintf(
                        'select name, type, not "notnull" as "nullable", dflt_value as "default", pk as "primary", 0 as "extra" from pragma_table_info(%s) order by cid asc',
                        $this->wrapValue($table)
                    );
                }
            };
            
            $conn->setSchemaGrammar($grammar);
            return $conn;
        });
    }
}
