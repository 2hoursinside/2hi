<?php
class City extends AppModel {
	
	var $name = 'City';
	var $hasMany = array('Festival', 'User');

}
?>