<?php
class Country extends AppModel {
	
	var $name = 'Country';
	var $hasMany = array('Region', 'Artist');

	var $order = 'Country.name ASC';
}