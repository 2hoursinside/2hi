<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $this->Html->charset(); ?>
<?php echo $this->Html->meta('icon'); ?>
<meta name="description" content="3 Jours Dehors vous aide à découvrir les festivals qui correspondent à vos goûts musicaux.">
<meta property="og:title" content="3 Jours Dehors - Le festival qu'il vous faut" /><meta property="og:type" content="company" /><meta property="og:site_name" content="3 Jours Dehors" /><meta property="og:url" content="http://3joursdehors.fr" /><meta property="og:image" content="http://3joursdehors.fr/img/3jd.jpg" /><meta property="og:description" content="3 Jours Dehors vous permet de trouver le festival qui correspond le mieux à vos goûts musicaux. Actuellement en beta privée." />
<title>3 Jours Dehors - Recommandation de festivals de musique</title>
<style type="text/css">
	* { margin: 0; padding: 0; border: 0; }
	.site-powered-by, .share-link { display:none; } 
	.invite-label, .site-desc {margin-top:10px;}
	body .lrdiscoverwidget .inviteform .submit { 
		background-image: -webkit-gradient(linear, left top, left bottom, from(#fb5f36), to(#e1512c));
		background-image: -webkit-linear-gradient(top, #fb5f36, #e1512c);
		background-image: -moz-linear-gradient(top, #fb5f36, #e1512c);
		background-image: -ms-linear-gradient(top, #fb5f36, #e1512c);
		background-image: -o-linear-gradient(top, #fb5f36, #e1512c);
		background-image: linear-gradient(top, #fb5f36, #e1512c);
	}
	body .lrdiscoverwidget .inviteform .submit:hover {
		background-color: #eee;     
		color:white;  
		background-image: -webkit-gradient(linear, left top, left bottom, from(#fc7641), to(#e1632c));
		background-image: -webkit-linear-gradient(top, #fc7641, #e1632c);
		background-image: -moz-linear-gradient(top, #fc7641, #e1632c);
		background-image: -ms-linear-gradient(top, #fafafa, #e1632c);
		background-image: -o-linear-gradient(top, #fafafa, #e1632c);
		background-image: linear-gradient(top, #fafafa, #e1632c);
}
	body .lrdiscoverwidget .inviteform .submit:active {
		-moz-box-shadow: 0 0 4px 2px rgba(0,0,0,.3) inset;
		-webkit-box-shadow: 0 0 4px 2px rgba(0,0,0,.3) inset;
		box-shadow: 0 0 4px 2px rgba(0,0,0,.3) inset;
		position: relative;
		top: 1px;
}
	#sign-in { position:absolute; top:15px; right:15px; z-index:999; font-family:Arial, Helvetica, sans-serif; font-size:15px; }
	#sign-in a { color:white; }
</style>
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body>
	
  
  
  <div rel="YJVHZA8H" class="lrdiscoverwidget" data-logo="on" data-background="on" data-share-url="3joursdehors.fr" data-css="">
  	<div id="sign-in">
			<?php echo $this->Html->link('Connexion', '/login'); ?>
    </div>
  </div>
	<script type="text/javascript" src="http://launchrock-ignition.s3.amazonaws.com/ignition.1.1.js"></script>
  
  <?php echo $content_for_layout; ?>

</body>
</html>
