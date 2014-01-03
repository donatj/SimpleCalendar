<!DOCTYPE html>
<html lang="fr_FR">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="author" content="Olivier Maridar (Trialog)" />
	<link rel="stylesheet" href="../web/css/SimpleCalendar.min.css" />
</head>
<body>
<?php
// error_reporting(E_ALL ^ E_WARNING);
require_once(__DIR__.'/../vendor/autoload.php');
use trialog\SimpleCalendar;
use trialog\SimpleCalendarConst;

$calendar = new SimpleCalendar('2014/1/4');
$calendar->setStartOfWeek(SimpleCalendarConst::Monday);
$calendar->setDayNames(array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'));
$calendar->setMonthNames(array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'));
$calendar->addDailyHtml( 'Example d\'<strong>événement</strong>', SimpleCalendarConst::Yesterday, SimpleCalendarConst::Tomorrow, 'D\'autres petits détails...');
$calendar->addDailyHtml( 'Sample event', SimpleCalendarConst::Yesterday);
$calendar->addDailyHtml( 'An other event', '2014/01/11', '2014/01/15');
$calendar->show(true);
?>
</body>
</html>