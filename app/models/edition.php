<?php
class Edition extends AppModel {
	
	var $name = 'Edition';
	var $belongsTo = array('Festival');
	var $hasMany = array('Day');
  var $hasAndBelongsToMany = array('User', 'Artist');
	
	var $displayField = 'date_start';
	
	var $actsAs = array('ExtendAssociations', 'Containable');
}