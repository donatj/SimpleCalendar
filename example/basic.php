<html>
<head><link rel="stylesheet" href="../lib/css/SimpleCalendar.css" /></head>
<body>
	<?php
	error_reporting(E_ALL);
	require_once('../lib/donatj/SimpleCalendar.php');

	$calendar = new donatj\SimpleCalendar();

	$calendar->addDailyHtml( 'Sample Event', 'today', 'tomorrow' );
	$calendar->show(true);
	?>
</body>
</html>