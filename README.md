# Simple Calendar

[![Latest Stable Version](https://poser.pugx.org/donatj/simplecalendar/v/stable.png)](https://packagist.org/packages/donatj/simplecalendar)

A very simple, easy to use PHP calendar rendering class.

## Requirements

  - PHP 5.3.0+

## Installing

SimpleCalendar is available through Packagist via Composer.

```json
"require": {
	"donatj/simplecalendar": "0.*"
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
