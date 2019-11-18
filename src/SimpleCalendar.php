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
	 * @var array|null
	 */
	public $wday_names = null;

	/**
	 * @var \DateTimeInterface
	 */
	private $now;

	/**
	 * @var \DateTimeInterface|null
	 */
	private $today;

	private $dailyHtml = [];
	private $offset = 0;

	/**
	 * @param \DateTimeInterface|string|null $calendarDate
	 * @param \DateTimeInterface|string|false|null $today
	 * @throws \Exception on failing to parse given dates
	 *
	 * @see setDate
	 * @see setToday
	 */
	public function __construct( $calendarDate = null, $today = null ) {
		$this->setDate($calendarDate);
		$this->setToday($today);
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
	 * @param \DateTimeInterface|string|null|false $today
	 * @throws \Exception
	 */
	public function setToday( $today = null ) {
		if( $today instanceof \DateTimeInterface ) {
			$this->today = $today;
		} elseif( is_string($today) ) {
			$this->now = new \DateTimeImmutable($today);
		} elseif( $today === null ) {
			$this->today = new \DateTimeImmutable();
		} elseif( $today === false ) {
			$this->today = null;
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
		$end_date = $start_date;

		if( $end_date_string ) {
			$end_date = strtotime($end_date_string);
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
		$today = ['mday' => -1, 'mon' => -1, 'year' => -1];
		if($this->today !== null) {
			$today = getdate($this->today->getTimestamp());
		}

		$wDays = $this->weekdays();
		$this->rotate($wDays, $this->offset);

		$wDay    = date('N', mktime(0, 0, 1, $now['mon'], 1, $now['year'])) - $this->offset;
		$no_days = cal_days_in_month(CAL_GREGORIAN, $now['mon'], $now['year']);

		$out = <<<'TAG'
<table cellpadding="0" cellspacing="0" class="SimpleCalendar"><thead><tr>
TAG;

		foreach($wDays as $wd) {
			$out .= "<th>{$wd}</th>";
		}

		$out .= <<<'TAG'
</tr></thead>
<tbody>
<tr>
TAG;

		$wDay = ($wDay + 7) % 7;

		if( $wDay == 7 ) {
			$wDay = 0;
		} else {
			$out .= str_repeat(<<<'TAG'
<td class="SCprefix">&nbsp;</td>
TAG
				, $wDay);
		}

		$count = $wDay + 1;
		for( $i = 1; $i <= $no_days; $i++ ) {
			$date = (new \DateTimeImmutable())->setDate($now['year'], $now['mon'], $i);

			$isToday = false;
			if($this->today !== null) {
				$isToday = $i == $today['mday']
					&& $today['mon'] == $date->format('n')
					&& $today['year'] == $date->format('Y');
			}

			$out .= '<td' . ($isToday ? ' class="today"' : '') . '>';

			$out .= sprintf('<time datetime="%s">%d</time>', $date->format('Y-m-d'), $i);

			$dHtml_arr = false;
			if( isset($this->dailyHtml[$now['year']][$now['mon']][$i]) ) {
				$dHtml_arr = $this->dailyHtml[$now['year']][$now['mon']][$i];
			}

			if( is_array($dHtml_arr) ) {
				foreach( $dHtml_arr as $dHtml ) {
					$out .= sprintf('<div class="event">%s</div>', $dHtml);
				}
			}

			$out .= '</td>';

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
		$steps %= $count;
		for( $i = 0; $i < $steps; $i++ ) {
			$data[] = array_shift($data);
		}
	}

	/**
	 * @return array|null
	 */
	private function weekdays() {
		if( $this->wday_names ) {
			$wDays = $this->wday_names;
		} else {
			$today = (86400 * (date('N')));
			$wDays = [];
			for( $n = 0; $n < 7; $n++ ) {
				$wDays[] = strftime('%a', time() - $today + ($n * 86400));
			}
		}

		return $wDays;
	}

	/**
	 * @param array|null $weekDayNames
	 */
	public function setWeekDayNames( $weekDayNames ) {
		$this->wday_names = $weekDayNames;
	}

}
