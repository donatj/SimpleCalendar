<?php

namespace donatj;

/**
 * Simple Calendar
 *
 * @author Jesse G. Donat <donatj@gmail.com>
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
	public $wday_names = false;

	/**
	 * Constructor - Calls the setDate function
	 *
	 * @see setDate
	 * @param null|string $date_string
	 * @return SimpleCalendar
	 */
	function __construct( $date_string = null ) {
		$this->setDate($date_string);
	}

	/**
	 * Sets the date for the calendar
	 *
	 * @param null|string $date_string Date string parsed by strtotime for the calendar date. If null set to current timestamp.
	 */
	public function setDate( $date_string = null ) {
		if( $date_string ) {
			$this->now = getdate(strtotime($date_string));
		} else {
			$this->now = getdate();
		}
	}

	/**
	 * Add a daily event to the calendar
	 *
	 * @param string      $html The raw HTML to place on the calendar for this event
	 * @param string      $start_date_string Date string for when the event starts
	 * @param bool|string $end_date_string Date string for when the event ends. Defaults to start date
	 * @return void
	 */
	public function addDailyHtml( $html, $start_date_string, $end_date_string = false ) {
		static $htmlCount = 0;
		$start_date = strtotime($start_date_string);
		if( $end_date_string ) {
			$end_date = strtotime($end_date_string);
		} else {
			$end_date = $start_date;
		}

		$working_date = $start_date;
		do {
			$tDate = getdate($working_date);
			$working_date += 86400;
			$this->daily_html[$tDate['year']][$tDate['mon']][$tDate['mday']][$htmlCount] = $html;
		} while( $working_date < $end_date + 1 );

		$htmlCount++;

	}

	/**
	 * Clear all daily events for the calendar
	 *
	 * @return void
	 */
	public function clearDailyHtml() { $this->daily_html = array(); }

	/**
	 * Show the Calendars current date
	 *
	 * @param bool $show_today Whether to highlight the current day
	 * @param bool $echo Whether to echo resulting calendar
	 * @return string
	 */
	public function show( $show_today = false, $echo = true ) {
		if( $this->wday_names ) {
			$wdays = $this->wday_names;
		} else {
			$today = (86400 * (date("N")));
			for( $i = 0; $i < 7; $i++ ) {
				$wdays[] = strftime('%a', time() - $today + ($i * 86400));
			}
		}

		$wday    = date('N', mktime(0, 0, 1, $this->now['mon'], 1, $this->now['year']));
		$no_days = cal_days_in_month(CAL_GREGORIAN, $this->now['mon'], $this->now['year']);

		$out = '';
		$out .= "\n<table cellpadding=\"0\" cellspacing=\"0\" class=\"SimpleCalendar\"><thead><tr>";
		for( $i = 0; $i < 7; $i++ ) {
			$out .= '<th>' . $wdays[$i] . '</th>';
		}

		$out .= "</tr></thead>\n<tbody>\n<tr>";

		if( $wday == 7 ) {
			$wday = 0;
		} else {
			$out .= str_repeat('<td class="SCprefix">&nbsp;</td>', $wday);
		}

		$count = $wday + 1;
		for( $i = 1; $i <= $no_days; $i++ ) {
			$out .= "<td>";
			// if( $show_today && $i == $this->now['mday'] ) {
			// 	$out .= "<strong>$i</strong>";
			// } else {
			// 	$out .= $i;
			// }
			
			$datetime = mktime ( 0, 0, 1, $this->now['mon'], $i, $this->now['year'] );

			$out .= '<time datetime="' . date('Y-m-d', $datetime) . '">'.$i.'</time>';

			$dHtml_arr = $this->daily_html[$this->now['year']][$this->now['mon']][$i];

			if( is_array($dHtml_arr) ) {
				foreach( $dHtml_arr as $eid => $dHtml ) {
					$out .= '<div class="event">' . $dHtml . '</div>';
				}
			}

			$out .= "</td>";

			if( $count > 6 ) {
				$out .= "</tr>\n" . ($i != $count ? '<tr>' : '');
				$count = 0;
			}
			$count++;
		}
		$out .= ($count != 1 ? str_repeat('<td class="SCsuffix">&nbsp;</td>', 8 - $count) : '') . "</tr>\n</tbody></table>\n";
		if( $echo ) {
			echo $out;
		}

		return $out;
	}

}