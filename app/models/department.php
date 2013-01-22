<?php
class Department extends AppModel {
	
	var $name = 'Department';
	var $hasMany = array('Festival');
  var $belongsTo = array('Region');

}
?>