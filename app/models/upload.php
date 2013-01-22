<?php

class Upload extends AppModel {
	
    var $name = 'Upload';   
	
	var $actsAs = array(
    'MeioUpload' => array (
        'name' => array (
            'dir' => 'img/metier',
            'create_directory' => true,
            'allowed_ext' => array('.jpg', '.jpeg', '.png', '.gif', '.pdf', '.JPG', '.PNG', '.GIF', '.PDF', '.JPEG')
        )
    )
	);
	
	var $validate = array(
		'picture' => array(
			'Empty' => array(
				'check' => false
			),
			'InvalidExt' => array(
				'message' => 'Extension non valide.'
			)
		)
	);

}
?>