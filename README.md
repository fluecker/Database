# PHP-Secure-Database-Class

With this class you can easily and quickly build secure database queries and send them to your MySql-Database.
If required, the queries are logged and / or output directly on the console.

### Support me

This software is developed during my free time and I will be glad if somebody will support me.

Everyone's time should be valuable, so please consider donating.

[Donate with paypal](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=luecker.fabian%40gmail.com&currency_code=EUR&source=url)

### Installing

Installing with the composer

```
"require": {
    "fluecker/database": "dev-master"
}
```

after this 

```
composer update
```

or run

```
composer require fluecker/database
```

### Configure

Minimum configuration
```php
<?php
Database::getInstance('host', 'username', 'password', 'database');

$database = new Database('host', 'username', 'password', 'database');

$mysql = new mysqli('host', 'username', 'password', 'database');
$database = new Database($mysql);

Database::getInstance(
    [ //Main Database Connection
        'host' => '',
        'user' => '',
        'pass' => '',
        'prefix' => '',
        'database' => '',
        'port' => '3306',
        'charset' => 'utf8',
        'timezone' => 'Europe/Berlin',
    ],
    [ //Log Database Connection
            'host' => '',
            'user' => '',
            'pass' => '',
            'prefix' => '',
            'database' => '',
            'port' => '3306',
            'charset' => 'utf8',
            'timezone' => 'Europe/Berlin',
    ],
);
```

To log or echo the queries you need to configure more (full configuration)
```php
<?php
Database::getInstance([
        'config' => [
            'debug' => false, //true = do not send the Query to server
            'timer' => true, //true = save the sql execution time
            'log' => [
                'enabled' => true, // true = enabled the log functions
                'destination' => 'all', //file = only in log file, database = only in database, all = file and database
                'echo' => true, // prints the Query
                'file' => [
                    'log_path' => dirname(__DIR__) . '/Log', // full path to your logfile
                    'log_file' => 'Query.log', // Path for file log
                ],
                'database' => [ //Database config to store the logs into a table
                    'connection_data' => [
                        'main_host' => true, //true use the main connection_data, false use the following connection_data
                        'host' => '',
                        'user' => '',
                        'pass' => '',
                        'prefix' => '',
                        'database' => '',
                        'port' => '3306',
                        'charset' => 'utf8',
                        'timezone' => 'Europe/Berlin',
                    ],
                    'table_data' => [ //log table
                        'name' => '', //log table name
                        'columns' => [], //log table columns
                        'values' => [] //log table values
                    ],
                ],
            ]
        ],
        'connection_data' => [
            'host' => '',
            'user' => '',
            'pass' => '',
            'prefix' => '',
            'database' => '',
            'port' => '3306',
            'charset' => 'utf8',
            'timezone' => 'Europe/Berlin',
        ]
    ]
);
```

### Usage

```php
<?php
use Database\Database;

require_once 'vendor/autoload.php';
```

First you must configure the class. As explained above.
The class comes with Singleton functionality

You can use it as variable like this: 

```php
<?php
$database = Database::getInstance();
$database->......
```

or as Static class like this:

```php
<?php
Database::getInstance()->.....
```

After this you can use the class like the following methods

Simple select
```php
<?php
$database->select()->addFields(['*'])->addFrom('table')->addWhere(
    [
        ['state', 1]
    ]
);

$database->execute();
```

simple update
```php
<?php
$database->update()->addTable('table')->addFields(
    [
        ['name', 'test2']
    ]
)->addWhere(
    [
        ['name', 'test']
    ]
);


$database->execute();
```

If you want to send your query directly to the server you can do it like this. 
!!!Attention this way is not safe!!!
```php
<?php
$database->execute($query);
```

If you only want to debug the queries
```php
<?php
Database::getInstance()->setDebug(true);
```
The queries get build but do not send to the server.
The query was printed on the console.

For more examples, look into the "example" folder.


## Authors

* **Fabian Lücker** - [f-Luecker](https://www.f-luecker.de)

## License

This project is licensed under the MIT License - for more information see look at the [LICENSE.txt](LICENSE.txt)
