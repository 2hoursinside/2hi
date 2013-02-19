<?php
class Edition extends AppModel {
	
	var $name = 'Edition';
	var $belongsTo = array('Festival');
	var $hasMany = array('Day', 'Liveset');
  var $hasAndBelongsToMany = array('User', 'Artist');
	
	var $displayField = 'date_start';
	var $order = 'date_start DESC';
	var $actsAs = array('ExtendAssociations', 'Containable');
}