Simple Calendar
===============

Requirements
---------------

  - PHP 5.1.0+

Usage
---------------

`
include( 'SimpleCalendar.php' );  
$Calendar = new SimpleCalendar( 'June 2010' );  
$Calendar->show();
`

or

`
include( 'SimpleCalendar.php' );  
$Calendar = new SimpleCalendar();  
$Calendar->setDate( 'June 5 2010' );  
$Calendar->show();
`