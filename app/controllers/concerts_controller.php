<?php
class ConcertsController extends AppController {
	
	var $name = "Concerts";
 	
	
	function add ($artist_name, $place_name, $date) {
		
		$result = array('artist_name' => $artist_name, 'place_name' => $place_name, 'date' => $date);
		$result = json_encode($result);
		$this->set(compact('result'));
		debug($result);
		
	}
}