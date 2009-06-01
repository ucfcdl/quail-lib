<?php

class tableHeaderLabelMustBeTerse extends quailTableTest {

	var $default_severity = QUAIL_TEST_MODERATE;

	function check() {
		foreach($this->getAllElements('table') as $table) {
			foreach($table->childNodes as $child) {
				if($child->tagName == 'tr') {
					foreach($child->childNodes as $td) {
						if($td->tagName == 'th') {
							if(strlen($td->getAttribute('abbr')) > 20)
								$this->addReport($td);
						
						}
					}
				}
			}
			
		}
	
	}
}