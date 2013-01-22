<script type="text/javascript"> 
<?php if (isset($type) && $type == 'important') : ?> 
    var settings = { header:"Important :", glue:'before' }; 
<?php elseif (isset($type) && $type == 'error') : ?> 
    var settings = { header:"Erreur :", sticky:true }; 
<?php else : ?> 
    var settings = {}; 
<?php endif ?> 
  jQuery.jGrowl("<?php echo $content_for_layout; ?>", settings); 
</script> 
 
<!-- si vous désirez garder le message d'origine en plus de l'alerte Growl 
<div id="flashMessage" class="message"><?php echo $content_for_layout; ?></div> --> 