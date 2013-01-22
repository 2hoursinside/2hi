<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */
 
 	function random($car) {
		$string = "";
		$chaine = "abcdefghijklmnpqrstuvwxy123456789";
		srand((double)microtime()*1000000);
		for ($i=0; $i < $car; $i++) {
			$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;
	}
	
	function formatUrl($name, $isCsv) {
		if ($isCsv)
			$url = strtr($name,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		else
			$url = htmlentities($name, ENT_NOQUOTES, 'utf-8');
			
		$chars = array('/', '?', '!', ',');
		$url = str_replace($chars, '', $url);
		$url = preg_replace('#\&([A-za-z])(?:acute|cedil|circ|grave|ring|tilde|uml|amp)\;#', '\1', $url);
		$url = preg_replace('#\&([A-za-z]{2})(?:lig)\;#', '\1', $url); // pour les ligatures e.g. '&oelig;'
		$url = str_replace("&amp;", "and", str_replace("'", "-", str_replace(" ", "-", strtolower($url))));
		$url = str_replace('--', '-', $url);
		return $url;
	}
	
	function age($date_naissance) {
    $arr = explode('-', $date_naissance);
    return date('Y', time() - strtotime($arr[2].'-'.$arr[1].'-'.$arr[0])) - 1970;
	}
	
	function sexe($gender) {
		if ($gender === 'male')
			return 'Homme';
		else
			return 'Femme';
	}
	
	function pronoun($isMyProfile, $gender, $type) {
		if ($isMyProfile) {
			if ($type == 'go')
					return "J'y vais";
			elseif ($type == 'want')
				return "Ça m'intéresse";
			elseif ($type == 'seen')
				return "J'y étais";
		} else {
			if ($gender == 'male') {
				if ($type == 'go')
					return "Il y va";
				elseif ($type == 'want')
					return "Ça l'intéresse";
				elseif ($type == 'seen')
					return "Il y était";
			} else {
				if ($type == 'go')
					return "Elle y va";
				elseif ($type == 'want')
					return "Ça l'intéresse";
				elseif ($type == 'seen')
					return "Elle y était";
			}
		}
	}
	
	function artistRelationship($artist_id, $artists) {
		$result = false;
		foreach ($artists as $artist) {
			if ($artist['ArtistsUser']['artist_id'] == $artist_id) {
				if ($artist['ArtistsUser']['favorite'] == 1)
					$result = 'favorite';
				else
					$result = 'follow';
				break;
			}
		}
		return $result;
	}
	
	Configure::write('App.encoding', 'utf-8');
	Configure::write('Search.mode', 'natural');
	
	$languages = array(
		'fr' => 'fre',
		'en' => 'eng'
	);
	 
	// Français par défaut
	$langCode = 'fr';
	$language = 'fre';
	 
	// Analyse de l'URL
	if(!empty($_GET['url']))
	{
		if(strpos($_GET['url'], '/') !== false)
		{
			$langFromUrl = substr($_GET['url'], 0, strpos($_GET['url'], '/'));
		}
		else
		{
			$langFromUrl = $_GET['url'];
		}
	 
		// Code langue accepté ?
		if(isset($languages[$langFromUrl]))
		{
			$langCode = $langFromUrl;
			$language = $languages[$langCode];
	 
			// On enlève le code langue et le slash au début de l'URL
			// avant qu'elle ne soit transmise au Router
			if(strlen($_GET['url']) > strlen($langFromUrl))
			{
				$_GET['url'] = substr($_GET['url'], strlen($langFromUrl));
			}
			else
			{
				$_GET['url'] = '/';
			}
		}
	}
		 
	Configure::write('Config.languages', $languages);
	Configure::write('Config.language',  $language);
	Configure::write('Config.langCode',  $langCode);
	
	
	if(env('REMOTE_ADDR') == '127.0.0.1') {
	 	// Local (Windows dans notre cas)
		$locale = 'french';
	} else {
	  	// En ligne
		$locale = 'fr_FR.utf8';
	}
	// Définition de la locale pour toutes les fonctions php relatives à la de gestion du temps :
	setlocale(LC_TIME, $locale);

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */
