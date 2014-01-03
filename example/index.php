<html>
<head><link rel="stylesheet" href="../web/css/SimpleCalendar.css" /></head>
<body>
<?php
error_reporting(E_ALL ^ E_WARNING);
require_once(__DIR__.'/../vendor/autoload.php');
use donatj\SimpleCalendar;
use donatj\SimpleCalendarConst;

$calendar = new SimpleCalendar();
$calendar->setStartOfWeek(SimpleCalendarConst::Monday);
$calendar->addDailyHtml( 'Sample Event', SimpleCalendarConst::Yesterday, SimpleCalendarConst::Tomorrow);
$calendar->show(true);
?>
</body>
</html>