# Simple PHP Calendar

[![Latest Stable Version](https://poser.pugx.org/donatj/simplecalendar/version)](https://packagist.org/packages/donatj/simplecalendar)
[![License](https://poser.pugx.org/donatj/simplecalendar/license)](https://packagist.org/packages/donatj/simplecalendar)
[![Build Status](https://travis-ci.org/donatj/SimpleCalendar.svg?branch=master)](https://travis-ci.org/donatj/SimpleCalendar)


A very simple, easy to use PHP calendar rendering class.

## Requirements

- **php**: >=5.5.0
- **ext-calendar**: *

## Installing

Install the latest version with:

```bash
composer require 'marcandreappel/simple-calendar'
```

## Examples

```php
<?php
require '../vendor/autoload.php';

$calendar = new MarcAndreAppel\SimpleCalendar\SimpleCalendar('June 2023');

echo $calendar->render();

```

```php
<?php
require '../vendor/autoload.php';

$calendar = new MarcAndreAppel\SimpleCalendar\SimpleCalendar();

$calendar->addEvent('Sample Event', 'today', 'tomorrow');

$calendar->setWeekdays(['Sun', 'Mon', 'Tu', 'W', 'Th', 'F', 'Sa']);
$calendar->setWeekOffset('mon');

echo $calendar->render();
```

## Documentation

### Class: \donatj\SimpleCalendar

Simple Calendar

#### Method: SimpleCalendar->__construct

```php
function __construct([ $calendarDate = null [, $today = null]])
```

##### Parameters:

- ***\DateTimeInterface*** | ***int*** | ***string*** | ***null*** `$calendarDate`
- ***\DateTimeInterface*** | ***false*** | ***int*** | ***string*** | ***null*** `$today`

---

#### Method: SimpleCalendar->setDate

```php
function setDate([ $date = null])
```

Sets the date for the calendar.

##### Parameters:

- ***\DateTimeInterface*** | ***int*** | ***string*** | ***null*** `$date` - DateTimeInterface or Date string parsed by strtotime for the
calendar date. If null set to current timestamp.

---

#### Method: SimpleCalendar->setCalendarClasses

```php
function setCalendarClasses([ array $classes = []])
```

Sets the class names used in the calendar  
  
```php  
[  
   'calendar'     => 'SimpleCalendar',  
   'leading_day'  => 'SCprefix',  
   'trailing_day' => 'SCsuffix',  
   'today'        => 'today',  
   'event'        => 'event',  
   'events'       => 'events',  
]  
```

##### Parameters:

- ***array*** `$classes` - Map of element to class names used by the calendar.

---

#### Method: SimpleCalendar->setToday

```php
function setToday([ $today = null])
```

Sets "today"'s date. Defaults to today.

##### Parameters:

- ***\DateTimeInterface*** | ***false*** | ***string*** | ***null*** `$today` - `null` will default to today, `false` will disable the
rendering of Today.

---

#### Method: SimpleCalendar->setWeekDayNames

```php
function setWeekDayNames([ array $weekDayNames = null])
```

##### Parameters:

- ***string[]*** | ***null*** `$weekDayNames`

---

#### Method: SimpleCalendar->addDailyHtml

```php
function addDailyHtml($html, $startDate [, $endDate = null])
```

Add a daily event to the calendar

##### Parameters:

- ***string*** `$html` - The raw HTML to place on the calendar for this event
- ***\DateTimeInterface*** | ***int*** | ***string*** `$startDate` - Date string for when the event starts
- ***\DateTimeInterface*** | ***int*** | ***string*** | ***null*** `$endDate` - Date string for when the event ends. Defaults to start date

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

- ***int*** | ***string*** `$offset` - Day the week starts on. ex: "Monday" or 0-6 where 0 is Sunday

---

#### Method: SimpleCalendar->show

```php
function show([ $echo = true])
```

Returns/Outputs the Calendar

##### DEPRECATED

Use `render()` method instead.

##### Parameters:

- ***bool*** `$echo` - Whether to echo resulting calendar

##### Returns:

- ***string*** - HTML of the Calendar

---

#### Method: SimpleCalendar->render

```php
function render()
```

Returns the generated Calendar

##### Returns:

- ***string***
