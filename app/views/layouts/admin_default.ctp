<?php
/* SVN FILE: $Id: default.ctp 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php __('Festival -'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $html->meta('icon');
		echo $html->css('admin');
		echo $html->css('jquery.jgrowl');
		echo $html->script('jquery-1.7.1.min', true);
		echo $html->script('jquery.form', true);
		echo $html->script('jquery.jgrowl', true);
		echo $scripts_for_layout;
	?>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div id="header">
    	<div id="header-left"></div>
        <div id="header-center">
            <table width="100%">
            <tr>
            	<td><div id="logo"><?php echo $html->link($html->image('3jd_logo.png'), '/', array('escape' => false, 'target' => '_blank')); ?></div></td>
                <td class="path"><h1><?php echo $html->image('icons/logo.png'); ?> Administration <?php echo $html->image('icons/next.png'); ?> <?php echo $title_for_layout; ?></h1>
                	<br />
            		<span class="notes">Connecté en tant que <strong class="green"><?php echo $user['User']['first_name']; ?></strong>. <?php echo $html->link('Déconnexion', '/users/logout'); ?></span></td>
           		</tr>
            </table>
            <div id="path">
            </div>
            <div id="menu">
                <ul>
                    <?php if ($admin) { ?>
                     <li><?php echo $html->link($html->image('menu/accueil.png', array('class' => 'absmiddle')) . ' &nbsp;Festivals', '/admin/festivals', array('escape' => false)); ?></a></li>
                     <li><?php echo $html->link($html->image('menu/pages.png', array('class' => 'absmiddle')) . ' &nbsp;Editions', '/admin/editions', array('escape' => false)); ?></a></li>
                     <li><?php echo $html->link($html->image('icons/rss.png', array('class' => 'absmiddle')) . ' &nbsp;News', '/admin/posts', array('escape' => false)); ?></a></li>
                     <li><?php echo $html->link($html->image('icons/music.png', array('class' => 'absmiddle')) . ' &nbsp;Artistes', '/admin/artists', array('escape' => false)); ?></a></li>
                     <li><?php echo $html->link($html->image('menu/categories.png', array('class' => 'absmiddle')) . ' &nbsp;Genres', '/admin/genres', array('escape' => false)); ?></a></li>
                     <!--<li><?php echo $html->link($html->image('menu/pages.png', array('class' => 'absmiddle')) . ' &nbsp;Régions', '/admin/regions', array('escape' => false)); ?></a></li>-->
                     <li><?php echo $html->link($html->image('menu/pages.png', array('class' => 'absmiddle')) . ' &nbsp;Pays', '/admin/countries', array('escape' => false)); ?></a></li>
                     <li><?php echo $html->link($html->image('menu/users.png', array('class' => 'absmiddle')) . ' &nbsp;Users', '/admin/users', array('escape' => false)); ?></a></li>
                     <li><?php echo $html->link($html->image('menu/categories.png', array('class' => 'absmiddle')) . ' &nbsp;Invit', '/admin/invitations', array('escape' => false)); ?></a></li>
                     <?php } ?>
                    
                </ul>
            </div>
        </div>
        <div id="header-right"></div>
    
    </div>
    
    <div id="page">
        <div id="content">
        
            <?php
						if ($session->check('Message.flash')) {	$session->flash();}
            if ($session->check('Message.auth')) {$session->flash('auth');}
            ?>

            <?php echo $content_for_layout; ?>
            <div class="spacer"> </div>
        </div>
    </div>
    
    <div id="footer"></div>
        
    
    <br /><br />
    <?php echo $this->element('sql_dump'); ?>
    
</body>
</html>

