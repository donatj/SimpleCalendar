# Simple Calendar

[![Latest Stable Version](https://poser.pugx.org/donatj/simplecalendar/version)](https://packagist.org/packages/donatj/simplecalendar)
[![License](https://poser.pugx.org/donatj/simplecalendar/license)](https://packagist.org/packages/donatj/simplecalendar)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/donatj/SimpleCalendar/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/donatj/SimpleCalendar)
[![Build Status](https://travis-ci.org/donatj/SimpleCalendar.svg?branch=master)](https://travis-ci.org/donatj/SimpleCalendar)


A very simple, easy to use PHP calendar rendering class.

## Requirements

- **php**: >=5.3.0
- **ext-calendar**: *

## Installing

Install the latest version with:

```bash
composer require 'donatj/simplecalendar'
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

## Documentation

### Class: \donatj\SimpleCalendar

Simple Calendar

```php
<?php
namespace donatj;

class SimpleCalendar {
	/**
	 * Array of Week Day Names
	 * @var array|false
	 */
	public $wday_names = false;
}
```

#### Method: SimpleCalendar->__construct

```php
function __construct([ $date_string = null])
```

Constructor - Calls the setDate function

##### Parameters:

- ***null*** | ***string*** `$date_string`

---

#### Method: SimpleCalendar->setDate

```php
function setDate([ $date_string = null])
```

Sets the date for the calendar

##### Parameters:

- ***null*** | ***string*** `$date_string` - Date string parsed by strtotime for the calendar date. If null set to current timestamp.

---

#### Method: SimpleCalendar->addDailyHtml

```php
function addDailyHtml($html, $start_date_string [, $end_date_string = null])
```

Add a daily event to the calendar

##### Parameters:

- ***string*** `$html` - The raw HTML to place on the calendar for this event
- ***string*** `$start_date_string` - Date string for when the event starts
- ***null*** | ***string*** `$end_date_string` - Date string for when the event ends. Defaults to start date

---

#### Method: SimpleCalendar->clearDailyHtml

```php
function clearDailyHtml()
```

Clear all daily events for the calendar

---

#### Method: SimpleCalendar->setStartOfWeek

```php
function setStartOfWeek($offset)
```

Sets the first day of the week

##### Parameters:

- ***int*** | ***string*** `$offset` - Day to start on, ex: "Monday" or 0-6 where 0 is Sunday

---

#### Method: SimpleCalendar->show

```php
function show([ $echo = true])
```

Returns/Outputs the Calendar

##### Parameters:

- ***bool*** `$echo` - Whether to echo resulting calendar

##### Returns:

- ***string*** - HTML of the Calendar