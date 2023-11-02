<?php

namespace donatj;

/**
 * Simple Calendar
 *
 * @author Jesse G. Donat <donatj@gmail.com>
 * @see https://donatstudios.com
 * @license http://opensource.org/licenses/mit-license.php
 */
class SimpleCalendar {

	/**
	 * Array of Week Day Names
	 *
	 * @var string[]|null
	 */
	private $weekDayNames;

	/**
	 * @var \DateTimeInterface
	 */
	private $now;

	/**
	 * @var \DateTimeInterface|null
	 */
	private $today;

	private $classes = [
		'calendar'     => 'SimpleCalendar',
		'leading_day'  => 'SCprefix',
		'trailing_day' => 'SCsuffix',
		'today'        => 'today',
		'event'        => 'event',
		'events'       => 'events',
	];

	private $dailyHtml = [];
	private $offset = 0;

	/**
	 * @param \DateTimeInterface|int|string|null       $calendarDate
	 * @param \DateTimeInterface|false|int|string|null $today
	 *
	 * @see setDate
	 * @see setToday
	 */
	public function __construct( $calendarDate = null, $today = null ) {
		$this->setDate($calendarDate);
		$this->setToday($today);
	}

	/**
	 * Sets the date for the calendar.
	 *
	 * @param \DateTimeInterface|int|string|null $date DateTimeInterface or Date string parsed by strtotime for the
	 *     calendar date. If null set to current timestamp.
	 */
	public function setDate( $date = null ) {
		$this->now = $this->parseDate($date) ?: new \DateTimeImmutable();
	}

	/**
	 * @param \DateTimeInterface|int|string|null $date
	 * @return \DateTimeInterface|null
	 */
	private function parseDate( $date = null ) {
		if( $date instanceof \DateTimeInterface ) {
			return $date;
		}
		if( is_int($date) ) {
			return (new \DateTimeImmutable())->setTimestamp($date);
		}
		if( is_string($date) ) {
			return new \DateTimeImmutable($date);
		}

		return null;
	}

	/**
	 * Sets the class names used in the calendar
	 *
	 * ```php
	 * [
	 *    'calendar'     => 'SimpleCalendar',
	 *    'leading_day'  => 'SCprefix',
	 *    'trailing_day' => 'SCsuffix',
	 *    'today'        => 'today',
	 *    'event'        => 'event',
	 *    'events'       => 'events',
	 * ]
	 * ```
	 *
	 * @param array $classes Map of element to class names used by the calendar.
	 */
	public function setCalendarClasses( array $classes ) {
		foreach( $classes as $key => $value ) {
			if( !isset($this->classes[$key]) ) {
				throw new \InvalidArgumentException("class '{$key}' not supported");
			}

			$this->classes[$key] = $value;
		}
	}

	/**
	 * Sets "today"'s date. Defaults to today.
	 *
	 * @param \DateTimeInterface|false|string|null $today `null` will default to today, `false` will disable the
	 *     rendering of Today.
	 */
	public function setToday( $today = null ) {
		if( $today === false ) {
			$this->today = null;
		} elseif( $today === null ) {
			$this->today = new \DateTimeImmutable();
		} else {
			$this->today = $this->parseDate($today);
		}
	}

	/**
	 * @param string[]|null $weekDayNames
	 */
	public function setWeekDayNames( array $weekDayNames = null ) {
		if( is_array($weekDayNames) && count($weekDayNames) !== 7 ) {
			throw new \InvalidArgumentException('week array must have exactly 7 values');
		}

		$this->weekDayNames = $weekDayNames ? array_values($weekDayNames) : null;
	}

	/**
	 * Add a daily event to the calendar
	 *
	 * @param string                             $html The raw HTML to place on the calendar for this event
	 * @param \DateTimeInterface|int|string      $startDate Date string for when the event starts
	 * @param \DateTimeInterface|int|string|null $endDate Date string for when the event ends. Defaults to start date
	 */
	public function addDailyHtml( $html, $startDate, $endDate = null ) {
		static $htmlCount = 0;

		$start = $this->parseDate($startDate);
		if( !$start ) {
			throw new \InvalidArgumentException('invalid start time');
		}

		$end = $start;
		if( $endDate ) {
			$end = $this->parseDate($endDate);
		}
		if( !$end ) {
			throw new \InvalidArgumentException('invalid end time');
		}

		if( $end->getTimestamp() < $start->getTimestamp() ) {
			throw new \InvalidArgumentException('end must come after start');
		}

		$working = (new \DateTimeImmutable())->setTimestamp($start->getTimestamp());
		do {
			$tDate = getdate($working->getTimestamp());

			$this->dailyHtml[$tDate['year']][$tDate['mon']][$tDate['mday']][$htmlCount] = $html;

			$working = $working->add(new \DateInterval('P1D'));
		} while( $working->getTimestamp() < $end->getTimestamp() + 1 );

		$htmlCount++;
	}

	/**
	 * Clear all daily events for the calendar
	 */
	public function clearDailyHtml() { $this->dailyHtml = []; }

	/**
	 * Sets the first day of the week
	 *
	 * @param int|string $offset Day the week starts on. ex: "Monday" or 0-6 where 0 is Sunday
	 */
	public function setStartOfWeek( $offset ) {
		if( is_int($offset) ) {
			$this->offset = $offset % 7;
		} elseif( $this->weekDayNames !== null && ($weekOffset = array_search($offset, $this->weekDayNames, true)) !== false ) {
			$this->offset = $weekOffset;
		} else {
			$weekTime = strtotime($offset);
			if( $weekTime === 0 ) {
				throw new \InvalidArgumentException('invalid offset');
			}

			$this->offset = date('N', $weekTime) % 7;
		}
	}

	/**
	 * Returns/Outputs the Calendar
	 *
	 * @param bool $echo Whether to echo resulting calendar
	 * @return string HTML of the Calendar
	 * @deprecated Use `render()` method instead.
	 */
	public function show( $echo = true ) {
		$out = $this->render();
		if( $echo ) {
			echo $out;
		}

		return $out;
	}

	/**
	 * Returns the generated Calendar
	 *
	 * @return string
	 */
	public function render() {
		$now   = getdate($this->now->getTimestamp());
		$today = [ 'mday' => -1, 'mon' => -1, 'year' => -1 ];
		if( $this->today !== null ) {
			$today = getdate($this->today->getTimestamp());
		}

		$daysOfWeek = $this->weekdays();
		$this->rotate($daysOfWeek, $this->offset);

		$weekDayIndex = date('N', mktime(0, 0, 1, $now['mon'], 1, $now['year'])) - $this->offset;
		$daysInMonth  = cal_days_in_month(CAL_GREGORIAN, $now['mon'], $now['year']);

		$out = <<<TAG
<table cellpadding="0" cellspacing="0" class="{$this->classes['calendar']}"><thead><tr>
TAG;

		foreach( $daysOfWeek as $dayName ) {
			$out .= "<th>{$dayName}</th>";
		}

		$out .= <<<'TAG'
</tr></thead>
<tbody>
<tr>
TAG;

		$weekDayIndex = ($weekDayIndex + 7) % 7;

		if( $weekDayIndex === 7 ) {
			$weekDayIndex = 0;
		} else {
			$out .= str_repeat(<<<TAG
<td class="{$this->classes['leading_day']}">&nbsp;</td>
TAG
				, $weekDayIndex);
		}

		$count = $weekDayIndex + 1;
		for( $i = 1; $i <= $daysInMonth; $i++ ) {
			$date = (new \DateTimeImmutable())->setDate($now['year'], $now['mon'], $i);

			$isToday = false;
			if( $this->today !== null ) {
				$isToday = $i == $today['mday']
					&& $today['mon'] == $date->format('n')
					&& $today['year'] == $date->format('Y');
			}

			$out .= '<td' . ($isToday ? ' class="' . $this->classes['today'] . '"' : '') . '>';

			$out .= sprintf('<time datetime="%s">%d</time>', $date->format('Y-m-d'), $i);

			$dailyHTML = null;
			if( isset($this->dailyHtml[$now['year']][$now['mon']][$i]) ) {
				$dailyHTML = $this->dailyHtml[$now['year']][$now['mon']][$i];
			}

			if( is_array($dailyHTML) ) {
				$out .= '<div class="' . $this->classes['events'] . '">';
				foreach( $dailyHTML as $dHtml ) {
					$out .= sprintf('<div class="%s">%s</div>', $this->classes['event'], $dHtml);
				}
				$out .= '</div>';
			}

			$out .= '</td>';

			if( $count > 6 ) {
				$out   .= "</tr>\n" . ($i < $daysInMonth ? '<tr>' : '');
				$count = 0;
			}
			$count++;
		}

		if( $count !== 1 ) {
			$out .= str_repeat('<td class="' . $this->classes['trailing_day'] . '">&nbsp;</td>', 8 - $count) . '</tr>';
		}

		$out .= "\n</tbody></table>\n";

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
	 * @return string[]
	 */
	private function weekdays() {
		if( $this->weekDayNames !== null ) {
			return $this->weekDayNames;
		}

		$today = (86400 * (date('N')));
		$wDays = [];
		for( $n = 0; $n < 7; $n++ ) {
			$wDays[] = date('D', time() - $today + ($n * 86400));
		}

		return $wDays;
	}

}
