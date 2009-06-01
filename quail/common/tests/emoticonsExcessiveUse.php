<?php

class emoticonsExcessiveUse extends quailTest {

	var $default_severity = QUAIL_TEST_SEVERE;

	function check() {
		$emoticons = file(QUAIL_PATH.'/common/resources/emoticons.txt', FILE_IGNORE_NEW_LINES);
		$count = 0;
		foreach($this->getAllElements(null, 'text') as $element) {
			if(strlen($element->nodeValue < 2)) {
				$words = explode(' ', $element->nodeValue);
				foreach($words as $word) {
					if(in_array($word, $emoticons)) {
						$count++;
						if($count > 4) {
							$this->addReport(null, null, false);	
							return false;	
						}
					}
				}
			
			}
		}
	
	}
}