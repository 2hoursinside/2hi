<?php $this->set('title_for_layout', 'Artistes'); ?>

<div class="photo-c">
  <?php echo $html->image('user/covers/default_' . $user['User']['gender'] . '.jpg'); ?>
</div>

<div id="col" class="artists-index">
	<h1>les artistes</h1><br />
  

  <h2>mainstream shit</h2>
  <ul>
  <?php
 	foreach ($artists_mainstream as $artist) {
 		echo '<li>' . $html->link($artist['Artist']['name'], '/artist/' . $artist['Artist']['url']) . '</li>';
	}
	?>
	</ul>
	<br />
	
	<h2>le retour des superstars</h2>
  <ul>
  <?php
 	foreach ($artists_superstars as $artist) {
 		echo '<li>' . $html->link($artist['Artist']['name'], '/artist/' . $artist['Artist']['url']) . '</li>';
	}
	?>
	</ul>
	<br />
	
	<h2>assez hot en ce moment</h2>
  <ul>
  <?php
 	foreach ($artists_hype as $artist) {
 		echo '<li>' . $html->link($artist['Artist']['name'], '/artist/' . $artist['Artist']['url']) . '</li>';
	}
	?>
	</ul>

</div>


<div id="sidebar">
</div>