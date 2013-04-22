<?php
$this->set('title_for_layout', 'Actualités');

echo $this->Html->scriptBlock("
	$(document).ready(function() {
		$(function() {
      $('.tooltip-w').tipsy({gravity: 'w', 'html': true});
 	  });		
	});
", array('inline' => false));
?>

<div class="photo-c">
</div>

<div class="wrapper">

  <div id="col" class="posts">
  	<h1>Dernières actualités</h1>
  	<br />
    
    <?php 
    if (!empty($posts)) {
      foreach($posts as $post) {
        $timestamp = strtotime($post['Post']['created']);
        ?>
        <div class="post">
          <h2><?php echo $this->Html->link($post['Post']['name'], '/festival/' . $post['Festival']['url']); ?></h2>
          <span class="date">publié le <?php echo strftime("%d %B %Y", $timestamp); ?></span>
          <p class="content"><?php echo nl2br($post['Post']['text']); ?></p>
        </div>
        <hr />
      <?php
      }
    }
    ?>
    
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

</div>