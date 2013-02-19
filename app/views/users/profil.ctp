<?php 
	$this->set('title_for_layout', 'Profil de ' . $user['User']['login']);  
	echo $this->Html->script('profil', false); 
?>

<div class="photo-c">
  <?php echo $this->Html->image('user/covers/default_' . $user['User']['gender'] . '.jpg'); ?>
</div>

<div id="col" class="profil">
	<h1>Profil de <?php echo $user['User']['login']; ?></h1>
  <?php 
	if (!empty($user['User']['facebook_id'])) echo $this->Html->image('http://graph.facebook.com/' . $user['User']['facebook_id'] . '/picture', array('class' => 'fbppic')); 
	else echo $this->Html->image('user/profilepics/default.jpg', array('class' => 'fbppic')); 
	?>
  <span class="infos">
  	<?php 
			echo sexe($user['User']['gender']) . ', ' . age($user['User']['birth_date']) . ' ans<br />';
			echo $user['City']['name'];											 
		?>
  </span>
  <ul class="social">
  	<?php
		if ($isMyProfile && empty($user['User']['facebook_id']) && empty($user['User']['twitter']) && empty($user['User']['lastfm']) && empty($user['User']['soundcloud']) && empty($user['User']['hypem'])) {
			echo $this->Html->link('Compléter votre profil', '/profil/' . $user['User']['login'] . '/parametres');
		}
		if (!empty($user['User']['facebook_id'])) echo '<li>' . $this->Html->image('icons/facebook.png', array('class' => 'absmiddle')). $this->Html->link('Facebook', 'http://facebook.com/' . $user['User']['facebook_id']) . '</li>';
		if (!empty($user['User']['twitter'])) echo '<li>' . $this->Html->image('icons/twitter.png', array('class' => 'absmiddle')). $this->Html->link('Twitter', 'http://twitter.com/' . $user['User']['twitter']) . '</li>';
		if (!empty($user['User']['lastfm'])) echo '<li>' . $this->Html->image('icons/lastfm.png', array('class' => 'absmiddle')). $this->Html->link('Lastfm', 'http://lastfm.com/user/' . $user['User']['lastfm']) . '</li>';
		if (!empty($user['User']['soundcloud'])) echo '<li>' . $this->Html->image('icons/soundcloud.png', array('class' => 'absmiddle')). $this->Html->link('Soundcloud', 'http://soundcloud.com/' . $user['User']['soundcloud']) . '</li>';
		if (!empty($user['User']['hypem'])) echo '<li>' . $this->Html->image('icons/hypem.png', array('class' => 'absmiddle')). $this->Html->link('Hypem', 'http://hypem.com/' . $user['User']['hypem']) . '</li>';
		?>
  </ul>
  <div class="spacer"></div><br  /><br  />
  
  
  <?php if ($isMyProfile) { ?>
  <ul class="button_likes">
    <li><?php echo $this->Html->link('Editer', '/profil/' . $user['User']['login'] . '/artists', array('class' => 'button edit')); ?></li>
  </ul>
  <?php } ?>
  <h2 class="left">Artistes favoris</h2>
	<?php
	if (!empty($user['Artist'])) { ?>
		<ul class="artists-list">
      <?php
      foreach($user['Artist'] as $u => $artist) {
				if ($artist['ArtistsUser']['favorite'] == 1) {
					echo '<li><span>';
					if (!empty($artist['fb_picture']))
						echo $this->Html->link($this->Html->image($artist['fb_picture'], array('original-title' => $artist['name'], 'class' => 'ppic tooltip')), '/artist/' . $artist['url'], array('escape' => false));
					else 
						echo $this->Html->link($this->Html->image('artist/profilepics/default.jpg', array('original-title' => $artist['name'], 'class' => 'tooltip')), '/artist/' . $artist['url'], array('escape' => false));
					echo '</span></li>';
				}
			}
      ?>
      </ul>
      <div class="spacer"></div><br />
			<?php
	} else {
		 echo "<div class='spacer'></div><p>Aucun artiste suivi.</p><br />"; 
	}
	?>
  <br />
  
  <?php  // debug($go_editions); ?>
  
  <h2>Festivals</h2>
  <div class="tabs">
      <ul>
        <li><a href="#festivals-a-venir"><?php echo pronoun($isMyProfile, $user['User']['gender'], 'go'); ?> (<?php echo count($go_editions); ?>)</a></li>
        <li><a href="#festivals-interesse"><?php echo pronoun($isMyProfile, $user['User']['gender'], 'want'); ?> (<?php echo count($want_editions); ?>)</a></li>
        <li><a href="#festivals-precedents"><?php echo pronoun($isMyProfile, $user['User']['gender'], 'seen'); ?> (<?php echo count($seen_editions); ?>)</a></li>
      </ul>
      <div id="festivals-a-venir">
      	<?php if (!empty($go_editions)) {
					foreach($go_editions as $edition) {
						$edition = $edition['Edition'];
						echo '<div class="festival-list">';
							echo $this->Html->link($this->Html->image('festival/profilepics/thumb.festival.' . $edition['Festival']['photo_r'], array('class' => 'ppic')), '/festival/' . $edition['Festival']['url'], array('escape' => false));
							if(!empty($edition['Festival']['Country']['locale'])) echo $this->Html->image('flags/' . $edition['Festival']['Country']['locale'] . '.png', array('class' => 'flag'));
							echo '<h3>' . $this->Html->link($edition['Festival']['name'], '/festival/' . $edition['Festival']['url']) . '</span></h3>';
							
							$ts_start = strtotime($edition['date_start']);
							$ts_end = strtotime($edition['date_end']);
							echo '<span class="date"> ';
							if ($ts_start == $ts_end) {
								echo 'le ' . strftime("%d %B %Y", $ts_end);
							} elseif (strftime('%m', $ts_start) == strftime('%m', $ts_end)) {
								echo 'du '. strftime("%d", $ts_start) . ' au ' . strftime("%d %B %Y", $ts_end);
							} else {
								echo 'du ' . strftime("%d %B", $ts_start) . ' au ' . strftime("%d %B %Y", $ts_end);
							}
							echo '</span>';
							
							echo '<span class="lieu">';
							if ($edition['Festival']['Country']['locale'] !== 'fre') {
								 echo $edition['Festival']['Country']['name'];
							} else {
								if (isset($edition['Festival']['Region']['name'])) echo $edition['Festival']['Region']['name'];
								if (isset($edition['Festival']['City']['name'])) echo ', ' . $edition['Festival']['City']['name'] . ' ('. $edition['Festival']['Department']['code'].')';
							}
							echo '</span>';
							
							// lineup
							if (!empty($edition['Artist'])) {
								echo '<div class="artists-lineup">';
									foreach ($edition['Artist'] as $x => $artist) {
										if ($x != 0)
											echo ', ';
										echo $this->Html->link($artist['name'], '/artist/' . $artist['url']);
									}
								if (count($edition['Artist'] > 9)) echo '...';
								echo '</div>';
							
							// places, prix
							} else {
								if ($edition['Festival']['capacity'] != 0)
									echo '<span class="capacity">' . number_format($edition['Festival']['capacity'], 0, ',', ' ') . ' places';
								if ($edition['price'] != 0)
									echo '<span class="price">Prix : ' . $edition['price'];
							}
							
						echo '</div>';
					}
				} else {
					echo 'Aucun pour le moment.';
				}
				?>
      </div>
      <div id="festivals-interesse">
      	<?php if (!empty($want_editions)) {
					foreach($want_editions as $edition) {
						$edition = $edition['Edition'];
						echo '<div class="festival-list">';
							echo $this->Html->link($this->Html->image('festival/profilepics/thumb.festival.' . $edition['Festival']['photo_r'], array('class' => 'ppic')), '/festival/' . $edition['Festival']['url'], array('escape' => false));
							if(!empty($edition['Festival']['Country']['locale'])) echo $this->Html->image('flags/' . $edition['Festival']['Country']['locale'] . '.png', array('class' => 'flag'));
							echo '<h3>' . $this->Html->link($edition['Festival']['name'], '/festival/' . $edition['Festival']['url']) . '</span></h3>';
							
							$ts_start = strtotime($edition['date_start']);
							$ts_end = strtotime($edition['date_end']);
							echo '<span class="date"> ';
							if ($ts_start == $ts_end) {
								echo 'le ' . strftime("%d %B %Y", $ts_end);
							} elseif (strftime('%m', $ts_start) == strftime('%m', $ts_end)) {
								echo 'du '. strftime("%d", $ts_start) . ' au ' . strftime("%d %B %Y", $ts_end);
							} else {
								echo 'du ' . strftime("%d %B", $ts_start) . ' au ' . strftime("%d %B %Y", $ts_end);
							}
							echo '</span>';
							
							echo '<span class="lieu">';
							if ($edition['Festival']['Country']['locale'] !== 'fre') {
								 echo $edition['Festival']['Country']['name'];
							} else {
								if (isset($edition['Festival']['Region']['name'])) echo $edition['Festival']['Region']['name'];
								if (isset($edition['Festival']['City']['name'])) echo ', ' . $edition['Festival']['City']['name'] . ' ('. $edition['Festival']['Department']['code'].')';
							}
							echo '</span>';
							
							// lineup
							if (!empty($edition['Artist'])) {
								echo '<div class="artists-lineup">';
									foreach ($edition['Artist'] as $x => $artist) {
										if ($x != 0)
											echo ', ';
										echo $this->Html->link($artist['name'], '/artist/' . $artist['url']);
									}
								if (count($edition['Artist'] >= 10)) echo '...';
								echo '</div>';
							
							// places, prix
							} else {
								if ($edition['Festival']['capacity'] != 0)
									echo '<span class="capacity">Places : ' . number_format($edition['Festival']['capacity'], 0, ',', ' ');
								if ($edition['price'] != 0)
									echo '<span class="price">Prix : ' . $edition['price'];
							}
							
							
						echo '</div>';
					}
				} else {
					echo 'Aucun pour le moment.';
				}
				?>
      </div>
      <div id="festivals-precedents">
      	<?php if (!empty($seen_editions)) {
				foreach($seen_editions as $edition) {
					$edition = $edition['Edition'];
					echo '<div class="festival-list">';
						echo $this->Html->link($this->Html->image('festival/profilepics/thumb.festival.' . $edition['Festival']['photo_r'], array('class' => 'ppic')), '/festival/' . $edition['Festival']['url'], array('escape' => false));
						if(!empty($edition['Festival']['Country']['locale'])) echo $this->Html->image('flags/' . $edition['Festival']['Country']['locale'] . '.png', array('class' => 'flag'));
						echo '<h3>' . $this->Html->link($edition['Festival']['name'], '/festival/' . $edition['Festival']['url']) . ' <span class="year">' . substr($edition['date_start'], 0, 4) . '</span></h3>';
						
						$ts_start = strtotime($edition['date_start']);
						$ts_end = strtotime($edition['date_end']);
						echo '<span class="date"> ';
						if ($ts_start == $ts_end) {
							echo 'le ' . strftime("%d %B %Y", $ts_end);
						} elseif (strftime('%m', $ts_start) == strftime('%m', $ts_end)) {
							echo 'du '. strftime("%d", $ts_start) . ' au ' . strftime("%d %B", $ts_end);
						} else {
							echo 'du '. strftime("%d %B", $ts_start) . ' au ' . strftime("%d %B", $ts_end);
						}
						echo '</span>';
						
						echo '<span class="lieu">';
						if ($edition['Festival']['Country']['locale'] !== 'fre') {
							 echo $edition['Festival']['Country']['name'];
						} else {
							if (isset($edition['Festival']['Region']['name'])) echo $edition['Festival']['Region']['name'];
							if (isset($edition['Festival']['City']['name'])) echo ', ' . $edition['Festival']['City']['name'] . ' ('. $edition['Festival']['Department']['code'].')';
						}
						echo '</span>';
						
						// lineup
						if (!empty($edition['Artist'])) {
							echo '<div class="artists-lineup">';
								foreach ($edition['Artist'] as $x => $artist) {
									if ($x != 0)
										echo ', ';
									echo $this->Html->link($artist['name'], '/artist/' . $artist['url']);
								}
							if (count($edition['Artist'] >= 10)) echo '...';
							echo '</div>';
						
						// places, prix
						} else {
							if ($edition['Festival']['capacity'] != 0)
								echo '<span class="capacity">Places : ' . number_format($edition['Festival']['capacity'], 0, ',', ' ');
							if ($edition['price'] != 0)
								echo '<span class="price">Prix : ' . $edition['price'];
							}
						
						
					echo '</div>';
				}
			} else {
				echo 'Aucun pour le moment.';
			}
			?> 
      </div>
   </div>
  
  <br /><br />
  
  
  
  <br /><br /><br /><br />
</div>


<div id="sidebar" class="profil">

	<div id="artists-followed" class="sidebar_block">
		<h2>artistes suivis <?php if ($isMyProfile) echo $this->Html->link(' éditer', '/profil/' . $user['User']['login'] . '/artists', array('class' => 'edit')); ?></h2>
  	<?php
    if (!empty($user['Artist'])) { ?>
      <ul class="artists-list">
      <?php
      foreach($user['Artist'] as $artist) {
        echo '<li><span>';
				if (!empty($artist['fb_picture']))
					echo $this->Html->link($this->Html->image($artist['fb_picture'], array('original-title' => $artist['name'], 'class' => 'tooltip')), '/artist/' . $artist['url'], array('escape' => false));
				else 
					echo $this->Html->link($this->Html->image('artist/profilepics/default.jpg', array('original-title' => $artist['name'], 'class' => 'tooltip ppic')), '/artist/' . $artist['url'], array('escape' => false));
      	echo '</span></li>';
			}
      ?>
      </ul>
		<?php 
    } else { 
      echo "<p>Aucun artiste suivi.</p>"; 
    }
    ?>
  </div>
</div>

