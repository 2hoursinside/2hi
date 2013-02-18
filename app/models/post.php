<?php
class Post extends AppModel {
	
	var $name = 'Post';
	var $belongsTo = array('Festival');
	var $hasAndBelongsToMany = array('Artist');

	var $order = 'Post.id DESC';
	
	var $actsAs = array('ExtendAssociations');
}