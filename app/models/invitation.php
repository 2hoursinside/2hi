<?php
class Invitation extends AppModel {
  
	var $name = 'Invitation';
  var $hasMany = array('User');
}
?>