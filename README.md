# Delete plugin for CakePHP

This Delete plugin enable soft deletable.  
Entities aren't removed from your database. Instead, a deleted timestamp is set on the record.

## Requirements

It is developed for CakePHP 3.x.

## Installation

You can install plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require kicaj/delete
```

Load the Plugin
-----------

Ensure the Delete plugin is loaded in your config/bootstrap.php file

```
Plugin::load('Delete');
```

Load the Behavior
---------------------

Load the Behavior in your src/Model/Table/YourTable.php (or if You have AppTable.php). The default field named is `deleted` and compatible type with `Time::now()` (eg. `DATE` or `DATETIME`).

```
public function initialize(array $config)
{
    parent::initialize($config);

    $this->addBehavior('Delete.Deleted', [
        'field' => 'deleted', // It's default name
    ]);
}
```
You can configuration to customize the Deleted plugin:
```
    $this->addBehavior('Delete.Deleted', [
        'field' => 'deleted_at', // Change column field name
    ]);
```