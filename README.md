# Delete plugin for CakePHP

[![Build Status](https://scrutinizer-ci.com/g/slicesofcake/delete/badges/build.png?b=master)](https://scrutinizer-ci.com/g/slicesofcake/delete/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/slicesofcake/delete/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/slicesofcake/delete/?branch=master)
[![LICENSE](https://img.shields.io/github/license/slicesofcake/delete.svg)](https://github.com/slicesofcake/delete/blob/master/LICENSE)
[![Releases](https://img.shields.io/github/release/slicesofcake/delete.svg)](https://github.com/slicesofcake/delete/releases)



This Delete plugin enable soft deletable.  
Entities aren't removed from your database. Instead, a deleted timestamp is set on the record.

## Requirements

It is developed for CakePHP 3.x.

## Installation

You can install plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:
```
composer require slicesofcake/delete
```

Load the Behavior
---------------------

Load the Behavior in your src/Model/Table/YourTable.php (or if you have AppTable.php). The default field named in database table should be `deleted` (like `created` or `modified`) and compatible type with `Time::now()` (eg. `DATE` or `DATETIME`).
```
public function initialize(array $config)
{
    parent::initialize($config);

    $this->addBehavior('Delete.Deleted');
}
```

You can configuration to customize the Delete plugin:
```
$this->addBehavior('Delete.Deleted', [
    'field' => 'deleted_at', // Change column field name
]);
```

## Add column by Migrations plugin

Load Migrations plugin (https://github.com/cakephp/migrations).
Copy file from /vendor/kicaj/delete/config/20200101122906_AddDeletedToProducts.example.php to your app main config directory, rename to 20200101122906_AddDeletedTo[YourTable].php, nextly edit this file and rename class and update `change` method.

Run migrations by below command:

```
cake migrations migrate
```
