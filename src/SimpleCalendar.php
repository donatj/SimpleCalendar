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
    private Carbon $month;
    private ?Carbon $highlight = null;
    private array $events = [];
    private int $offset = 0;
    private array $cssClasses = [
        'calendar'     => 'simcal',
        'leading_day'  => 'simcal-lead',
        'trailing_day' => 'simcal-trail',
        'highlight'    => 'simcal-highlight',
        'event'        => 'simcal-event',
        'events'       => 'simcal-events',
    ];

    /**
     * @param  Carbon|string|null  $month  Carbon parsable value for the month to show
     * @param  bool|Carbon|string|null  $highlight  Set the day to mark as highlighted in the calendar
     */
    public function __construct(Carbon|null|string $month = null, bool|Carbon|null|string $highlight = null)
    {
        $this->setHighlight($highlight);
        $this->setMonth($month);
        $this->weekdays = Carbon::getDays();
    }

    /**
     * @param  Carbon|string|null  $month
     *
     * @return void
     */
    public function setMonth(Carbon|null|string $month = null): void
    {
        if ($month === null) {
            $this->month = Carbon::now()->startOfMonth();
        } else {
            $this->month = ($month instanceof Carbon) ? $month->startOfMonth() : Carbon::parse($month)->startOfMonth();
        }
    }

    /**
     * @param  bool|Carbon|string|null  $highlight If explicitly `false` then don't highlight any date
     *
     * @return void
     */
    public function setHighlight(bool|Carbon|null|string $highlight = null): void
    {
        if ($highlight === false) {
            $this->highlight = null;
        } elseif ($highlight === true || $highlight === null) {
            $this->highlight = Carbon::now()->startOfDay();
        } else {
            $this->highlight = ($highlight instanceof Carbon) ? $highlight : Carbon::parse($highlight);
        }
    }

    /**
     * Allows for custom CSS classes
     *
     * @param  array  $classes  Map of element to class names used by the calendar.
     *
     * @example
     * ```php
     * [
     *    'calendar'     => 'simcal',
     *    'leading_day'  => 'simcal-lead',
     *    'trailing_day' => 'simcal-trail',
     *    'highlight'        => 'simcal-highlight',
     *    'event'        => 'simcal-event',
     *    'events'       => 'simcal-events',
     * ]
     * ```
     *
     */
    public function setCssClasses(array $classes): void
    {
        foreach ($classes as $key => $value) {
            if (!array_key_exists($key, $this->cssClasses)) {
                throw new InvalidArgumentException("class '{$key}' not supported");
            }

            $this->cssClasses[$key] = $value;
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
     * Add an event to the calendar
     *
     * @param  string  $title  The raw HTML to place on the calendar for this event
     * @param  Carbon|string  $startDate  Date string for when the event starts
     * @param  Carbon|string|null  $endDate  Date string for when the event ends. Defaults to start date
     */
    public function addEvent(string $title, Carbon|string $startDate, Carbon|string|null $endDate = null): void
    {
        static $eventCount = 0;

        if (!$startDate instanceof Carbon) {
            $start = Carbon::parse($startDate);
        }
        if ($endDate === null) {
            $end = $start;
        } else {
            $end = ($endDate instanceof Carbon) ? $endDate : Carbon::parse($endDate);
        }

        if ($start->greaterThan($end)) {
            throw new InvalidArgumentException('The end date must be greater than the start date.');
        }

        do {
            $tDate = $start->clone();

            $this->events[$tDate->year][$tDate->month][$tDate->day][$eventCount] = $title;

            $start->addDay();
        } while ($start->lessThan($end));

        $eventCount++;
    }

    /**
     * Clear all daily events for the calendar
     */
    public function clearDailyHtml()
    {
        $this->events = [];
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
            if ($offset < 0) {
                throw new InvalidArgumentException('Week offset cannot be a negative number.');
            }
            $this->offset = $offset % 7;
        } else {
            try {
                $this->offset = Carbon::parse($offset)->dayOfWeek;
            } catch (InvalidFormatException) {
                throw new InvalidArgumentException('Week offset must be Carbon compatible string.');
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
        $month = $this->month;

        $this->rotate();

        $weekdayIndex = $this->weekdayIndex();
        $daysInMonth  = $this->month->daysInMonth;

        $html = <<<HTML
<table class="{$this->cssClasses['calendar']}"><thead><tr>
HTML;

        foreach ($this->weekdays as $day) {
            $html .= "<th>{$day}</th>";
        }

        $html .= <<<HTML
</tr></thead>
<tbody>
<tr>
HTML;

        $html .= str_repeat(<<<HTML
<td class="{$this->cssClasses['leading_day']}">&nbsp;</td>
HTML
            , $weekdayIndex);


        $count = $weekdayIndex + 1;
        for ($i = 0; $i < $daysInMonth; $i++) {
            $date = $this->month->clone()->addDays($i);

            $setHighlight = $this->highlight !== null && $date->equalTo($this->highlight);

            $html .= '<td'.($setHighlight ? ' class="'.$this->cssClasses['highlight'].'"' : '').'>';

            $html .= sprintf('<time datetime="%s">%d</time>', $date->toDateString(), $date->day);

            $event = $this->events[$month->year][$month->month][$date->day] ?? null;

            if (is_array($event)) {
                $html .= '<div class="'.$this->cssClasses['events'].'">';
                foreach ($event as $dHtml) {
                    $html .= sprintf('<div class="%s">%s</div>', $this->cssClasses['event'], $dHtml);
                }
                $html .= '</div>';
            }

            $html .= '</td>';

            if ($count > 6) {
                $html  .= "</tr>\n".($i < $daysInMonth ? '<tr>' : '');
                $count = 0;
            }
            $count++;
        }

        if ($count !== 1) {
            $html .= str_repeat('<td class="'.$this->cssClasses['trailing_day'].'">&nbsp;</td>', 8 - $count).'</tr>';
        }

        $html .= "\n</tbody></table>\n";

        return $html;
    }

    private function rotate(): void
    {
        $data  = &$this->weekdays;
        $count = count($data);

        $this->offset %= $count;
        for ($i = 0; $i < $this->offset; $i++) {
            $data[] = array_shift($data);
        }
    }

    private function weekdayIndex(): int
    {
        $weekdayIndex = $this->month->startOfMonth()->dayOfWeek;
        if ($this->offset !== Carbon::SUNDAY) {
            if ($this->offset < $weekdayIndex) {
                $weekdayIndex -= $this->offset;
            } elseif ($this->offset > $weekdayIndex) {
                $weekdayIndex += 7 - $this->offset;
            } else {
                $weekdayIndex = 0;
            }
        }

        return $weekdayIndex;
    }

    /**
     * @return string[]
     */
    public function getWeekdays(): array
    {
        return $this->weekdays;
    }
}
