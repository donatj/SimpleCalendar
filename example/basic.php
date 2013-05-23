<html>
<head><link rel="stylesheet" href="../lib/css/SimpleCalendar.css" /></head>
<body>
<?php
error_reporting(E_ALL ^ E_WARNING);
require_once('../lib/donatj/SimpleCalendar.php');

$calendar = new donatj\SimpleCalendar();

$calendar->setStartOfWeek('Sunday');

$calendar->addDailyHtml( 'Sample Event', 'today', 'tomorrow' );
$calendar->show(true);
?>
</body>
</html>