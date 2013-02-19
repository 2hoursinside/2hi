<?php
class Liveset extends AppModel {
	
	var $name = 'Liveset';
	var $belongsTo = array('Edition', 'Artist', 'Festival');

	var $order = 'Liveset.id DESC';
	
}