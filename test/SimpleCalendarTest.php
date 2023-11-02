<?php

use donatj\SimpleCalendar;
use PHPUnit\Framework\TestCase;

class SimpleCalendarTest extends TestCase {

	public function testCurrentMonth() : void {
		$cal = new SimpleCalendar;

		$this->assertNotFalse(strpos($cal->show(false), 'class="today"'));
	}

	public function testBadDailyHtmlDates() : void {
		try {
			$cal = new SimpleCalendar('June 2010', 'June 5 2010');
			$cal->addDailyHtml('foo', 'tomorrow', 'yesterday');
		} catch( InvalidArgumentException $ex ) {
			$this->assertTrue(true);

			return;
		}

		$this->fail('expected InvalidArgumentException');
	}

	public function testClasses() : void {
		$cal      = new SimpleCalendar('June 2010', 'June 5 2010');
		$defaults = [
			'SimpleCalendar',
			'SCprefix',
			'SCsuffix',
			'today',
			'event',
			'events',
		];

		$cal->addDailyHtml('Sample Event', 'June 15 2010');
		$cal_html = $cal->render();
		foreach( $defaults as $class ) {
			$this->assertNotFalse(strpos($cal_html, 'class="' . $class . '"'));
		}
	}

	public function testCustomClasses() : void {
		$cal     = new SimpleCalendar('June 2010', 'June 5 2010');
		$classes = [
			'calendar'     => 'TestCalendar',
			'leading_day'  => 'TestPrefix',
			'trailing_day' => 'TestSuffix',
			'today'        => 'TestToday',
			'event'        => 'TestEvent',
			'events'       => 'TestEvents',
		];

		$cal->setCalendarClasses($classes);
		$cal->addDailyHtml('Sample Event', 'June 15 2010');
		$cal_html = $cal->render();

		foreach( $classes as $class ) {
			$this->assertNotFalse(strpos($cal_html, 'class="' . $class . '"'));
		}
	}

	public function testCurrentMonth_yearRegression() : void {
		$cal = new SimpleCalendar(sprintf('%d-%d-%d', (date('Y') - 1), date('n'), date('d')));
		$this->assertFalse(strpos($cal->show(false), 'class="today"'));
	}

	public function testTagOpenCloseMismatch_regression() : void {
		$cal = new SimpleCalendar;
		$cal->setStartOfWeek(4);
		$cal->setDate('September 2016');
		$html = $cal->show(false);

		$this->assertSame(substr_count($html, '<tr'), substr_count($html, '</tr'));
		$this->assertSame(substr_count($html, '<td'), substr_count($html, '</td'));
	}

	public function testTagOpenCloseMismatch_regression2() : void {
		$cal = new SimpleCalendar;
		$cal->setDate('January 2017');
		$html = $cal->show(false);

		$this->assertSame(substr_count($html, '<tr'), substr_count($html, '</tr'));
		$this->assertSame(substr_count($html, '<td'), substr_count($html, '</td'));
	}

	public function testGenericGeneration() : void {
		$cal = new SimpleCalendar("June 5 2016");

		$tableArray = $this->parseCalendarHtml($cal);

		$expected = [
			[
				[ 'class' => '', 'text' => 'Sun', ],
				[ 'class' => '', 'text' => 'Mon', ],
				[ 'class' => '', 'text' => 'Tue', ],
				[ 'class' => '', 'text' => 'Wed', ],
				[ 'class' => '', 'text' => 'Thu', ],
				[ 'class' => '', 'text' => 'Fri', ],
				[ 'class' => '', 'text' => 'Sat', ],
			],

			[
				[ 'class' => 'SCprefix', 'text' => ' ', ],
				[ 'class' => 'SCprefix', 'text' => ' ', ],
				[ 'class' => 'SCprefix', 'text' => ' ', ],
				[ 'class' => '', 'text' => '1', 'date' => '2016-06-01', ],
				[ 'class' => '', 'text' => '2', 'date' => '2016-06-02', ],
				[ 'class' => '', 'text' => '3', 'date' => '2016-06-03', ],
				[ 'class' => '', 'text' => '4', 'date' => '2016-06-04', ],
			],

			[
				[ 'class' => '', 'text' => '5', 'date' => '2016-06-05', ],
				[ 'class' => '', 'text' => '6', 'date' => '2016-06-06', ],
				[ 'class' => '', 'text' => '7', 'date' => '2016-06-07', ],
				[ 'class' => '', 'text' => '8', 'date' => '2016-06-08', ],
				[ 'class' => '', 'text' => '9', 'date' => '2016-06-09', ],
				[ 'class' => '', 'text' => '10', 'date' => '2016-06-10', ],
				[ 'class' => '', 'text' => '11', 'date' => '2016-06-11', ],
			],

			[
				[ 'class' => '', 'text' => '12', 'date' => '2016-06-12', ],
				[ 'class' => '', 'text' => '13', 'date' => '2016-06-13', ],
				[ 'class' => '', 'text' => '14', 'date' => '2016-06-14', ],
				[ 'class' => '', 'text' => '15', 'date' => '2016-06-15', ],
				[ 'class' => '', 'text' => '16', 'date' => '2016-06-16', ],
				[ 'class' => '', 'text' => '17', 'date' => '2016-06-17', ],
				[ 'class' => '', 'text' => '18', 'date' => '2016-06-18', ],
			],

			[
				[ 'class' => '', 'text' => '19', 'date' => '2016-06-19', ],
				[ 'class' => '', 'text' => '20', 'date' => '2016-06-20', ],
				[ 'class' => '', 'text' => '21', 'date' => '2016-06-21', ],
				[ 'class' => '', 'text' => '22', 'date' => '2016-06-22', ],
				[ 'class' => '', 'text' => '23', 'date' => '2016-06-23', ],
				[ 'class' => '', 'text' => '24', 'date' => '2016-06-24', ],
				[ 'class' => '', 'text' => '25', 'date' => '2016-06-25', ],
			],

			[
				[ 'class' => '', 'text' => '26', 'date' => '2016-06-26', ],
				[ 'class' => '', 'text' => '27', 'date' => '2016-06-27', ],
				[ 'class' => '', 'text' => '28', 'date' => '2016-06-28', ],
				[ 'class' => '', 'text' => '29', 'date' => '2016-06-29', ],
				[ 'class' => '', 'text' => '30', 'date' => '2016-06-30', ],
				[ 'class' => 'SCsuffix', 'text' => ' ', ],
				[ 'class' => 'SCsuffix', 'text' => ' ', ],
			],
		];

		$this->assertSame($expected, $tableArray);
	}

	public function testGenericGeneration_mTs() : void {
		$cal = new SimpleCalendar("June 5 2016");
		$cal->setStartOfWeek(5);

		$tableArray = $this->parseCalendarHtml($cal);

		$expected = [
			[
				[ 'class' => '', 'text' => 'Fri', ],
				[ 'class' => '', 'text' => 'Sat', ],
				[ 'class' => '', 'text' => 'Sun', ],
				[ 'class' => '', 'text' => 'Mon', ],
				[ 'class' => '', 'text' => 'Tue', ],
				[ 'class' => '', 'text' => 'Wed', ],
				[ 'class' => '', 'text' => 'Thu', ],
			],
			[
				[ 'class' => 'SCprefix', 'text' => ' ', ],
				[ 'class' => 'SCprefix', 'text' => ' ', ],
				[ 'class' => 'SCprefix', 'text' => ' ', ],
				[ 'class' => 'SCprefix', 'text' => ' ', ],
				[ 'class' => 'SCprefix', 'text' => ' ', ],
				[ 'class' => '', 'text' => '1', 'date' => '2016-06-01', ],
				[ 'class' => '', 'text' => '2', 'date' => '2016-06-02', ],
			],
			[
				[ 'class' => '', 'text' => '3', 'date' => '2016-06-03', ],
				[ 'class' => '', 'text' => '4', 'date' => '2016-06-04', ],
				[ 'class' => '', 'text' => '5', 'date' => '2016-06-05', ],
				[ 'class' => '', 'text' => '6', 'date' => '2016-06-06', ],
				[ 'class' => '', 'text' => '7', 'date' => '2016-06-07', ],
				[ 'class' => '', 'text' => '8', 'date' => '2016-06-08', ],
				[ 'class' => '', 'text' => '9', 'date' => '2016-06-09', ],
			],
			[
				[ 'class' => '', 'text' => '10', 'date' => '2016-06-10', ],
				[ 'class' => '', 'text' => '11', 'date' => '2016-06-11', ],
				[ 'class' => '', 'text' => '12', 'date' => '2016-06-12', ],
				[ 'class' => '', 'text' => '13', 'date' => '2016-06-13', ],
				[ 'class' => '', 'text' => '14', 'date' => '2016-06-14', ],
				[ 'class' => '', 'text' => '15', 'date' => '2016-06-15', ],
				[ 'class' => '', 'text' => '16', 'date' => '2016-06-16', ],
			],
			[
				[ 'class' => '', 'text' => '17', 'date' => '2016-06-17', ],
				[ 'class' => '', 'text' => '18', 'date' => '2016-06-18', ],
				[ 'class' => '', 'text' => '19', 'date' => '2016-06-19', ],
				[ 'class' => '', 'text' => '20', 'date' => '2016-06-20', ],
				[ 'class' => '', 'text' => '21', 'date' => '2016-06-21', ],
				[ 'class' => '', 'text' => '22', 'date' => '2016-06-22', ],
				[ 'class' => '', 'text' => '23', 'date' => '2016-06-23', ],
			],
			[
				[ 'class' => '', 'text' => '24', 'date' => '2016-06-24', ],
				[ 'class' => '', 'text' => '25', 'date' => '2016-06-25', ],
				[ 'class' => '', 'text' => '26', 'date' => '2016-06-26', ],
				[ 'class' => '', 'text' => '27', 'date' => '2016-06-27', ],
				[ 'class' => '', 'text' => '28', 'date' => '2016-06-28', ],
				[ 'class' => '', 'text' => '29', 'date' => '2016-06-29', ],
				[ 'class' => '', 'text' => '30', 'date' => '2016-06-30', ],
			],
		];

		$this->assertSame($expected, $tableArray);
	}

	/**
	 * @return array<int, array<int, array<string, string>>>
	 */
	private function parseCalendarHtml( SimpleCalendar $cal ) : array {
		$x = new DOMDocument;
		@$x->loadHTML($cal->show(false));

		$trs        = $x->getElementsByTagName('tr');
		$tableArray = [];
		$rowi       = 0;
		foreach( $trs as $tr ) {
			/**
			 * @var \DOMElement $tr
			 */
			$this->assertEquals(7, $tr->childNodes->length);

			$rowArray = [];
			foreach( $tr->childNodes as $childNode ) {
				/**
				 * @var \DOMElement $childNode
				 */
				$class   = $childNode->getAttribute("class");
				$rowItem = [
					'class' => $class,
					'text'  => $childNode->textContent,
				];

				if( $rowi == 0 ) {
					$this->assertSame('th', $childNode->tagName);
				} else {
					$this->assertSame('td', $childNode->tagName);

					$time = $childNode->getElementsByTagName('time');

					if( $class === 'SCprefix' || $class === 'SCsuffix' ) {
						$this->assertSame(0, $time->length);
					} else {
						$this->assertGreaterThan(0, $time->length);
						$item = $time->item(0);
						assert($item instanceof DOMElement);
						$rowItem['date'] = $item->getAttribute('datetime');
					}
				}

				$rowArray[] = $rowItem;
			}

			$tableArray[] = $rowArray;

			$rowi++;
		}

		return $tableArray;
	}

}
