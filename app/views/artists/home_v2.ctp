 <?php $this->pageTitle = 'Accueil'; ?>
 	
    <?php echo $html->image('title/offres.png'); ?> <br />
    <?php echo $html->image('promo/promo1.png'); echo $html->image('promo/promo2.png'); ?>
    
    <br /><br />
    <?php echo $html->image('title/nuage.png'); ?>
    
    <div id="artists-cloud">
 	<?php
	foreach ($artists as $artist) {
			?>
			<span class="pop<?php echo $artist['Artist']['popularity']; ?>" rel="<?php echo $artist['Artist']['id']; ?>">
			<?php echo $artist['Artist']['name']; ?>
            </span>
			
		<?php 	
	}
	?>
	</div>
    
    <?php foreach($genres as $genre) {
		echo $genre['Genre']['name'] . ' - ';
	}
	?>