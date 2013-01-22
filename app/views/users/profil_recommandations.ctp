<?php
$this->set('title_for_layout', 'Festivals recommandés');

echo $this->Html->scriptBlock("
	$(document).ready(function() {
		$(function() {
      $('.tooltip-w').tipsy({gravity: 'w', 'html': true});
 	  });		
	});
", array('inline' => false));
?>

<div class="photo-c">
  <?php echo $html->image('festival/covers/recos.jpg'); ?>
</div>

<div id="col" class="home">
	<h1>Festivals</h1>
  <p>Retrouvez ici les recommandations de festivals selon vos goûts</p>
	<br />
  
  <?php
  echo $form->create('User', array('url' => '/'));
	echo 'Trier par :';
  echo $form->input('sortby', array('div' => false, 'label' => false, 'options' => array(
    'affinity' => 'Affinité',
    'pop' => 'Popularité',
    'price' => 'Tarifs',
    'dist' => 'Distance'
  )));
	
	echo '<button type="submit" class="button save" id="BtnSave">Trier</button>';
  echo $form->end();
	?><br /><br />
  
  <div id="festivals-reco">
  <?php if (!empty($recommanded_fest)) {
		echo '<table>'; ?>
		<tr>
    <th></th>
    <th class="th-name">Festival</th>
    <th>Lieu</th>
    <th>Places</th>
    <th>Prix</th>
    </tr>
    <?php
		foreach($recommanded_fest as $i => $edition) {
			if (!empty($edition['Artist'])) {
    	?>
        <tr <?php if ($i&1) echo 'class="pair"'; ?>>
          <?php
          echo '<td class="td-ppic">';
          echo $this->Html->link($this->Html->image('festival/profilepics/thumb.festival.' . $edition['Festival']['photo_r'], array('class' => 'ppic')), '/festival/' . $edition['Festival']['url'], array('escape' => false));
          echo '</td>';
          
          echo '<td class="td-lineup">';
          echo '<h3>' . $this->Html->link($edition['Festival']['name'], '/festival/' . $edition['Festival']['url']) . '</h3> ';
          
          echo '<span class="date">';
          $ts_start = strtotime($edition['Edition']['date_start']);
          $ts_end = strtotime($edition['Edition']['date_end']);
          if ($ts_start == $ts_end) {
            echo 'le ' . strftime("%d %B", $ts_end);
          } elseif (strftime('%m', $ts_start) == strftime('%m', $ts_end)) {
            echo strftime("%d", $ts_start) . ' ➔ ' . strftime("%d %B", $ts_end);
          } else {
            echo strftime("%d %B", $ts_start) . ' ➔ ' . strftime("%d %B", $ts_end);
          }
          echo '</span>';
          
          echo '<span class="lineup">';
          if (!empty($edition['Artist'])) {
              foreach ($edition['Artist'] as $x => $artist) {
                if ($x != 0)
                  echo ', ';
                if (artistRelationship($artist['id'], $user['Artist'])) {
                  echo $this->Html->link($artist['name'], '/artist/' . $artist['url'], array('class' => 'liked'));
                } else {
                  echo $this->Html->link($artist['name'], '/artist/' . $artist['url']);
                }
                if ($x > 9) { echo '...'; break; }
              }
          }
          echo '<br /><br />'; 
          $tooltip = '';
          if ($edition[0]['favorite_artist'] !== 0) { if ($edition[0]['favorite_artist'] === 1) $tooltip .= '1 artiste favori <br />'; else $tooltip .= $edition[0]['favorite_artist'] . ' artistes favoris <br />';  }
          if ($edition[0]['liked_artist'] !== 0) { if ($edition[0]['liked_artist'] === 1) $tooltip .= '1 artiste suivi <br />'; else $tooltip .= $edition[0]['liked_artist'] . ' artistes suivis <br />';  }
          if ($edition[0]['similar_artist'] !== 0) { if ($edition[0]['similar_artist'] === 1) $tooltip .= '1 artiste similaire <br />'; else $tooltip .= $edition[0]['similar_artist'] . ' artistes similaires <br />';  }
          echo '<span class="affinity tooltip-w" title="'. $tooltip .'">' . round($edition[0]['affinity']*100) . '% d\'affinité</span>';
          echo '</span>';
          echo '</td>';
          
          echo '<td class="td-lieu">';
          if ($edition['Country']['locale'] !== 'fre') {
             echo $edition['Country']['name'];
          } else {
            if (isset($edition['Region']['name'])) echo '<p>' . $edition['Region']['name'] . '</p>';
            if (isset($edition['City']['name'])) echo '<p>' . $edition['City']['name'] . '</p>';
          }
          echo '<p>';
          if(!empty($edition['Country']['locale'])) echo $this->Html->image('flags/' . $edition['Country']['locale'] . '.png', array('class' => 'absmiddle flag'));
          if (isset($edition['City']['name'])) echo '('. $edition['Department']['code'].')';
          echo '</p></td>';
          
          echo '<td class="td-capacity">';
          if ($edition['Festival']['capacity'] != 0) echo number_format($edition['Festival']['capacity'], 0, ',', ' ') . ''; else echo '-';
          echo '</td>';
          
          echo '<td class="td-price">';
          if ($edition['Edition']['price'] != 0)	echo $edition['Edition']['price'] . '€'; else echo '-';
          echo '</td>';
          
        echo '</tr>';
			}
		}
		echo '</table>';
	} else {
		echo 'Aucun pour le moment.';
	}
	?>

	</div>
  
</div>

<div id="sidebar" class="home">
	
  <div id="resume-profil" class="sidebar_block">
		<h2>profil <?php echo $this->Html->link(' voir', '/profil/' . $user['User']['login'], array('class' => 'next')); ?></h2>
    <ul>
  	<?php
		echo '<li>' . count($user['Artist']) . ' artistes suivis.</li>';
		echo '<li>' . $favorites . ' favoris.</li>';
		?>
    </ul>
    <br /><br />
  </div>
  
	<div id="artists-followed" class="sidebar_block">
		<h2>artistes favoris <?php echo $this->Html->link(' éditer', '/profil/' . $user['User']['login'] . '/artists', array('class' => 'edit')); ?></h2>
  	<?php
    if (!empty($user['Artist'])) { ?>
      <ul class="artists-list">
      <?php
      foreach($user['Artist'] as $artist) {
				//debug($artist);
				if ($artist['ArtistsUser']['favorite'] == 1) {
					echo '<li><span>';
					if (!empty($artist['fb_picture']))
						echo $this->Html->link($this->Html->image($artist['fb_picture'], array('original-title' => $artist['name'], 'class' => 'tooltip')), '/artist/' . $artist['url'], array('escape' => false));
					else 
						echo $this->Html->link($this->Html->image('artist/profilepics/default.jpg', array('original-title' => $artist['name'], 'class' => 'tooltip')), '/artist/' . $artist['url'], array('escape' => false));
					echo '</span></li>';
				}
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