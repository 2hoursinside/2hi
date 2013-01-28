<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php // echo $this->Facebook->html(); ?>
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>
    <?php echo $title_for_layout; ?><?php __(' - 3 Jours Dehors'); ?></title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->Html->script(array('jquery-1.7.1.min', 'jquery-ui-1.8.22.custom.min', 'jquery.tipsy', 'general'));
		
    //TODO: surcharger html->css pour qu'il prenne les .less
    // déplacer tous les js en bas de page?
    echo $this->Html->css('jquery-ui-1.8.22.custom');
    echo $scripts_for_layout;
    ?>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCuNJ62uVRP6hBuk3qbWIOxOPoffdNwlfQ&sensor=false"></script>
    <link rel="stylesheet/less" type="text/css" href="<?php echo $this->webroot; ?>css/general.less" />
    <script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-29786869-1']);
			_gaq.push(['_setDomainName', '3joursdehors.fr']);
			_gaq.push(['_trackPageview']);
		
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
    <?php echo $this->Html->script('less-1.2.1.min'); ?>
  </head>
  
  <body>
    <div id="page">
      <div id="header">
        <div id="top"></div>
        <div id="nav">
          <div id="middle">
            <div id="nav_left">
              <ul class="menu">
              	<li><?php echo $this->Html->link('Festivals', '#', array('class' => 'tooltip', 'original-title' => 'Veuillez utiliser la recherche <br />pour le moment.')); ?></li>
                <li><?php echo $this->Html->link('Artistes', '#', array('class' => 'tooltip', 'original-title' => 'Veuillez utiliser la recherche<br />pour le moment.')); ?></li>
                <li><?php echo $this->Html->link('Actualités', 'http://3joursdehors.tumblr.com/', array()); ?></li>
              </ul>
            </div>
            <div id="logo"><?php echo $this->Html->link('', '/', array('escape' => false)); ?></div>
            <div id="nav_right">
            	<?php echo $form->create('Search', array('id' => 'search_form', 'url' => array(
								'plugin' => 'search', 
								'controller' => 'searches',
								'action' => 'index'
							)));
							echo $form->input('q', array('id' => 'search', 'label' => false, 'div' => false, 'value' => 'rechercher...'));
							echo $form->end();
							?>
              <div id="profil">
              <?php 
							if (!empty($user)) {
                if (!empty($facebook_user)) echo $this->Facebook->picture($facebook_user['id'], array('facebook-logo' => 'false', 'size' => 'square', 'width' => '24', 'height' => '24'));
              ?>
                <p>Profil</p><span class="drop_down_arrow">&nbsp;</span>
                <div id="profil_menu">
                  <ul>
                  	<li><?php echo $this->Html->link('Mon profil', '/profil/' . $session->read('Auth.User.login')); ?></li>
                    <li><?php echo $this->Html->link('Mes artistes', '/profil/' . $session->read('Auth.User.login') . '/artists'); ?></li>
                    <li><?php echo $this->Html->link('Mes concerts', '/profil/' . $session->read('Auth.User.login') . '/concerts'); ?></li>
                    <li><?php echo $this->Html->link('Paramètres', '/profil/' . $session->read('Auth.User.login') . '/parametres'); ?></li>
                    <br />
										<?php 
                    if (isset($facebook_user)) {
                      echo '<li>' . $this->Facebook->logout(array('redirect' => '/users/logout')) . '</li>';
                    } else {
                      echo '<li>' . $this->Html->link('Déconnexion', '/users/logout'). '</li>';
                    } ?>
                    <?php if ($user['User']['role'] === 'admin') echo '<li>' . $this->Html->link('Admin', '/admin/users/menu') . '</li>'; ?>
                </div>
              <?php
              } else {
                echo $this->Html->link('Login', '/login');
							}
							?>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div id="content">
      	<?php 
				if ($session->check('Message.auth')) $session->flash('auth'); 
				if ($session->check('Message.flash')) $session->flash();
        echo $content_for_layout; 
				?>
        <div class="spacer"></div>
        <br /><br /><br /><br />
      </div>
      
      <div id="footer">
        <?php
        debug($facebook_user);
        debug($user);
         echo $this->element('sql_dump');
        ?>
      </div>
    </div>
    <?php echo $facebook->init(); 
     ?>
     <script type="text/javascript">
			var uvOptions = {};
			(function() {
				var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
				uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/ovXpBfNrprMdTvOlbcAV9A.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
			})();
		</script>
  </body>
</html>