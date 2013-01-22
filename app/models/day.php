<?php
class Day extends AppModel {
	
	var $name = 'Day';
	var $hasAndBelongsToMany = array('Artist');
	var $belongsTo = array('Edition');
	
	var $displayField = 'date';
	var $order = 'Day.date ASC';
	
	var $actsAs = array('ExtendAssociations', 'Containable'); 
}