<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
 	//Router::connect('/', array('controller' => 'users', 'action' => 'landing'));
	Router::connect('/', array('controller' => 'users', 'action' => 'home'));
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/inscription', array('controller' => 'users', 'action' => 'register'));
		
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	Router::connect('/artist/*', array('controller' => 'artists', 'action' => 'display'));
	Router::connect('/festival/*', array('controller' => 'festivals', 'action' => 'display'));
	
	Router::connect('/artists', array('controller' => 'artists', 'action' => 'index'));
	Router::connect('/festivals', array('controller' => 'festivals', 'action' => 'index'));
	Router::connect('/actualite', array('controller' => 'posts', 'action' => 'index'));
	
	Router::connect('/profil/:login', array('controller' => 'users', 'action' => 'profil', 'accueil'), array('pass' => 'login'));
	Router::connect('/profil/:login/artists', array('controller' => 'users', 'action' => 'profil', 'artists'), array('pass' => 'login'));
	Router::connect('/profil/:login/concerts', array('controller' => 'users', 'action' => 'profil', 'concerts'), array('pass' => 'login'));
	Router::connect('/profil/:login/parametres', array('controller' => 'users', 'action' => 'profil', 'parametres'), array('pass' => 'login'));
  Router::connect('/profil/:login/recommandations', array('controller' => 'users', 'action' => 'profil', 'recommandations'), array('pass' => 'login'));
	
	Router::connect('/recherche/*',	array('plugin' => 'search', 'controller' => 'searches', 'action' => 'index'));
	
/**
 * Administration
 */	
	Router::connect('/admin', array('controller' => 'submissions', 'action' => 'index', 'prefix' => 'admin' ));
	
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
