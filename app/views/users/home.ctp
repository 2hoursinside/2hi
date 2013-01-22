 <?php $this->set('title_for_layout', 'Accueil'); ?>
 
 	<h1>festivals recommandés</h1>
  <br /><br />
  <h2>Vos artistes</h2>
  
	<?php 
	if(!empty($user['Artist'])) { ?>
    <ul class="artists-list">
		<?php
    foreach($user['Artist'] as $artist) {
      echo '<li>' . $html->link($html->image($artist['fb_picture']), '/artist/' . $artist['url'], array('escape' => false)) . '</li>';
    }
		?>
  	</ul>
    
		<?php 
	} else { 
    echo "<p>Vous n'aimez aucun artiste encore</p>"; 
  }
?>
  
  <!-- 
  <h2>Vos écoutes</h2>
  <ul>
  <?php 
	if(!empty($user_fb_listens)) {
		foreach($user_fb_listens as $listen) {
		
		}
	}?>
  </ul>
  -->
  
  
  <?php 
	// debug($user_fb_listens); 
	// debug($user_fb_bands);
	?>
