
	<br /><br />
	<strong><?php echo $message; ?></strong><br /><br />
    
    <?php
	if ($error == 0) {
		
		echo 'URL : ' . $html->link('/img/metier/' . $upload['Upload']['name'], '/img/metier/' . $upload['Upload']['name']);
		echo '<br /><span class="notes">Copiez cette URL dans votre fiche.</span><br /><br />';
		echo $html->image('metier/' . $upload['Upload']['name']);
		
	}
	
?>