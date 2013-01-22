<?php
class Venue extends AppModel {
	
	var $name = 'Venue';
	var $hasMany = array('Concert', 'Artist');
	var $belongsTo = array('Country', 'Region');

	var $order = 'Venue.name ASC';
}