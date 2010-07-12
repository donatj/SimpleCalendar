<?php

/**
* Simple Calendar
* 
* @author Jesse G. Donat
* @link http://donatstudios.com
* @license http://opensource.org/licenses/mit-license.php
* 
*/
class SimpleCalendar {

	private $now = false;
	private $daily_html = array();

	/**
	* Array of Week Day Names
	* 
	* @var array
	*/
	public $wday_names = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");

	/**
	* Constructor - Calls the setDate function
	* 
	* @see setDate
	* @param null|string $date_string
	* @return SimpleCalendar
	*/
	function __construct( $date_string = null ) {
		$this->setDate( $date_string );
	}

	/**
	* Sets the date for the calendar
	* 
	* @param null|string $date_string Date string parsed by strtotime for the calendar date. If null set to current timestamp.
	*/
	public function setDate( $date_string = null ) {
		if( $date_string ) {
			$this->now = getdate( strtotime( $date_string ) );
		}else{
			$this->now = getdate();
		}
	}
	
	public function addDailyHtml( $html, $start_date_string, $end_date_string = false ) {
		static $htmlCount = 0;
		$start_date = strtotime( $start_date_string );
		if( $end_date_string ) { 
			$end_date = strtotime( $end_date_string ); 
		}else{
			$end_date = $start_date;	
		}
		
		$working_date = $start_date;
		do{
			$tDate = getdate( $working_date );
			$working_date += 86400;
			$this->daily_html[ $tDate['year'] ][ $tDate['mon'] ][ $tDate['mday'] ][ $htmlCount ] = $html;
		}while( $working_date < $end_date + 1 );
		
		$htmlCount++;
		
	}
	
	public function clearDailyHtml() { $this->daily_html = array(); }

	/**
	* Show the Calendars current date
	* 
	* @param bool $show_today Whether to highlight the current day
	* @param bool $echo Whether to echo resulting calendar
	* @return string
	*/
	public function show( $show_today = false, $echo = true ) {

		$wday    = date( 'N' , mktime( 0,0,1, $this->now['mon'], 1, $this->now['year'] ));
		$no_days = cal_days_in_month( CAL_GREGORIAN, $this->now['mon'], $this->now['year'] );

		$out = '';
		$out .= "\n<table cellpadding=\"0\" cellspacing=\"0\" class=\"SimpleCalendar\"><thead><tr>";
		for( $i = 0; $i < 7; $i++ ) { $out .= '<th>'. $this->wday_names[$i]. '</th>'; }
		
		$out .= "</tr></thead>\n<tbody>\n<tr>";
		
		if( $wday == 7 ) {
			$wday = 0;
		}else{
			$out .= str_repeat( '<td>&nbsp;</td>', $wday );
		}

		$count = $wday + 1;
		for($i=1;$i<=$no_days;$i++) {
			$out .= "<td>";
			if( $show_today && $i == $this->now['mday'] ) { $out .= "<strong>$i</strong>"; } else { $out .= $i; }
			
			$dHtml_arr = $this->daily_html[ $this->now['year'] ][ $this->now['mon'] ][ $i ];
			
			if( is_array( $dHtml_arr ) ) {
				foreach( $dHtml_arr as $eid => $dHtml ) {
					$out .= '<div>' . $dHtml . '</div>';
				}
			}
			
			$out .= "</td>";

			if( $count > 6 ) {
				$out .= "</tr>\n" . ( $i != $count ? '<tr>' : '' );
				$count = 0;
			}
			$count++;
		}
		$out .= ( $count != 1 ? str_repeat( '<td>&nbsp;</td>', 8 - $count ) : '' ) . "</tr>\n</tbody></table>\n";		
		if( $echo ) { echo $out; }
		return $out;
	}

}