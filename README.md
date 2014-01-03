# Simple Calendar

[![Latest Stable Version](https://poser.pugx.org/trialog/simplecalendar/v/stable.png)](https://packagist.org/packages/trialog/simplecalendar)

A very simple & easy to use PHP calendar rendering class.

Forked from ![donatj/SimpleCalendar](https://github.com/donatj/SimpleCalendar).

## Requirements

  - PHP 5.3.0+
  - Easier to use with Composer
  
## Installing

You can download and install this package manually or use Composer to install it.

SimpleCalendar is available through Packagist via Composer, you can add the following to your composer.json file
```json
"require": {
	"trialog/simplecalendar": "*"
}
```

Then, launch the installation via Composer
```bash
php composer.phar install
```

To get Composer
```bash
wget http://getcomposer.org/composer.phar
```

## Sample Usage

A quick example without Composer
```php
<?php
require_once('vendor/trialog/src/trialog/SimpleCalendar.php');

$calendar = new trialog\SimpleCalendar();  
$calendar->show();
```

Another one using Composer
```php
<?php
require_once('vendor/autoload.php');
use trialog\SimpleCalendar;

$calendar = new SimpleCalendar();  
$calendar->setDate('June 5 2010');  
$calendar->show();
```

A more complex example with translation to French and three events
```php
<?php
require_once('vendor/autoload.php');
use trialog\SimpleCalendar;
use trialog\SimpleCalendarConsts;

// Config
$calendar = new SimpleCalendar('2014/1/4');
$calendar->setStartOfWeek(SimpleCalendarConst::Monday);
$calendar->setDayNames(array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'));
$calendar->setMonthNames(array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'));

// Add events
$calendar->addDailyHtml( 'Example d\'<strong>événement</strong>', SimpleCalendarConst::Yesterday, SimpleCalendarConst::Tomorrow, 'D\'autres petits détails...');
$calendar->addDailyHtml( 'Sample event', SimpleCalendarConst::Yesterday);
$calendar->addDailyHtml( 'An other event', '2014/01/11', '2014/01/15');

// Display
$calendar->show(true);
```

![Screenshot of the last example](https://raw.github.com/trialog/SimpleCalendar/master/example/screenshot.png)


## Contribute

SimpleCalendar can be greatly improved. All comments or pull requests are welcome!

## License

SimpleCalendar is licensed under the MIT License - see the LICENSE file for details
