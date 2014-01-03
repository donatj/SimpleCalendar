<!DOCTYPE html>
<html lang="fr_FR">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="author" content="Olivier Maridar (Trialog)" />
	<link rel="stylesheet" href="../web/css/SimpleCalendar.css" />
</head>
<body>
<?php
error_reporting(E_ALL ^ E_WARNING);
require_once(__DIR__.'/../vendor/autoload.php');
use donatj\SimpleCalendar;
use donatj\SimpleCalendarConst;

$calendar = new SimpleCalendar();
$calendar->setStartOfWeek(SimpleCalendarConst::Monday);
$calendar->setDayNames(array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'));
$calendar->setMonthNames(array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'));
$calendar->addDailyHtml( 'Example d\'<strong>événement</strong>', SimpleCalendarConst::Yesterday, SimpleCalendarConst::Tomorrow);
$calendar->show(true);
?>
</body>
</html>