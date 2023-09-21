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

    'default' => env('DB_CONNECTION', 'firebase'),

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
            'url' => env('q0h7yf5pynynaq54.cbetxkdyhwsb.us-east-1.rds.amazonaws.com'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'q0h7yf5pynynaq54.cbetxkdyhwsb.us-east-1.rds.amazonaws.com'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'd7dqr3hs5hurj7da'),
            'username' => env('DB_USERNAME', 'w2zzvl2y01otqwyd'),
            'password' => env('DB_PASSWORD', 'ylyk1odocit3il7g'),
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
            'url' => env(':810ff5d79c83a5de7b5e3289eaaec62e9e39bd892885e15ac80cd5e808ee923c@ec2-100-24-250-155.compute-1.amazonaws.com:5432/depephis61i32j'),
            'host' => env('DB_HOST', 'ec2-100-24-250-155.compute-1.amazonaws.com'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'depephis61i32j'),
            'username' => env('DB_USERNAME', 'kvbnvlgomjaijg'),
            'password' => env('DB_PASSWORD', '810ff5d79c83a5de7b5e3289eaaec62e9e39bd892885e15ac80cd5e808ee923c'),
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

        'firebase' => [
            'driver' => 'custom',
            'url' => env('FIREBASE_DATABASE_URL'),
            'database' => 'default',
            'name' => 'firebase',
            'options' => [
                'api_key' => env('FIREBASE_API_KEY'),
                'auth_domain' => env('FIREBASE_AUTH_DOMAIN'),
                'project_id' => env('FIREBASE_PROJECT_ID'),
                'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
                'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
                'app_id' => env('FIREBASE_APP_ID'),
                'measurement_id' => env('FIREBASE_MEASUREMENT_ID'),
            ],
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
