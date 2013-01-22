<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo $facebook->html(); ?>
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>
    <?php echo $title_for_layout; ?><?php __(' - 3 Jours Dehors'); ?></title>
    <?php
    echo $this->Html->meta('icon');
		echo $this->Html->script(array('jquery-1.7.1.min'));
    echo $scripts_for_layout;
    ?>
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
  </head>
  
  <body>
    <div id="page">
      <div id="header">
        <div id="top"></div>
        <div id="nav">
          <div id="middle">
            <div id="nav_left">
            </div>
            <div id="logo"><?php echo $this->Html->link('', '/', array('escape' => false)); ?></div>
            <div id="nav_right">
              <div id="profil">
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
    echo $this->Html->script('less-1.2.1.min'); ?>
  </body>
</html>