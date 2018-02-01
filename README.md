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

Load the Behavior
---------------------

Load the Behavior in your src/Model/Table/YourTable.php (or if You have AppTable.php). The default field named in database is `deleted` (like `created` or `modified`) and should be compatible type with `Time::now()` (eg. `DATE` or `DATETIME`).
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
