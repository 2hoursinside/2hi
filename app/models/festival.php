<?php
class Festival extends AppModel {
	
	var $name = 'Festival';
	var $belongsTo = array('Country', 'Region', 'Department', 'City');
	var $hasAndBelongsToMany = array('Genre');
	var $hasMany = array('Edition', 'Post', 'Liveset');
		
	var $validate = array(
		'name' => array(
			'rule' => array('minLength', 1),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Doit avoir un nom.'
		)
	);
		
	var $actsAs = array(
    'MeioUpload' => array (
        'photo_c' => array (
            'dir' => 'img/festival/covers',
						'default' => 'default.jpg',
            'create_directory' => true,
            'allowed_mime' => array('image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'),
            'allowed_ext' => array('.jpg', '.jpeg', '.png', '.gif'),
						'max_size' => '4 Mb',
						'thumbsizes' => array(
                'festival' => array('width'=> 977, 'height' => 72),
            )
        ),
		'photo_r' => array (
            'dir' => 'img/festival/profilepics',
						'default' => 'default.jpg',
            'create_directory' => true,
            'allowed_mime' => array('image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'),
            'allowed_ext' => array('.jpg', '.jpeg', '.png', '.gif'),
						'max_size' => '4 Mb',
						'thumbsizes' => array(
                'festival' => array('width'=> 100, 'height' => 100),
            )
        ),
		'affiche' => array (
            'dir' => 'img/festival/posters',
						'default' => 'default.jpg',
            'create_directory' => true,
            'allowed_mime' => array('image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'),
            'allowed_ext' => array('.jpg', '.jpeg', '.png', '.gif'),
						'max_size' => '4 Mb',
						'thumbsizes' => array(
                'festival' => array('width'=> 300),
            )
        )
    ), 
	'Containable',
	'Search.Searchable' => array(
		'fields' => array('name')
	),
	'Translate' => array(
		'bio' => 'Biographies'
	), 
	'ExtendAssociations'
	);
	
}