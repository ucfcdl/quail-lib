<?php

class imageMapServerSide extends quailTest {

	var $default_severity = QUAIL_TEST_SEVERE;

	function check() {
		foreach($this->getAllElements('img') as $img) {
			if($img->hasAttribute('ismap'))
				$this->addReport($img);
		}
	
	}
}