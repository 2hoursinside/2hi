<?php 
	$this->set('title_for_layout', $artist['Artist']['name']); 
	echo $this->Html->script('jquery.expander.min', false); 
	echo $this->Html->script('artist', false); 
	
	echo $this->Html->scriptBlock("
	$(document).ready(function() {
		$('.button_likes a').click( function () {
			$.post(
				'" . $this->webroot . "users/editArtist', 
				{ id: $(this).attr('id'), rel: $(this).attr('rel'), name: $(this).attr('name') },
				function(result) { 
					if (result) {
						result = JSON.parse(result);
						if (result.alert) {
							alert(result.alert);
						} else {
							if (result.rel === 'add-fav' || result.rel === 'rm-fav')
								var id = '#fav' + result.id
							else if (result.rel === 'add-artist' || result.rel === 'rm-artist')
								var id = '#add' + result.id
							
							var name = result.rel + result.name;
							$(id).removeClass(result.classToRemove).addClass(result.classToAdd);
							$(id).attr('rel', result.rel);
							
							if (result.eltToHide) {
								var eltToHide = '#' + result.eltToHide;
								$(eltToHide).hide();
							}
							if (result.eltToShow) {
								var eltToShow = '#' + result.eltToShow;
								$(eltToShow).show();
								$(eltToShow).removeClass('like').addClass('delete');
								$(eltToShow).attr('rel', 'rm-artist');
							}
						}
					} else {
						alert('Oops, problème de sauvegarde');
					}
				}
			);
		});
	});
	", array('inline' => false));
?> 

<div class="photo-c">
  <?php echo $this->Html->image('artist/covers/default.jpg'); ?>
</div>
        
	<div id="col" class="artist">
  	<ul class="button_likes">
  		<li>
				<?php 
				$relationship = artistRelationship($artist['Artist']['id'], $user['Artist']);
				if ($relationship) { 
					if ($relationship === 'favorite')
						echo $this->Html->link('Suivre', '#', array('style' => 'display:none', 'onclick' => 'return false;', 'name' => $artist['Artist']['id'], 'rel' => 'rm-artist', 'id' => 'add'. $artist['Artist']['id'], 'class' => 'button delete')); 
					else 
						echo $this->Html->link('Suivre', '#', array('onclick' => 'return false;', 'name' => $artist['Artist']['id'], 'rel' => 'rm-artist', 'id' => 'add'. $artist['Artist']['id'], 'class' => 'button delete')); 
				} else {
					echo $this->Html->link('Suivre', '#', array('onclick' => 'return false;', 'name' => $artist['Artist']['id'], 'rel' => 'add-artist', 'id' => 'add'. $artist['Artist']['id'], 'class' => 'button like')); 
				}
				?>
      </li>
      <li>
				<?php 
				if ($relationship === 'favorite') 
					echo $this->Html->link('Favori', '#', array('onclick' => 'return false;', 'name' => $artist['Artist']['id'], 'rel' => 'rm-fav', 'id' => 'fav'. $artist['Artist']['id'], 'class' => 'button delete')); 
				else 
					echo $this->Html->link('Favori', '#', array('onclick' => 'return false;', 'name' => $artist['Artist']['id'], 'rel' => 'add-fav', 'id' => 'fav'. $artist['Artist']['id'], 'class' => 'button star')); 
				?>
      </li>
    </ul>
    
  	<h1><?php echo $artist['Artist']['name']; ?></h1>
        
		<?php if(!empty($artist['Country']['locale'])) echo $this->Html->image('flags/' . $artist['Country']['locale'] . '.png', array('id' => 'cur_fest_flag', 'class' => 'absmiddle')); ?>
			<?php
      if (!empty($artist['Genre'])) {
      	echo '<ul class="tags">';
				foreach($artist['Genre'] as $j => $genre) {
					if (is_object($genre)) $genre = get_object_vars($genre);
					echo '<li><span class="tag_left">&nbsp;</span><span class="tag">' . $genre['name'] . '</span><span class="tag_right">&nbsp;</span></li>';
					if ($j > 3) break;
				}
				echo '</ul>';
			} else {
				echo '<div class="spacer"></div>';
			}
      ?>
    
    <?php if (!empty($artist['Artist']['bio'])) {
			echo '<div class="bio expandable">' . $artist['Artist']['bio'] . '</div>';
		}    
    ?>
   	
    <br /><br />
    
    
    <?php if (!empty($similar_artists)) {
    	echo '<h2>Artistes similaires</h2><ul>';
			foreach ($similar_artists as $artist) {
				$relationship = artistRelationship($artist['Artist']['id'], $user['Artist']);
																														 
				echo '<li class="artist-similar">';
				if (!empty($artist['Artist']['fb_picture']))
					echo $this->Html->link($this->Html->image($artist['Artist']['fb_picture'], array('class' => 'ppic')), '/artist/' . $artist['Artist']['url'], array('escape' => false));
				else 
					echo $this->Html->link($this->Html->image('artist/profilepics/default.jpg', array('class' => 'ppic')), '/artist/' . $artist['Artist']['url'], array('escape' => false));
					
				echo '<span class="name">' . $this->Html->link($artist['Artist']['name'], '/artist/' . $artist['Artist']['url']) . '</span>';
				
				echo '<br />';
					if ($relationship === 'follow') { echo '<span class="relation follow">Ajouté.</span>'; }
					elseif ($relationship === 'favorite') { echo '<span class="relation favorite">Favori.</span>'; }
        echo '</span></li>';
			}
			echo '</ul><div class="spacer"></div><br />';
		}
    ?>

    <h2>Festivals</h2>
        
    <div class="tabs">
      <ul>
        <li><a href="#festivals-a-venir">A venir (<?php echo count($coming_editions); ?>)</a></li>
        <li><a href="#festivals-precedents">Précédents (<?php echo count($past_editions); ?>)</a></li>
      </ul>
      <div id="festivals-a-venir">
      	<?php if (!empty($coming_editions)) {
					foreach($coming_editions as $edition) {
						
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
							
							if ($edition['Festival']['capacity'] != 0)
								echo '<span class="capacity">' . number_format($edition['Festival']['capacity'], 0, ',', ' ') . ' places';
							
							if ($edition['price'] != 0)
								echo '<span class="price">' . $edition['price'] . ' euros';
							
							
						echo '</div>';
					}
				} else {
					echo 'Aucun pour le moment.';
				}
				?>
      </div>
      <div id="festivals-precedents">
      	<?php if (!empty($past_editions)) {
				foreach($past_editions as $edition) {
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
						
						if ($edition['Festival']['capacity'] != 0)
							echo '<span class="capacity">' . number_format($edition['Festival']['capacity'], 0, ',', ' ') . ' places';
						
						if ($edition['price'] != 0)
							echo '<span class="price">' . $edition['price'] . ' euros';
						
						
					echo '</div>';
				}
			} else {
				echo 'Aucun pour le moment.';
			}
			?> 
      </div>
   </div>
    
    <div class="spacer"></div><br /><br />
    
    <?php 
		//debug($past_editions); ?>
    <!--
    <?php 
		$days = array_reverse($artist['Day']);
		$separation = false;
		
		if (!empty($days)) { 
		foreach ($days as $i => $day) {
			$timestamp = strtotime($day['date']);
			
			if($timestamp < time() and !$separation) {
				if ($i == 0) { echo '<div class="festivals-artists">Aucun pour le moment.</div>'; }
				echo '<div class="spacer"></div><br /><br /><h2 class="title">Festivals passés</h2>';
				$separation = true;
			}
			
			echo '<div class="festivals-artists">';
			echo $this->Html->image('flags/' . $day['Edition']['Festival']['Country']['locale'] . '.png', array('class' => 'absmiddle')); ?>
            <h3><?php echo $this->Html->link($day['Edition']['Festival']['name'], '/festival/' . $day['Edition']['Festival']['url'], array('class' => 'decoblue')); ?></h3> <br />
            <?php echo $this->Html->image('festival/thumb.festival.' . $day['Edition']['Festival']['photo_c'], array('class' => 'festival-imgc')); ?>
             
             <span class="infos-festival">
             	<p class="infos-date">
				<?php	
					e(strftime("%d %B %Y", $timestamp));
				?>
                </p>
                <p class="infos-country"><?php echo $day['Edition']['Festival']['Country']['name']; 
				if (!empty($day['Edition']['Festival']['Region']['name'])) echo ', ' . $day['Edition']['Festival']['Region']['name']; ?>
                </p>
                <p class="infos-places"><?php echo $day['Edition']['Festival']['places']; ?> places</p>
                <p class="infos-price"><?php echo $day['Edition']['Festival']['price']; ?> euros</p>
             </span>
             <div class="spacer"></div>
			  <?php
              foreach($day['Edition']['Festival']['Genre'] as $j => $genre) {
				  	echo $this->Html->link($genre['name'], '/festivals/genre/' . $genre['url'], array('class' => 'tag'));
              }
              ?>
              </div> 
              
           
       	<?php
		}
		} else {
			echo '<div class="festivals-artists">Aucun pour le moment.</div>';	
		}?>
    -->
	</div>
    
  <div id="sidebar" class="artist">
 		<div id="artist-video" class="sidebar_block">
  		<h2>musique</h2>
      <?php //if (!empty($videoname); ?>
      <div class="video">
        <object width="270" height="189">
            <param name="movie" value="http://www.youtube.com/v/<?php echo $videourl; ?>"></param>
            <param name="allowFullScreen" value="true"></param>
            <param name="allowscriptaccess" value="always"></param>
            <embed src="http://www.youtube.com/v/<?php echo $videourl; ?>" type="application/x-shockwave-flash" width="270" height="189" allowscriptaccess="always" allowfullscreen="true">
            </embed>
    		</object>
      </div>
      <br /><h3><?php echo $videoname; ?></h3>
  	</div>
    
    <div id="artist-urls" class="sidebar_block">
    	<h2>infos</h2>
      <ul>
      <?php 
			if (!empty($artist_urls->itunes_url))	echo '<li>' . $this->Html->image('icons/itunes.png', array('class' => 'absmiddle')) . $this->Html->link('Acheter sur iTunes',  $artist_urls->itunes_url) . '</li>';
			if (!empty($artist_urls->amazon_url))	echo '<li>' . $this->Html->image('icons/amazon.png', array('class' => 'absmiddle')) . $this->Html->link('Acheter sur Amazon',  $artist_urls->amazon_url) . '</li><br />';
			
			if (!empty($artist_twitter->artist->twitter))	echo '<li>' . $this->Html->image('icons/twitter.png', array('class' => 'absmiddle')) . $this->Html->link('Twitter', 'http://twitter.com/'. $artist_twitter->artist->twitter) . '</li>';
      if (!empty($artist_urls->lastfm_url))	echo '<li>' . $this->Html->image('icons/lastfm.png', array('class' => 'absmiddle')) . $this->Html->link('LastFM',  $artist_urls->lastfm_url) . '</li>';
			if (!empty($artist_urls->wikipedia_url))	echo '<li>' . $this->Html->image('icons/wikipedia.png', array('class' => 'absmiddle')) . $this->Html->link('Wikipedia',  $artist_urls->wikipedia_url) . '</li>';
			if (!empty($artist_urls->myspace_url))	echo '<li>' . $this->Html->image('icons/myspace.png', array('class' => 'absmiddle')) . $this->Html->link('Myspace',  $artist_urls->myspace_url) . '</li>';

			if (!empty($artist_urls->mb_url))	echo '<li>' . $this->Html->image('icons/musicbrainz.png', array('class' => 'absmiddle')) . $this->Html->link('Discographie',  $artist_urls->mb_url) . '</li>';
			?>
      </ul>
    </div>
  </div>