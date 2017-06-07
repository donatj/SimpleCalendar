<?php


class SimpleCalendarTest extends PHPUnit_Framework_TestCase {

	public function testCurrentMonth() {
		$cal = new \donatj\SimpleCalendar();
		$this->assertContains('class="today"', $cal->show(false));
	}

	public function testCurrentMonth_yearRegression() {
		$cal = new \donatj\SimpleCalendar(sprintf('%d-%d-%d', (date('Y') - 1), date('n'), date('d')));
		$this->assertNotContains('class="today"', $cal->show(false));
	}

	public function testTagOpenCloseMismatch_regression() {
		$cal = new \donatj\SimpleCalendar();
		$cal->setStartOfWeek(4);
		$cal->setDate('September 2016');
		$html = $cal->show(false);

		$this->assertSame(substr_count($html, '<tr'), substr_count($html, '</tr'));
		$this->assertSame(substr_count($html, '<td'), substr_count($html, '</td'));
	}

	public function testTagOpenCloseMismatch_regression2() {
		$cal = new \donatj\SimpleCalendar();
		$cal->setDate('January 2017');
		$html = $cal->show(false);

		$this->assertSame(substr_count($html, '<tr'), substr_count($html, '</tr'));
		$this->assertSame(substr_count($html, '<td'), substr_count($html, '</td'));
	}

	public function testGenericGeneration() {
		$cal = new \donatj\SimpleCalendar("June 5 2016");

		$tableArray = $this->parseCalendarHtml($cal);

		$expected = array(
			array(
				array( 'class' => '', 'text' => 'Sun', ),
				array( 'class' => '', 'text' => 'Mon', ),
				array( 'class' => '', 'text' => 'Tue', ),
				array( 'class' => '', 'text' => 'Wed', ),
				array( 'class' => '', 'text' => 'Thu', ),
				array( 'class' => '', 'text' => 'Fri', ),
				array( 'class' => '', 'text' => 'Sat', ),
			),

			array(
				array( 'class' => 'SCprefix', 'text' => ' ', ),
				array( 'class' => 'SCprefix', 'text' => ' ', ),
				array( 'class' => 'SCprefix', 'text' => ' ', ),
				array( 'class' => '', 'text' => '1', 'date' => '2016-06-01', ),
				array( 'class' => '', 'text' => '2', 'date' => '2016-06-02', ),
				array( 'class' => '', 'text' => '3', 'date' => '2016-06-03', ),
				array( 'class' => '', 'text' => '4', 'date' => '2016-06-04', ),
			),

			array(
				array( 'class' => '', 'text' => '5', 'date' => '2016-06-05', ),
				array( 'class' => '', 'text' => '6', 'date' => '2016-06-06', ),
				array( 'class' => '', 'text' => '7', 'date' => '2016-06-07', ),
				array( 'class' => '', 'text' => '8', 'date' => '2016-06-08', ),
				array( 'class' => '', 'text' => '9', 'date' => '2016-06-09', ),
				array( 'class' => '', 'text' => '10', 'date' => '2016-06-10', ),
				array( 'class' => '', 'text' => '11', 'date' => '2016-06-11', ),
			),

			array(
				array( 'class' => '', 'text' => '12', 'date' => '2016-06-12', ),
				array( 'class' => '', 'text' => '13', 'date' => '2016-06-13', ),
				array( 'class' => '', 'text' => '14', 'date' => '2016-06-14', ),
				array( 'class' => '', 'text' => '15', 'date' => '2016-06-15', ),
				array( 'class' => '', 'text' => '16', 'date' => '2016-06-16', ),
				array( 'class' => '', 'text' => '17', 'date' => '2016-06-17', ),
				array( 'class' => '', 'text' => '18', 'date' => '2016-06-18', ),
			),

			array(
				array( 'class' => '', 'text' => '19', 'date' => '2016-06-19', ),
				array( 'class' => '', 'text' => '20', 'date' => '2016-06-20', ),
				array( 'class' => '', 'text' => '21', 'date' => '2016-06-21', ),
				array( 'class' => '', 'text' => '22', 'date' => '2016-06-22', ),
				array( 'class' => '', 'text' => '23', 'date' => '2016-06-23', ),
				array( 'class' => '', 'text' => '24', 'date' => '2016-06-24', ),
				array( 'class' => '', 'text' => '25', 'date' => '2016-06-25', ),
			),

			array(
				array( 'class' => '', 'text' => '26', 'date' => '2016-06-26', ),
				array( 'class' => '', 'text' => '27', 'date' => '2016-06-27', ),
				array( 'class' => '', 'text' => '28', 'date' => '2016-06-28', ),
				array( 'class' => '', 'text' => '29', 'date' => '2016-06-29', ),
				array( 'class' => '', 'text' => '30', 'date' => '2016-06-30', ),
				array( 'class' => 'SCsuffix', 'text' => ' ', ),
				array( 'class' => 'SCsuffix', 'text' => ' ', ),
			),
		);

		$this->assertSame($expected, $tableArray);
	}


	public function testGenericGeneration_mTs() {
		$cal = new \donatj\SimpleCalendar("June 5 2016");
		$cal->setStartOfWeek(5);

		$tableArray = $this->parseCalendarHtml($cal);

		$expected = array(
			array(
				array( 'class' => '', 'text' => 'Fri', ),
				array( 'class' => '', 'text' => 'Sat', ),
				array( 'class' => '', 'text' => 'Sun', ),
				array( 'class' => '', 'text' => 'Mon', ),
				array( 'class' => '', 'text' => 'Tue', ),
				array( 'class' => '', 'text' => 'Wed', ),
				array( 'class' => '', 'text' => 'Thu', ),
			),
			array(
				array( 'class' => 'SCprefix', 'text' => ' ', ),
				array( 'class' => 'SCprefix', 'text' => ' ', ),
				array( 'class' => 'SCprefix', 'text' => ' ', ),
				array( 'class' => 'SCprefix', 'text' => ' ', ),
				array( 'class' => 'SCprefix', 'text' => ' ', ),
				array( 'class' => '', 'text' => '1', 'date' => '2016-06-01', ),
				array( 'class' => '', 'text' => '2', 'date' => '2016-06-02', ),
			),
			array(
				array( 'class' => '', 'text' => '3', 'date' => '2016-06-03', ),
				array( 'class' => '', 'text' => '4', 'date' => '2016-06-04', ),
				array( 'class' => '', 'text' => '5', 'date' => '2016-06-05', ),
				array( 'class' => '', 'text' => '6', 'date' => '2016-06-06', ),
				array( 'class' => '', 'text' => '7', 'date' => '2016-06-07', ),
				array( 'class' => '', 'text' => '8', 'date' => '2016-06-08', ),
				array( 'class' => '', 'text' => '9', 'date' => '2016-06-09', ),
			),
			array(
				array( 'class' => '', 'text' => '10', 'date' => '2016-06-10', ),
				array( 'class' => '', 'text' => '11', 'date' => '2016-06-11', ),
				array( 'class' => '', 'text' => '12', 'date' => '2016-06-12', ),
				array( 'class' => '', 'text' => '13', 'date' => '2016-06-13', ),
				array( 'class' => '', 'text' => '14', 'date' => '2016-06-14', ),
				array( 'class' => '', 'text' => '15', 'date' => '2016-06-15', ),
				array( 'class' => '', 'text' => '16', 'date' => '2016-06-16', ),
			),
			array(
				array( 'class' => '', 'text' => '17', 'date' => '2016-06-17', ),
				array( 'class' => '', 'text' => '18', 'date' => '2016-06-18', ),
				array( 'class' => '', 'text' => '19', 'date' => '2016-06-19', ),
				array( 'class' => '', 'text' => '20', 'date' => '2016-06-20', ),
				array( 'class' => '', 'text' => '21', 'date' => '2016-06-21', ),
				array( 'class' => '', 'text' => '22', 'date' => '2016-06-22', ),
				array( 'class' => '', 'text' => '23', 'date' => '2016-06-23', ),
			),
			array(
				array( 'class' => '', 'text' => '24', 'date' => '2016-06-24', ),
				array( 'class' => '', 'text' => '25', 'date' => '2016-06-25', ),
				array( 'class' => '', 'text' => '26', 'date' => '2016-06-26', ),
				array( 'class' => '', 'text' => '27', 'date' => '2016-06-27', ),
				array( 'class' => '', 'text' => '28', 'date' => '2016-06-28', ),
				array( 'class' => '', 'text' => '29', 'date' => '2016-06-29', ),
				array( 'class' => '', 'text' => '30', 'date' => '2016-06-30', ),
			),
		);

		$this->assertSame($expected, $tableArray);
	}

	/**
	 * @param \donatj\SimpleCalendar $cal
	 * @return array
	 */
	private function parseCalendarHtml( \donatj\SimpleCalendar $cal ) {
		$x = new DOMDocument();
		@$x->loadHTML($cal->show(false));

		$trs        = $x->getElementsByTagName('tr');
		$tableArray = array();
		$rowi       = 0;
		foreach( $trs as $tr ) {
			/**
			 * @var $tr \DOMElement
			 */
			$this->assertEquals(7, $tr->childNodes->length);

			$rowArray = array();
			foreach( $tr->childNodes as $childNode ) {
				/**
				 * @var $childNode \DOMElement
				 */

				$class   = $childNode->getAttribute("class");
				$rowItem = array(
					'class' => $class,
					'text'  => $childNode->textContent,
				);

				if( $rowi == 0 ) {
					$this->assertEquals($childNode->tagName, 'th');
				} else {
					$this->assertEquals($childNode->tagName, 'td');

					$time = $childNode->getElementsByTagName('time');

					if( $class == 'SCprefix' || $class == 'SCsuffix' ) {
						$this->assertEquals(0, $time->length);
					} else {
						$this->assertGreaterThan(0, $time->length);
						$rowItem['date'] = $time->item(0)->getAttribute('datetime');
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
