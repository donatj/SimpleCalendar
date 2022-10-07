<?php
declare(strict_types=1);

namespace MarcAndreAppel\SimpleCalendar;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use InvalidArgumentException;

/**
 * Simple Calendar
 *
 * @author Marc-André Appel <marc-andre@appel.fun>
 * @author Jesse G. Donat <donatj@gmail.com>
 * @see https://github.com/marcandreappel/simple-calendar
 * @license http://opensource.org/licenses/mit-license.php
 */
class SimpleCalendar
{
    private array $weekdays;
    private Carbon $now;
    private Carbon $today;
    private array $dailyHtml = [];
    private int $offset = 0;
    private array $cssClasses = [
        'calendar'     => 'simcal',
        'leading_day'  => 'simcal-lead',
        'trailing_day' => 'simcal-trail',
        'today'        => 'simcal-today',
        'event'        => 'simcal-event',
        'events'       => 'simcal-events',
    ];

    /**
     * @param  string|null  $date  For the actual month to show
     * @param  string|null  $today  To highlight the day on the calendar
     *
     * @throws InvalidFormatException
     */
    public function __construct(?string $date = null, ?string $today = null)
    {
        $this->now   = ($date === null) ? Carbon::now() : Carbon::parse($date);
        $this->today = ($today === null) ? Carbon::now() : Carbon::parse($today);
    }

    /**
     * @param  \DateTimeInterface|int|string|null  $date
     *
     * @return \DateTimeInterface|null
     * @obsolete
     */
    private function parseDate($date = null)
    {
        if ($date instanceof \DateTimeInterface) {
            return $date;
        }
        if (is_int($date)) {
            return (new \DateTimeImmutable())->setTimestamp($date);
        }
        if (is_string($date)) {
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
     * @param  array  $classes  Map of element to class names used by the calendar.
     */
    public function setCssClasses(array $classes)
    {
        foreach ($classes as $key => $value) {
            if (!isset($this->classes[$key])) {
                throw new InvalidArgumentException("class '{$key}' not supported");
            }

            $this->classes[$key] = $value;
        }
    }

    /**
     * Overwrites the default names for the weekdays
     *
     * @param  array<string>  $weekdays
     */
    public function setWeekdays(array $weekdays = []): void
    {
        if (!empty($weekdays) && count($weekdays) !== 7) {
            throw new InvalidArgumentException('Week day names array must have exactly 7 values');
        }

        $this->weekdays = $weekdays ? array_values($weekdays) : Carbon::getDays();
    }

    /**
     * Add a daily event to the calendar
     *
     * @param  string  $title  The raw HTML to place on the calendar for this event
     * @param  \DateTimeInterface|int|string  $startDate  Date string for when the event starts
     * @param  \DateTimeInterface|int|string|null  $endDate  Date string for when the event ends. Defaults to start date
     *
     * @throws InvalidFormatException|InvalidArgumentException
     */
    public function addEvent(string $title, string $startDate, ?string $endDate = null)
    {
        static $htmlCount = 0;

        $start = Carbon::parse($startDate);
        $end = $start;

        if ($endDate) {
            $end = Carbon::parse($endDate);
        }

        if ($end->gt($start)) {
            throw new InvalidArgumentException('end must come after start');
        }

        $working = (new \DateTimeImmutable())->setTimestamp($start->getTimestamp());
        do {
            $tDate = getdate($working->getTimestamp());

            $this->dailyHtml[$tDate['year']][$tDate['mon']][$tDate['mday']][$htmlCount] = $title;

            $working = $working->add(new \DateInterval('P1D'));
        } while ($working->getTimestamp() < $end->getTimestamp() + 1);

        $htmlCount++;
    }

    /**
     * Clear all daily events for the calendar
     */
    public function clearDailyHtml()
    {
        $this->dailyHtml = [];
    }

    /**
     * Sets the first day of the week
     *
     * @param  int|string  $offset  Day the week starts on
     *
     * @example "Monday", "mon" or 0-6, where 0 is Sunday.
     */
    public function setWeekOffset(int|string $offset): void
    {
        if (is_int($offset)) {
            $this->offset = $offset % 7;
        } else {
            try {
                $this->offset = Carbon::parse($offset)->dayOfWeek % 7;
            } catch (InvalidFormatException) {
                $this->offset = 0;
            }
        }
    }

    /**
     * Returns the generated Calendar
     *
     * @return string
     */
    public function render(): string
    {
        $now   = getdate($this->now->getTimestamp());
        $today = ['mday' => -1, 'mon' => -1, 'year' => -1];
        if ($this->today !== null) {
            $today = getdate($this->today->getTimestamp());
        }

        $daysOfWeek = $this->weekdays();
        $this->rotate($daysOfWeek, $this->offset);

        $weekDayIndex = date('N', mktime(0, 0, 1, $now['mon'], 1, $now['year'])) - $this->offset;
        $daysInMonth  = cal_days_in_month(CAL_GREGORIAN, $now['mon'], $now['year']);

        $out = <<<TAG
<table cellpadding="0" cellspacing="0" class="{$this->classes['calendar']}"><thead><tr>
TAG;

        foreach ($daysOfWeek as $dayName) {
            $out .= "<th>{$dayName}</th>";
        }

        $out .= <<<'TAG'
</tr></thead>
<tbody>
<tr>
TAG;

        $weekDayIndex = ($weekDayIndex + 7) % 7;

        if ($weekDayIndex === 7) {
            $weekDayIndex = 0;
        } else {
            $out .= str_repeat(<<<TAG
<td class="{$this->classes['leading_day']}">&nbsp;</td>
TAG
                , $weekDayIndex);
        }

        $count = $weekDayIndex + 1;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = (new \DateTimeImmutable())->setDate($now['year'], $now['mon'], $i);

            $isToday = false;
            if ($this->today !== null) {
                $isToday = $i == $today['mday']
                    && $today['mon'] == $date->format('n')
                    && $today['year'] == $date->format('Y');
            }

            $out .= '<td'.($isToday ? ' class="'.$this->classes['today'].'"' : '').'>';

            $out .= sprintf('<time datetime="%s">%d</time>', $date->format('Y-m-d'), $i);

            $dailyHTML = null;
            if (isset($this->dailyHtml[$now['year']][$now['mon']][$i])) {
                $dailyHTML = $this->dailyHtml[$now['year']][$now['mon']][$i];
            }

            if (is_array($dailyHTML)) {
                $out .= '<div class="'.$this->classes['events'].'">';
                foreach ($dailyHTML as $dHtml) {
                    $out .= sprintf('<div class="%s">%s</div>', $this->classes['event'], $dHtml);
                }
                $out .= '</div>';
            }

            $out .= '</td>';

            if ($count > 6) {
                $out   .= "</tr>\n".($i < $daysInMonth ? '<tr>' : '');
                $count = 0;
            }
            $count++;
        }

        if ($count !== 1) {
            $out .= str_repeat('<td class="'.$this->classes['trailing_day'].'">&nbsp;</td>', 8 - $count).'</tr>';
        }

        $out .= "\n</tbody></table>\n";

        return $out;
    }

    /**
     * @param  int  $steps
     */
    private function rotate(array &$data, $steps)
    {
        $count = count($data);
        if ($steps < 0) {
            $steps = $count + $steps;
        }
        $steps %= $count;
        for ($i = 0; $i < $steps; $i++) {
            $data[] = array_shift($data);
        }
    }

    /**
     * @return string[]
     */
    private function weekdays()
    {
        if ($this->weekdays !== null) {
            $wDays = $this->weekdays;
        } else {
            $today = (86400 * (date('N')));
            $wDays = [];
            for ($n = 0; $n < 7; $n++) {
                $wDays[] = strftime('%a', time() - $today + ($n * 86400));
            }
        }

        return $wDays;
    }

}
