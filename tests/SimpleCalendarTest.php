<?php
declare(strict_types=1);

use Carbon\Carbon;
use MarcAndreAppel\SimpleCalendar\SimpleCalendar;
use PHPUnit\Framework\TestCase;

class SimpleCalendarTest extends TestCase
{
    /**
     * @test
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::getWeekdays()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight()
     */
    public function initializes_correctly_without_parameters(): void
    {
        $simpleCalendar = new SimpleCalendar();
        $weekdays       = Carbon::getDays();

        $this->assertEquals($simpleCalendar->getWeekdays(), $weekdays);
    }

    /**
     * @test
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::weekdayIndex()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::rotate()
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::render()
     */
    public function shows_current_month(): void
    {
        $simCal = new SimpleCalendar();

        $this->assertNotFalse(str_contains($simCal->render(), 'class="simcal-highlight"'));
    }

    /**
     * @test
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::addEvent()
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::weekdayIndex()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::rotate()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::render()
     */
    public function adding_multiple_events_returns_correct_result(): void
    {
        $simpleCalendar = new SimpleCalendar('October 2022', false);
        $simpleCalendar->addEvent('Test Event #1', 'October 15 2022');
        $simpleCalendar->addEvent('Test Event #2', 'October 30');

        $html = $simpleCalendar->render();

        $this->assertTrue(str_contains($html, '<td><time datetime="2022-10-15">15</time><div class="simcal-events"><div class="simcal-event">Test Event #1</div></div></td>'));
        $this->assertTrue(str_contains($html, '<td><time datetime="2022-10-30">30</time><div class="simcal-events"><div class="simcal-event">Test Event #2</div></div></td>'));
    }

    /**
     * @test
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::addEvent()
     */
    public function throws_exception_for_greater_end_date(): void
    {
        try {
            $cal = new SimpleCalendar('June 2010', 'June 5 2010');
            $cal->addEvent('foo', 'tomorrow', 'yesterday');
        } catch (InvalidArgumentException) {
            $this->assertTrue(true);

            return;
        }
        $this->fail('Whoops, expected InvalidArgumentException.');
    }

    /**
     * @test
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::getWeekdays()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setWeekOffset()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::rotate()
     *
     * @throws ReflectionException
     */
    public function rotate_method_produces_correct_result(): void
    {
        $rotate         = self::getMethod('rotate');
        $simpleCalendar = new SimpleCalendar();
        $simpleCalendar->setWeekOffset(Carbon::TUESDAY);
        $rotate->invoke($simpleCalendar);

        $expected = ['Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'Monday'];

        $this->assertEquals($expected, $simpleCalendar->getWeekdays());
    }

    /**
     * @test
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setWeekOffset()
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::weekdayIndex()
     *
     * @throws ReflectionException
     */
    public function method_weekdayIndex_produces_correct_results(): void
    {
        $weekdayIndex = self::getMethod('weekdayIndex');

        $simpleCalendarSeptember = new SimpleCalendar('September 2022');
        $simpleCalendarSeptember->setWeekOffset(Carbon::TUESDAY);

        $simpleCalendarNovember = new SimpleCalendar('November 2022');
        $simpleCalendarNovember->setWeekOffset(Carbon::SATURDAY);

        $simpleCalendarOctober = new SimpleCalendar('October 2022');
        $simpleCalendarOctober->setWeekOffset(Carbon::THURSDAY);

        $septemberResult = $weekdayIndex->invoke($simpleCalendarSeptember);
        $novemberResult  = $weekdayIndex->invoke($simpleCalendarNovember);
        $octoberResult   = $weekdayIndex->invoke($simpleCalendarOctober);

        $this->assertEquals(2, $septemberResult);
        $this->assertEquals(3, $novemberResult);
        $this->assertEquals(2, $octoberResult);
    }

    /**
     * @test
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::render()
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::weekdayIndex()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::rotate()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::addEvent()
     */
    public function renders_all_css_classes(): void
    {
        $simpleCalendar = new SimpleCalendar('June 2010', 'June 5 2010');

        $defaults = [
            'simcal',
            'simcal-lead',
            'simcal-trail',
            'simcal-highlight',
            'simcal-event',
            'simcal-events',
        ];

        $simpleCalendar->addEvent('Sample Event', 'June 15 2010');
        $render = $simpleCalendar->render();
        foreach ($defaults as $class) {
            $this->assertNotFalse(str_contains($render, 'class="'.$class.'"'));
        }
    }

    /**
     * @test
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::render()
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::addEvent
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::rotate
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setCssClasses
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::weekdayIndex
     */
    public function renders_custom_css_classes(): void
    {
        $simpleCalendar = new SimpleCalendar('June 2010', 'June 5 2010');
        $classes        = [
            'calendar'     => 'TestCalendar',
            'leading_day'  => 'TestPrefix',
            'trailing_day' => 'TestSuffix',
            'highlight'    => 'TestToday',
            'event'        => 'TestEvent',
            'events'       => 'TestEvents',
        ];

        $simpleCalendar->setCssClasses($classes);
        $simpleCalendar->addEvent('Sample Event', 'June 15 2010');
        $render = $simpleCalendar->render();

        foreach ($classes as $class) {
            $this->assertNotFalse(str_contains($render, 'class="'.$class.'"'));
        }
    }

    /**
     * @test
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight()
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setWeekOffset()
     */
    public function throws_exception_with_negative_offset(): void
    {
        $simpleCalendar = new SimpleCalendar();
        $this->expectException(InvalidArgumentException::class);

        $simpleCalendar->setWeekOffset(-1);
    }

    /**
     * @test
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight()
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setWeekOffset()
     */
    public function throws_exception_with_Carbon_incompatible_string(): void
    {
        $simpleCalendar = new SimpleCalendar();
        $this->expectException(InvalidArgumentException::class);

        $simpleCalendar->setWeekOffset('Juin 2022');
    }

    /**
     * @test
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::render()
     *
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::rotate
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth
     * @covers MarcAndreAppel\SimpleCalendar\SimpleCalendar::weekdayIndex
     */
    public function opening_tags_and_closing_tags_match(): void
    {
        $cal  = new SimpleCalendar('October 2022');
        $html = $cal->render();

        $this->assertSame(substr_count($html, '<tr'), substr_count($html, '</tr'));
        $this->assertSame(substr_count($html, '<td'), substr_count($html, '</td'));
    }

    /**
     * @test
     *
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::render()
     *
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::rotate
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::setWeekOffset
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::weekdayIndex
     */
    public function opening_tags_and_closing_tags_match_with_custom_attributes(): void
    {
        $cal = new SimpleCalendar();
        $cal->setWeekOffset(4);
        $cal->setMonth('January 2017');
        $html = $cal->render();

        $this->assertSame(substr_count($html, '<tr'), substr_count($html, '</tr'));
        $this->assertSame(substr_count($html, '<td'), substr_count($html, '</td'));
    }

    /**
     * @test
     *
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::render()
     *
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct()
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::rotate
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::weekdayIndex
     */
    public function generic_render_result_matches(): void
    {
        $simpleCalendar = new SimpleCalendar("October 2022", false);
        $tableArray     = $this->parseCalendarHtml($simpleCalendar);

        $expected = [
            [
                ['class' => '', 'text' => 'Sunday',],
                ['class' => '', 'text' => 'Monday',],
                ['class' => '', 'text' => 'Tuesday',],
                ['class' => '', 'text' => 'Wednesday',],
                ['class' => '', 'text' => 'Thursday',],
                ['class' => '', 'text' => 'Friday',],
                ['class' => '', 'text' => 'Saturday',],
            ],

            [
                ['class' => 'simcal-lead', 'text' => ' ',],
                ['class' => 'simcal-lead', 'text' => ' ',],
                ['class' => 'simcal-lead', 'text' => ' ',],
                ['class' => 'simcal-lead', 'text' => ' ',],
                ['class' => 'simcal-lead', 'text' => ' ',],
                ['class' => 'simcal-lead', 'text' => ' ',],
                ['class' => '', 'text' => '1', 'date' => '2022-10-01',],
            ],
            [
                ['class' => '', 'text' => '2', 'date' => '2022-10-02',],
                ['class' => '', 'text' => '3', 'date' => '2022-10-03',],
                ['class' => '', 'text' => '4', 'date' => '2022-10-04',],
                ['class' => '', 'text' => '5', 'date' => '2022-10-05',],
                ['class' => '', 'text' => '6', 'date' => '2022-10-06',],
                ['class' => '', 'text' => '7', 'date' => '2022-10-07',],
                ['class' => '', 'text' => '8', 'date' => '2022-10-08',],
            ],
            [
                ['class' => '', 'text' => '9', 'date' => '2022-10-09',],
                ['class' => '', 'text' => '10', 'date' => '2022-10-10',],
                ['class' => '', 'text' => '11', 'date' => '2022-10-11',],
                ['class' => '', 'text' => '12', 'date' => '2022-10-12',],
                ['class' => '', 'text' => '13', 'date' => '2022-10-13',],
                ['class' => '', 'text' => '14', 'date' => '2022-10-14',],
                ['class' => '', 'text' => '15', 'date' => '2022-10-15',],
            ],
            [
                ['class' => '', 'text' => '16', 'date' => '2022-10-16',],
                ['class' => '', 'text' => '17', 'date' => '2022-10-17',],
                ['class' => '', 'text' => '18', 'date' => '2022-10-18',],
                ['class' => '', 'text' => '19', 'date' => '2022-10-19',],
                ['class' => '', 'text' => '20', 'date' => '2022-10-20',],
                ['class' => '', 'text' => '21', 'date' => '2022-10-21',],
                ['class' => '', 'text' => '22', 'date' => '2022-10-22',],
            ],
            [
                ['class' => '', 'text' => '23', 'date' => '2022-10-23',],
                ['class' => '', 'text' => '24', 'date' => '2022-10-24',],
                ['class' => '', 'text' => '25', 'date' => '2022-10-25',],
                ['class' => '', 'text' => '26', 'date' => '2022-10-26',],
                ['class' => '', 'text' => '27', 'date' => '2022-10-27',],
                ['class' => '', 'text' => '28', 'date' => '2022-10-28',],
                ['class' => '', 'text' => '29', 'date' => '2022-10-29',],
            ],
            [
                ['class' => '', 'text' => '30', 'date' => '2022-10-30',],
                ['class' => '', 'text' => '31', 'date' => '2022-10-31',],
                ['class' => 'simcal-trail', 'text' => ' ',],
                ['class' => 'simcal-trail', 'text' => ' ',],
                ['class' => 'simcal-trail', 'text' => ' ',],
                ['class' => 'simcal-trail', 'text' => ' ',],
                ['class' => 'simcal-trail', 'text' => ' ',],
            ],
        ];

        $this->assertSame($expected, $tableArray);
    }

    /**
     * @test
     *
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::render()
     *
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::__construct
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::rotate
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::setHighlight
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::setMonth
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::setWeekOffset
     * @covers  MarcAndreAppel\SimpleCalendar\SimpleCalendar::weekdayIndex
     */
    public function custom_attributes_render_result_matches(): void
    {
        $cal = new SimpleCalendar("October 2022", false);
        $cal->setWeekOffset(Carbon::FRIDAY);
        $tableArray = $this->parseCalendarHtml($cal);

        $expected = [
            [
                ['class' => '', 'text' => 'Friday',],
                ['class' => '', 'text' => 'Saturday',],
                ['class' => '', 'text' => 'Sunday',],
                ['class' => '', 'text' => 'Monday',],
                ['class' => '', 'text' => 'Tuesday',],
                ['class' => '', 'text' => 'Wednesday',],
                ['class' => '', 'text' => 'Thursday',],
            ],
            [
                ['class' => 'simcal-lead', 'text' => ' ',],
                ['class' => '', 'text' => '1', 'date' => '2022-10-01',],
                ['class' => '', 'text' => '2', 'date' => '2022-10-02',],
                ['class' => '', 'text' => '3', 'date' => '2022-10-03',],
                ['class' => '', 'text' => '4', 'date' => '2022-10-04',],
                ['class' => '', 'text' => '5', 'date' => '2022-10-05',],
                ['class' => '', 'text' => '6', 'date' => '2022-10-06',],
            ],
            [
                ['class' => '', 'text' => '7', 'date' => '2022-10-07',],
                ['class' => '', 'text' => '8', 'date' => '2022-10-08',],
                ['class' => '', 'text' => '9', 'date' => '2022-10-09',],
                ['class' => '', 'text' => '10', 'date' => '2022-10-10',],
                ['class' => '', 'text' => '11', 'date' => '2022-10-11',],
                ['class' => '', 'text' => '12', 'date' => '2022-10-12',],
                ['class' => '', 'text' => '13', 'date' => '2022-10-13',],
            ],
            [
                ['class' => '', 'text' => '14', 'date' => '2022-10-14',],
                ['class' => '', 'text' => '15', 'date' => '2022-10-15',],
                ['class' => '', 'text' => '16', 'date' => '2022-10-16',],
                ['class' => '', 'text' => '17', 'date' => '2022-10-17',],
                ['class' => '', 'text' => '18', 'date' => '2022-10-18',],
                ['class' => '', 'text' => '19', 'date' => '2022-10-19',],
                ['class' => '', 'text' => '20', 'date' => '2022-10-20',],
            ],
            [
                ['class' => '', 'text' => '21', 'date' => '2022-10-21',],
                ['class' => '', 'text' => '22', 'date' => '2022-10-22',],
                ['class' => '', 'text' => '23', 'date' => '2022-10-23',],
                ['class' => '', 'text' => '24', 'date' => '2022-10-24',],
                ['class' => '', 'text' => '25', 'date' => '2022-10-25',],
                ['class' => '', 'text' => '26', 'date' => '2022-10-26',],
                ['class' => '', 'text' => '27', 'date' => '2022-10-27',],
            ],
            [
                ['class' => '', 'text' => '28', 'date' => '2022-10-28',],
                ['class' => '', 'text' => '29', 'date' => '2022-10-29',],
                ['class' => '', 'text' => '30', 'date' => '2022-10-30',],
                ['class' => '', 'text' => '31', 'date' => '2022-10-31',],
                ['class' => 'simcal-trail', 'text' => ' ',],
                ['class' => 'simcal-trail', 'text' => ' ',],
                ['class' => 'simcal-trail', 'text' => ' ',],
            ],
        ];

        $this->assertSame($expected, $tableArray);
    }

    /**
     * @param  SimpleCalendar  $simpleCalendar
     *
     * @return array
     */
    private function parseCalendarHtml(SimpleCalendar $simpleCalendar): array
    {
        $html = $simpleCalendar->render();

        $document = new DOMDocument();
        @$document->loadHTML($html);

        $tableRows     = $document->getElementsByTagName('tr');
        $tableArray    = [];
        $tableRowIndex = 0;
        foreach ($tableRows as $tableRow) {
            /**
             * @var $tableRow DOMElement
             */
            $this->assertEquals(7, $tableRow->childNodes->length);

            $rowArray = [];
            foreach ($tableRow->childNodes as $childNode) {
                /**
                 * @var $childNode DOMElement
                 */
                $class   = $childNode->getAttribute("class");
                $rowItem = [
                    'class' => $class,
                    'text'  => $childNode->textContent,
                ];

                if ($tableRowIndex === 0) {
                    $this->assertSame('th', $childNode->tagName);
                } else {
                    $this->assertSame('td', $childNode->tagName);

                    $time = $childNode->getElementsByTagName('time');

                    if ($class === 'simcal-lead' || $class === 'simcal-trail') {
                        $this->assertSame(0, $time->length);
                    } else {
                        $this->assertGreaterThan(0, $time->length);
                        $rowItem['date'] = $time->item(0)->getAttribute('datetime');
                    }
                }

                $rowArray[] = $rowItem;
            }

            $tableArray[] = $rowArray;

            $tableRowIndex++;
        }

        return $tableArray;
    }

    /**
     * @param  string  $methodName
     *
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected static function getMethod(string $methodName): ReflectionMethod
    {
        $class  = new ReflectionClass(SimpleCalendar::class);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    protected static function getAttribute(): array
    {
        return (new ReflectionClass(SimpleCalendar::class))->getAttributes();
    }
}
