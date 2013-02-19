<?php
class Submission extends AppModel {
	
	var $name = 'Submission';
	var $belongsTo = array('User');

	var $order = 'Submission.id DESC';
}