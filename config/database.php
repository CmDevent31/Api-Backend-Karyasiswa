<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('mysql://qcrfks36ewkywywa:aejhzi9lwrfs5njd@ebh2y8tqym512wqs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/yy4a0gicuygj9a8a'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'ebh2y8tqym512wqs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'yy4a0gicuygj9a8a'),
            'username' => env('DB_USERNAME', 'qcrfks36ewkywywa'),
            'password' => env('DB_PASSWORD', 'aejhzi9lwrfs5njd'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL','postgres://kqlwqdkwkwguuf:9656c5748dc727cdf4ad27a4a75e7c843b6243db1a41b13ebf12ba753fe09a90@ec2-34-236-103-63.compute-1.amazonaws.com:5432/d5jmeq35k05rhc'),
            'host' => env('DB_HOST', 'http://ec2-34-236-103-63.compute-1.amazonaws.com'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'd5jmeq35k05rhc'),
            'username' => env('DB_USERNAME', 'kqlwqdkwkwguuf'),
            'password' => env('DB_PASSWORD', '9656c5748dc727cdf4ad27a4a75e7c843b6243db1a41b13ebf12ba753fe09a90'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

        'rethinkdb' => [
            'driver' => 'rethinkdb',
            'host' => env('RETHINKDB_HOST', '3bc6a989-062c-4841-8ff4-0d9fd7b1793e.db.rdb.rethinkdb.cloud'),
            'port' => env('RETHINKDB_PORT', 28015),
            'database' => env('RETHINKDB_DATABASE', 'test'),
            'username' => env('DB_USERNAME', '3bc6a989-062c-4841-8ff4-0d9fd7b1793e'),
            'password' => env('DB_PASSWORD', 'a9b83ce154c0023f418edb18797266fccc5f76db'),
        ],
     

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];