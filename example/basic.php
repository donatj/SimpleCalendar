<?php
require '../vendor/autoload.php';

echo '<link rel="stylesheet" href="../src/css/SimpleCalendar.css" />';

$calendar = new donatj\SimpleCalendar();

$calendar->setStartOfWeek('Sunday');
$calendar->addDailyHtml('Sample Event', 'today', 'tomorrow');

$calendar->setWeekDayNames([ 'Sun', 'Mon', 'Tu', 'W', 'Th', 'F', 'Sa' ]);
$calendar->setStartOfWeek('Monday');

echo $calendar->render();
