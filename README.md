# Simple Calendar

## Requirements

  - PHP 5.3.0+

## Installing

SimpleCalendar is available through Packagist.

```
  "require": {
  	"donatj/simplecalendar": "*"
	}
```

## Sample Usage

```php
<?php
require('SimpleCalendar.php');  

$Calendar = new donatj\SimpleCalendar('June 2010');  
$Calendar->show();
```

or

```php
<?php
require('SimpleCalendar.php');  

$Calendar = new donatj\SimpleCalendar();  
$Calendar->setDate('June 5 2010');  
$Calendar->show();
```
