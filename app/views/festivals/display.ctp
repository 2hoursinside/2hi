<?php
	echo $this->Html->script('jquery.expander.min', false);
	echo $this->Html->script('jquery.fancybox-1.3.4.pack', false);
	echo $this->Html->script('jquery.mousewheel-3.0.4.pack', false);
	echo $this->Html->script('festival', false);
	echo $this->Html->scriptBlock("
	$(document).ready(function() {
		$('.add-edition').click( function () {
			$.post(
				'" . $this->webroot . "users/addEdition', 
				{ id: $(this).attr('id'), rel: $(this).attr('rel'), name: $(this).attr('name') },
				function(result) { 
					if (result) {
						result = JSON.parse(result);
						var id = '#' + result.id;
						var name = result.rel + result.name;
						$(id).removeClass(result.classToRemove).addClass(result.classToAdd);
						$(id).attr('rel', result.rel);
						$(id).attr('id', name);
						
						if (result.eltToHide) {
							var eltToHide = '#' + result.eltToHide;
							$(eltToHide).hide();
						}
						if (result.eltToShow) {
							var eltToShow = '#' + result.eltToShow;
							$(eltToShow).show();
						}
					} else {
						alert('Oops, problème de sauvegarde');
					}
				}
			);
		});
	});
	", array('inline' => false));

	if (!empty($festival['City']['latitude'])) {
		echo $this->Html->scriptBlock("
		$(document).ready(function() {
															 
			var fest_lat = " . $festival['City']['latitude'] .";
  		var fest_lon = " . $festival['City']['longitude'] .";
			
			var latlng = new google.maps.LatLng(fest_lat, fest_lon);
			
			var options = {
				zoom: 6,
				center: latlng,
				disableDefaultUI: true,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			
			var map = new google.maps.Map($('.map')[0], options);
			
			var marker = new google.maps.Marker({
				position: latlng,
				map: map
			});
		});
		", array('inline' => false));
	}
	
	$this->Html->css("jquery.fancybox-1.3.4", null, array("inline" => false));
	$this->set("title_for_layout", $festival['Festival']['name']);
	if (!empty($festival['Festival']['bio'])) echo $this->Html->meta('description', $festival['Festival']['bio'], array('type' => 'description', 'inline' => false));
?>

<?php 
// load soundcloud library
// TODO: load only when necessary
require_once 'vendors/soundcloud/Services/Soundcloud.php'; 
$client = new Services_Soundcloud('dba5449f0e7f56e0bd028fb2e0ed9f74', '128605c31ae634d911e5267635f42540');
$client->setCurlOptions(array(CURLOPT_FOLLOWLOCATION => 1));
?>

<?php if (empty($festival['Festival']['photo_c'])) { $cover = 'default.jpg'; } else { $cover = $festival['Festival']['photo_c']; } ?>

<div class="photo-c festival" style="background: black url(<?php echo $this->webroot . 'img/festival/covers/' . $cover; ?>) center center no-repeat">
  <div class="wrapper">
  
    <div class="bg-gradient">
      <?php if ($festival['Festival']['photo_r'] != 'default.jpg')  $displayPic = 'left'; else $displayPic = '';
    	
    	if ($displayPic) { ?>
    	<div class="ppic_festival">
    	   <?php echo $this->Html->image('festival/profilepics/thumb.festival.' . $festival['Festival']['photo_r'], array('class' => 'ppic headline')); ?>
      </div>
      <?php } ?>
    	
    	<div class="festival_title">
        <h1><?php echo $festival['Festival']['name']; ?>  <?php echo $this->Html->image('flags/'. $festival['Country']['locale']. '.png', array('class' => 'flag', 'id' => 'cur_fest_flag', 'alt' => $festival['Country']['locale'], 'width' => '24px')); ?></h1>
       
    	</div>
    	
    	<?php
      if (!empty($festival['Genre'])) {
      	echo '<ul class="tags ' . $displayPic . '">';
        foreach($festival['Genre'] as $genre) {
        	echo '<li>' . $genre['name'] . '</li>';
        }
        echo '</ul>';
      }
      ?>
    </div>
  </div>
</div>


<div class="wrapper" class="festival">
  
    <div id="breadcrumb">
      <?php echo $this->Html->link('Festivals', '/festivals'); ?> &raquo;
      <?php echo $this->Html->link($festival['Festival']['name'], '/festival/' . $festival['Festival']['url']); ?>
    </div>
    
    <div id="infos-festival">
      <div class="bio">
        <?php 
        if (!empty($festival['Festival']['bio'])) { 
          echo '<div class="bio expandable">' . $festival['Festival']['bio'] . '</div>';
        } else {
          echo 'Pas de description disponible pour ce festival.';
        } ?>
      </div>
      <ul class="infos-circles">
        <?php 
  			if (isset($festival['Festival']['creation_year']) && $festival['Festival']['creation_year'] != 0) { ?>
        <li class="year">
          <?php echo $festival['Festival']['creation_year']; ?><span>création</span>
        </li>
        <?php 
        }
        if (isset($festival['Festival']['capacity']) && $festival['Festival']['capacity'] != 0) { ?>
        <li class="capacity">
          <?php echo strstr((number_format($festival['Festival']['capacity'])), ',', true); ?>k <span>places</span>
        </li>
        <?php 
        }
        if (isset($editions[0]['Edition']['price']) && $editions[0]['Edition']['price'] != 0) { ?>
        <li class="price tooltip" original-title="Acheter sur le site <br />du festival">
          <?php 
          if (!empty($festival['Festival']['website'])) {
            echo $this->Html->link($editions[0]['Edition']['price'] . '<span>euros</span>', 'http://' . $festival['Festival']['website'], array('escape' => false, 'target' => '_blank', 'rel' => 'nofollow'));
          } else {
            echo $editions[0]['Edition']['price'] . '<span>euros</span>';
          } ?>
        </li>
        <?php 
        }
        if(isset($festival['City']['name'])) { ?>
        <li class="map">
        </li>
  			<?php } ?>
      </ul>
      
.   </div>
  <div class="spacer"></div>
  </div>    
    
  <div id="menu-editions">
    <ul>
    <?php 
    if (!empty($editions)) { 
      foreach ($editions as $key => $edition) {
        $timestamp = strtotime($edition['Edition']['date_end']);
        $anneeedition = strftime("%Y", $timestamp);
        if ($key == 0)
          echo '<li class="active" ><a href="#'. $anneeedition . '">' . $anneeedition . '</a></li>';
        else
          echo '<li><a href="#'. $anneeedition . '">' . $anneeedition . '</a></li>';
      }
    } ?>
    </ul>
  </div>


  <div class="wrapper festival">
    
    <?php    
    //debug($editions);
    
    if (!empty($editions)) { 
    
      // Parse les éditions et les affiche
      $last_key = end(array_keys($editions));
      foreach ($editions as $key => $edition) {
  			
  			if (!empty($edition['User'][0])) {
  				$go = $edition['User'][0]['EditionsUser']['type'] == 1;
  				$want = $edition['User'][0]['EditionsUser']['type'] == 2;
  				$seen = $edition['User'][0]['EditionsUser']['type'] == 3;
  			} else {
  				$go = $want = $seen = false;
  			}
  			
        $timestamp = strtotime($edition['Edition']['date_end']);
        $anneeedition = strftime("%Y", $timestamp);
      ?>
    <div class="edition edition<?php echo $anneeedition; ?>">
      <ul class="button_likes">
      <?php
  			// éditions précédentes
  			if ($timestamp < time()) {
  				if (!$seen) {
  					 echo '<li>' . $this->Html->link('J\'y étais !', '#', array('onclick' => 'return false;', 'name' => $edition['Edition']['id'], 'rel' => 'seen', 'id' => 'seen'. $edition['Edition']['id'], 'class' => 'button save add-edition')) . '</li>';
  				} else {
  					echo $this->Html->link('J\'y étais !', '#', array('onclick' => 'return false;', 'name' => $edition['Edition']['id'], 'rel' => 'noseen', 'id' => 'noseen'. 'noseen'. $edition['Edition']['id'], 'class' => 'button delete add-edition')) . '</li>';
  				}
  			// éditions futures
  			} else {
  					if (!$go) {
  						echo '<li>' . $this->Html->link('J\'y vais !', '#', array('onclick' => 'return false;', 'name' => $edition['Edition']['id'], 'rel' => 'go', 'id' => 'go'. $edition['Edition']['id'], 'class' => 'button next add-edition')) . '</li>';
  					} else {
  						echo '<li>' . $this->Html->link('J\'y vais', '#', array('onclick' => 'return false;', 'name' => $edition['Edition']['id'], 'rel' => 'nogo', 'id' => 'nogo'. $edition['Edition']['id'], 'class' => 'button delete add-edition')) . '</li>';
  					}
  					
  					if (!$want) {
  						echo '<li>' . $this->Html->link('Ça m\'intéresse', '#', array('onclick' => 'return false;', 'name' => $edition['Edition']['id'], 'rel' => 'want', 'id' => 'want'. $edition['Edition']['id'], 'class' => 'button like add-edition')) . '</li>';
  					} else {
  						echo '<li>' . $this->Html->link('Ça m\'intéresse', '#', array('onclick' => 'return false;', 'name' => $edition['Edition']['id'], 'rel' => 'nowant', 'id' => 'nowant'. $edition['Edition']['id'], 'class' => 'button delete add-edition')) . '</li>';
  					}
  			}
  			
  		// Parse les users attachés à cette édition
  		// Récupére uniquement les users qui y vont et/ou qui y sont allés
  		$users_going = array();
  		$users_went = array();
  		if (!empty($edition['User'])) {
    		foreach($edition['User'] as $festivalier) {
      		if ($festivalier['EditionsUser']['type'] == 1) {
        		$users_going[] = $festivalier;
      		} elseif ($festivalier['EditionsUser']['type'] == 3) {
      		  $users_went[] = $festivalier;
      		}
    		}
  		}
      ?>
      </ul>
      <h2>
      <?php
      $ts_start = strtotime($edition['Edition']['date_start']);
  		$ts_end = strtotime($edition['Edition']['date_end']);
  		if ($ts_start == $ts_end) {
  			echo 'le ' . strftime("%d %B %Y", $ts_end);
  		} elseif (strftime('%m', $ts_start) == strftime('%m', $ts_end)) {
  			echo 'du '. strftime("%d", $ts_start) . ' au ' . strftime("%d %B", $ts_end);
  		} else {
  			echo 'du '. strftime("%d %B", $ts_start) . ' au ' . strftime("%d %B", $ts_end);
  		}
  		echo ' ' . $anneeedition; ?>
  		<?php if ($timestamp > time()) echo '<span class="affinity"> (' . round($fest_affinity*100) . '% d\'affinité)</span>'; ?>
  		</h2>
      
      
      <?php // peut afficher par jour ou non
      $canDisplayDays = isset($edition['Day']) && !empty($edition['Day']) && !empty($edition['Day'][0]['Artist']); ?>
        
      <ul class="menu-display">
        <li class="by-popularity <?php if (!$canDisplayDays) echo 'active'; ?>"><a href="#">par popularité</a></li>
        <li class="by-day <?php if ($canDisplayDays) echo 'active'; else echo 'disabled'; ?>"><a href="#">par jour</a></li>
      </ul>
      
      <div class="headliners">
        <?php 
        if (!empty($edition['Artist'])) {
          foreach ($edition['Artist'] as $i => $artist) {
            if ($i > 9) break; 
            
            if (!empty($artist['fb_picture'])) {
              echo $this->Html->link($this->Html->image($artist['fb_picture'], array('original-title' => $artist['name'], 'class' => 'ppic tooltip')), '/artist/' . $artist['url'], array('escape' => false));
            } else {
              echo $this->Html->link($this->Html->image('artist/profilepics/default.jpg', array('original-title' => $artist['name'], 'class' => 'ppic tooltip')), '/artist/' . $artist['url'], array('escape' => false));
            }
            
          }
        }
        ?>
      </div>
      
      <div class="lineup">
      <?php
      // AFFICHAGE PAR JOUR
      if ($canDisplayDays) {
				$nbdays = count($edition['Day']);
				
				// hack to always have the right order in edition days
				if (isset($edition['Day'][0]) && strtotime($edition['Day'][0]['date']) > strtotime($edition['Day'][1]['date'])){
					$days = array_reverse($edition['Day']);
				} else{
					$days = $edition['Day'];
				} 
				?>
				
        <table width="100%" class="days">
        <?php
				foreach($days as $day) {
					echo '<tr><td class="td-artist td-artist-horiz">';
					echo '<span class="day">' . strftime("%A", strtotime($day['date'])) . '</span>';
        	foreach ($day['Artist'] as $y => $artist){ ?>
						<?php
            if ($y !== 0) {
              echo ' . ';
						}
						if ($y < 3) {
							/*if (!empty($artist['fb_picture']))
								echo $this->Html->image($artist['fb_picture'], array('class' => 'absmiddle'));
							else 
								echo $this->Html->image('artist/profilepics/default.jpg', array('class' => 'absmiddle'));
							*/
						}
						$relationship = artistRelationship($artist['id'], $user['Artist']);
						if (!$relationship) {
            	echo $this->Html->link($artist['name'], '/artist/' . $artist['url'], array('class' => 'familiarity' . round($artist['familiarity'] * 10)));
						} else {
							echo $this->Html->link($artist['name'], '/artist/' . $artist['url'], array('class' => 'followed familiarity' . round($artist['familiarity'] * 10)));
						}
          }
        	echo '</td></tr>';
				} 
				?>
        </tr>
        </table>
        <?php
				
		  // AFFICHAGE PAR POPULARITE
			} elseif ($edition['Artist']) { ?>
				<table width="100%" class="days">
        <tr>
        <td class="td-artist">
        <?php
				foreach ($edition['Artist'] as $y => $artist){ ?>
					<?php
					if ($y !== 0)
						echo '. ';
					
					$relationship = artistRelationship($artist['id'], $user['Artist']);
					if (!$relationship) {
						echo $this->Html->link($artist['name'], '/artist/' . $artist['url'], array('class' => 'familiarity' . round($artist['familiarity'] * 10)));
					} else {
						echo $this->Html->link($artist['name'], '/artist/' . $artist['url'], array('class' => 'followed familiarity' . round($artist['familiarity'] * 10)));
					}
        }
				if ($edition['Edition']['date_lineup'] != '2000-01-01' && $edition['Edition']['date_lineup'] != '1970-01-01' && $edition['Edition']['date_lineup'] != '0000-00-00') {
					$ts = strtotime($edition['Edition']['date_lineup']);
					if ($ts > time())
						echo "<br /><br /><div class='date-lineup'>La lineup complète sera annoncée le " . utf8_encode(strftime("%d %B %Y", $ts)) . '.</div>';
				} else {
					$ts_edition = strtotime($edition['Edition']['date_start']);
					if ($ts_edition > time())
						echo "<br /><br /><div class='date-lineup'>D'autres artistes sont encore à venir.</div>";
				}
				?>
        </td>
        </tr>
        </table>
        
      <?php
      // LINEUP A VENIR
			} elseif ($edition['Edition']['date_lineup'] != '2000-01-01' && $edition['Edition']['date_lineup'] != '0000-00-00') {
				$ts = strtotime($edition['Edition']['date_lineup']);
				echo "Les artistes seront annoncés le " . utf8_encode(strftime("%d %B %Y", $ts)) . '.';
				
		  // PAS DE LINEUP
      } else {
				$ts = strtotime($edition['Edition']['date_lineup']);
				if ($ts < time())
					echo "<div class='date-lineup'>Pas de lineup disponible pour cette édition.</div>";
				else 
					echo "<div class='date-lineup'>Les artistes seront bientôt annoncés.</div>";
			}
      ?>
      </div>
      
      <?php 
      // PHOTOS
      if (!empty($photos['photo'])) { ?>
      <div class="photos">
      <h2>Photos</h2>
      <?php  
					echo '<ul class="photos">';
					foreach ($photos['photo'] as $photo) {
						echo '<li>' . $this->Html->link($this->Html->image('http://farm'.$photo['farm'].'.static.flickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_s.jpg'), 'http://farm'.$photo['farm'].'.static.flickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_b.jpg', array('rel' => 'photos', 'escape' => false)) . '</li>';
					}
					echo '</ul>'; ?>
      </div>
      <?php } ?>
      
      <?php 
      // SOUNDCLOUD
      if (!empty($edition['Liveset'])) { ?>
      <div class="livesets">
      <h2>Sets SoundCloud</h2>
        <?php 
        foreach($edition['Liveset'] as $liveset) {
          $embed_info = json_decode($client->get('oembed', array('url' => $liveset['url'])));
          print $embed_info->html;
        }
         ?>
      </div>
      <?php } ?>
      
      <?php 
      // PLAYLIST SPOTIFY
      if (!empty($edition['Edition']['spotify_uri'])) { ?>
      <div class="playlist">
      <h2>Playlist Spotify</h2>
        <?php echo '<iframe src="https://embed.spotify.com/?uri=' . $edition['Edition']['spotify_uri'] . '&theme=white" width="270" height="350" frameborder="0" allowtransparency="true"></iframe>'; ?>
      </div>
      <?php } ?>
      
      <?php 
      // FESTIVALIERS QUI Y VONT
      if ($timestamp >= time() && !empty($users_going)) { ?>
      <div class="festivaliers">
        <h2>Ils y vont</h2>
        <?php 
        foreach ($users_going as $festivalier) {
        	if (!empty($festivalier['facebook_id'])) 
        	   echo $this->Html->link($this->Html->image('http://graph.facebook.com/' . $festivalier['facebook_id'] . '/picture', array('original-title' => $festivalier['login'], 'class' => 'ppic tooltip')), '/profil/' . $festivalier['login'], array('escape' => false));
        	else 
        	   echo $this->Html->link($this->Html->image('user/profilepics/default.jpg', array('original-title' => $festivalier['login'], 'class' => 'ppic tooltip')), '/profil/' . $festivalier['login'], array('escape' => false));
        }
        ?>
        </div>
        <div class="spacer"></div><br />
      </div>
      <?php } ?>
      
      <?php 
      // FESTIVALIERS QUI Y ETAIENT
      if ($timestamp < time() && !empty($users_went)) { ?>
      <div class="festivaliers">
        <h2>Ils y étaient</h2>
        <?php 
        foreach ($users_went as $festivalier) {
        	if (!empty($festivalier['facebook_id'])) 
        	   echo $this->Html->link($this->Html->image('http://graph.facebook.com/' . $festivalier['facebook_id'] . '/picture', array('original-title' => $festivalier['login'], 'class' => 'ppic tooltip')), '/profil/' . $festivalier['login'], array('escape' => false));
        	else 
        	   echo $this->Html->link($this->Html->image('user/profilepics/default.jpg', array('original-title' => $festivalier['login'], 'class' => 'ppic tooltip')), '/profil/' . $festivalier['login'], array('escape' => false));
        }
        ?>
      </div>
        
      <div class="more-infos">
        <h2>Informations</h2>
        <p><?php echo $this->Html->image('flags/' . $festival['Country']['locale'].'.png', array('class' => 'flag', 'width' => '24px', 'height' => '17px'));?> Lieu :
        <?php 
    		if ($festival['Country']['locale'] != 'fre') {
    			echo $festival['Country']['name'];
    		} else {
          if(isset($festival['Region']['name'])) echo $festival['Region']['name'];
          if(isset($festival['City']['name'])) echo ', '. $festival['City']['name'] . ' ('. $festival['Department']['code'].')';
    		} ?></p>
        
        <br /><br />
      
      
        <ul class="infos_list">
          <?php if (isset($festival['Festival']['capacity']) && $festival['Festival']['capacity'] != 0) { ?>
          <li class="infos-places">
            <?php echo $this->Html->image('icons/people.png', array('class' => '')); ?>
            <span>&nbsp;Nombre de places : <?php echo $festival['Festival']['capacity']; ?></span>
          </li>
          <?php 
          }
    			if (isset($editions[0]['Edition']['price']) && $editions[0]['Edition']['price'] != 0) { ?>
          <li class="infos-website">
            <?php echo $this->Html->image('icons/euro.png', array('class' => '')); ?>
            <span>&nbsp;Prix : <?php echo $editions[0]['Edition']['price']; ?> euros</span>
          </li>
          <?php 
          }
    			if (isset($festival['Festival']['creation_year']) && $festival['Festival']['creation_year'] != 0) { ?>
          <li class="infos-website">
            <?php echo $this->Html->image('icons/calendar.png', array('class' => '')); ?>
            <span>&nbsp;Date de création : <?php echo $festival['Festival']['creation_year']; ?></span>
          </li>
          <?php 
          }
          if (isset($festival['Festival']['website'])) { ?>
          <li class="infos-website">
            <?php echo $this->Html->image('icons/link.png', array('class' => '')); ?>
            <span>&nbsp;Site : <?php echo $this->Html->link($festival['Festival']['website'], 'http://' . $festival['Festival']['website']); ?></span>
          </li>
          <?php 
          }
    			/*
          if (isset($festival['Festival']['email'])) { ?>
          <li class="infos-contact">
            <?php echo $this->Html->image('icons/contact.png', array('class' => '')); ?>
            <span>&nbsp;<?php echo $this->Html->link('Contacter le festival', 'mailto:' . $festival['Festival']['email']); ?></span>
          </li>
          <?php } */?>
        </ul>
      </div>

        
      <div class="spacer"></div><br />
    </div>
      <?php } ?>
      
      
      <!-- <div id="tabs-<?php echo $anneeedition; ?>-videos"> 
        <p></p>
      </div>
      -->

    <?php if ($key != $last_key) echo '<hr class="edition_separator" />';
      }
    }
    ?>
  
  <div id="col" class="festival">
  </div>
  
  <div id="sidebar" class="festival">
    
    <!-- 
    <div id="festival_news" class="sidebar_block">
      <h2>Actualités</h2>
      <ul class="news_list">
        <li><span class="news_date">16/12</span><span class="news_title">Des nouveaux noms pour Rock en Seine 2012</span></li>
      </ul>
    </div>
    
    
    <div id="festival_same" class="sidebar_block">
      <h2>festivals similaires</h2>
      <?php echo $this->Html->image('festival/profilepics/thumb.festival.'. $same_festival['Festival']['photo_r'], array('class' => 'festival_thumb', 'height' => '68px', 'width' => '68px')); ?>
      <div class="festival_shortinfos">
        <h3><?php echo $this->Html->image('flags/'. $same_festival['Country']['locale'] .'.png', array('class' => 'flag')); ?> 
  					<?php echo $this->Html->link($same_festival['Festival']['name'], '/festival/' . $same_festival['Festival']['url']); ?>
        </h3>
        <p class="date">
        <?php
        if (isset($same_festival['Edition']) AND isset($same_festival['Edition']['date_start']) AND isset($same_festival['Edition']['date_end'])) {
          $date_start = strtotime($same_festival['Edition']['date_start']);
          $date_end = strtotime($same_festival['Edition']['date_end']);
          $day_format = '%e';
  				
          if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
            $day_format = '%#d';
  					
          echo 'du '.strftime($day_format, $date_start).' ';
  				
          if (strftime('%B', $date_start) != strftime('%B', $date_end) OR strftime('%Y', $date_start) != strftime('%Y', $date_end))
            echo strftime('%B', $date_start).' ';
          if (strftime('%Y', $date_start) != strftime('%Y', $date_end))
            echo strftime('%Y', $date_start).' ';
          echo 'au '.strftime($day_format.' %B %Y', $date_end);
        }
        ?></p>
        <p class="location"><?php echo (!empty($same_festival['City']['name']))? $same_festival['City']['name'].', ' : ''; echo (!empty($same_festival['Department']['name']))? $same_festival['Department']['name'] : '';?></p>
      </div>
    </div>
    
    <div id="festival_nearest" class="sidebar_block">
      <h2>festivals à proximité</h2>
      <?php echo $this->Html->image('festival/profilepics/thumb.festival.'.$nearest_festival['Festival']['photo_r'], array('class' => 'festival_thumb', 'height' => '68px', 'width' => '68px')); ?>
      <div class="festival_shortinfos">
        <h3>
        	<?php echo $this->Html->image('flags/'.$nearest_festival['Country']['locale'].'.png', array('class' => 'flag')); ?>
  				<?php echo $this->Html->link($nearest_festival['Festival']['name'], '/festival/' . $nearest_festival['Festival']['url']); ?>
       </h3>
        
        <p class="date">
  			<?php
        if (isset($nearest_festival['Edition']) && isset($nearest_festival['Edition']['date_start']) && isset($nearest_festival['Edition']['date_end'])) {
          $date_start = strtotime($nearest_festival['Edition']['date_start']);
          $date_end = strtotime($nearest_festival['Edition']['date_end']);
          $day_format = '%e';
  				
          if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
            $day_format = '%#d';
  					
          echo 'du '.strftime($day_format, $date_start).' ';
  				
          if (strftime('%B', $date_start) != strftime('%B', $date_end) || strftime('%Y', $date_start) != strftime('%Y', $date_end))
            echo strftime('%B', $date_start).' ';
          if (strftime('%Y', $date_start) != strftime('%Y', $date_end))
            echo strftime('%Y', $date_start).' ';
          echo 'au '.strftime($day_format.' %B %Y', $date_end);
        }
        ?></p>
        <p class="location"><?php echo (!empty($nearest_festival['City']['name']))? $nearest_festival['City']['name'].', ' : ''; echo (!empty($nearest_festival['Department']['name']))? $nearest_festival['Department']['name'] : '';?></p>
      </div>
    </div>
    -->
  </div>
</div>