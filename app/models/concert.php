<?php
class Concert extends AppModel {
	
	var $name = 'Concert';
	var $belongsTo = array('Venue', 'Artist', 'Edition');

	var $order = 'Concert.date DESC';
}