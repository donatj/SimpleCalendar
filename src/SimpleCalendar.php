<?php

namespace donatj;

/**
 * Simple Calendar
 *
 * @author Jesse G. Donat <donatj@gmail.com>
 * @see http://donatstudios.com
 * @license http://opensource.org/licenses/mit-license.php
 */
class SimpleCalendar {

	/**
	 * Array of Week Day Names
	 *
	 * @var array|false
	 */
	public $wday_names = false;

	/**
	 * @var \DateTimeInterface
	 */
	private $now;
	private $dailyHtml = [];
	private $offset = 0;

	/**
	 * Constructor - Calls the setDate function
	 *
	 * @see setDate
	 * @param string|null $date_string
	 * @throws \Exception
	 */
	public function __construct( $date_string = null ) {
		$this->setDate($date_string);
	}

	/**
	 * Sets the date for the calendar
	 *
	 * @param \DateTimeInterface|string|null $date DateTimeInterface or Date string parsed by strtotime for the calendar
	 *                                             date. If null set to current timestamp.
	 * @throws \Exception
	 */
	public function setDate( $date = null ) {
		if( $date instanceof \DateTimeInterface ) {
			$this->now = $date;
		} elseif( is_string($date) ) {
			$this->now = new \DateTimeImmutable($date);
		} else {
			$this->now = new \DateTimeImmutable();
		}
	}

	/**
	 * Add a daily event to the calendar
	 *
	 * @param string      $html The raw HTML to place on the calendar for this event
	 * @param string      $start_date_string Date string for when the event starts
	 * @param string|null $end_date_string Date string for when the event ends. Defaults to start date
	 */
	public function addDailyHtml( $html, $start_date_string, $end_date_string = null ) {
		static $htmlCount = 0;
		$start_date = strtotime($start_date_string);
		if( $end_date_string ) {
			$end_date = strtotime($end_date_string);
		} else {
			$end_date = $start_date;
		}

		$working_date = $start_date;
		do {
			$tDate        = getdate($working_date);
			$working_date += 86400;

			$this->dailyHtml[$tDate['year']][$tDate['mon']][$tDate['mday']][$htmlCount] = $html;
		} while( $working_date < $end_date + 1 );

		$htmlCount++;
	}

	/**
	 * Clear all daily events for the calendar
	 */
	public function clearDailyHtml() { $this->dailyHtml = []; }

	/**
	 * Sets the first day of the week
	 *
	 * @param int|string $offset Day to start on, ex: "Monday" or 0-6 where 0 is Sunday
	 */
	public function setStartOfWeek( $offset ) {
		if( is_int($offset) ) {
			$this->offset = $offset % 7;
		} else {
			$this->offset = date('N', strtotime($offset)) % 7;
		}
	}

	/**
	 * Returns/Outputs the Calendar
	 *
	 * @param bool $echo Whether to echo resulting calendar
	 * @return string HTML of the Calendar
	 */
	public function show( $echo = true ) {
		$now = getdate($this->now->getTimestamp());

		if( $this->wday_names ) {
			$wdays = $this->wday_names;
		} else {
			$today = (86400 * (date("N")));
			$wdays = [];
			for( $i = 0; $i < 7; $i++ ) {
				$wdays[] = strftime('%a', time() - $today + ($i * 86400));
			}
		}

		$this->rotate($wdays, $this->offset);
		$wday    = date('N', mktime(0, 0, 1, $now['mon'], 1, $now['year'])) - $this->offset;
		$no_days = cal_days_in_month(CAL_GREGORIAN, $now['mon'], $now['year']);

		$out = <<<'TAG'
<table cellpadding="0" cellspacing="0" class="SimpleCalendar"><thead><tr>
TAG;

		for( $i = 0; $i < 7; $i++ ) {
			$out .= '<th>' . $wdays[$i] . '</th>';
		}

		$out .= <<<'TAG'
</tr></thead>
<tbody>
<tr>
TAG;

		$wday = ($wday + 7) % 7;

		if( $wday == 7 ) {
			$wday = 0;
		} else {
			$out .= str_repeat(<<<'TAG'
<td class="SCprefix">&nbsp;</td>
TAG
				, $wday);
		}

		$count = $wday + 1;
		for( $i = 1; $i <= $no_days; $i++ ) {

			$isToday = $i == $now['mday'] && $now['mon'] == date('n') && $now['year'] == date('Y');

			$out .= '<td' . ($isToday ? ' class="today"' : '') . '>';

			$datetime = mktime(0, 0, 1, $now['mon'], $i, $now['year']);

			$out .= '<time datetime="' . date('Y-m-d', $datetime) . '">' . $i . '</time>';

			$dHtml_arr = false;
			if( isset($this->dailyHtml[$now['year']][$now['mon']][$i]) ) {
				$dHtml_arr = $this->dailyHtml[$now['year']][$now['mon']][$i];
			}

			if( is_array($dHtml_arr) ) {
				foreach( $dHtml_arr as $dHtml ) {
					$out .= '<div class="event">' . $dHtml . '</div>';
				}
			}

			$out .= "</td>";

			if( $count > 6 ) {
				$out   .= "</tr>\n" . ($i < $no_days ? '<tr>' : '');
				$count = 0;
			}
			$count++;
		}
		$out .= ($count != 1) ? str_repeat('<td class="SCsuffix">&nbsp;</td>', 8 - $count) . '</tr>' : '';
		$out .= "\n</tbody></table>\n";
		if( $echo ) {
			echo $out;
		}

		return $out;
	}

	/**
	 * @param int $steps
	 */
	private function rotate( array &$data, $steps ) {
		$count = count($data);
		if( $steps < 0 ) {
			$steps = $count + $steps;
		}
		$steps = $steps % $count;
		for( $i = 0; $i < $steps; $i++ ) {
			array_push($data, array_shift($data));
		}
	}
}
