<?php

/**
*	http://checker.atrc.utoronto.ca/servlet/ShowCheck?check=2&lang=eng
*/
class documentTitleDescribesDocument extends quailTest {

	var $default_severity = QUAIL_TEST_SUGGESTION;

	var $cms = false;
	
	function check() {
		$placeholders = file(QUAIL_PATH.'/common/resources/placeholder.txt');		
		$element = $this->dom->getElementsByTagName('title');
		$title = $element->item(0);
		if($title) {
				$this->addReport($title);
		}
	}
}