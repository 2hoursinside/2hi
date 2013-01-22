<?php
$this->set('title_for_layout', 'Recherche : ' . $q);
?>

<div class="photo-c">
  <?php echo $html->image('festival/covers/default.jpg'); ?>
</div>

<div id="col" class="search">
	<?php $paginator->options(array('url' => $this->passedArgs)); ?>
	<h1>Recherche : &laquo; <?php echo $q; ?> &raquo;</h1>
  <p><?php echo $paginator->counter(array('format' => "%count% résultats trouvés")); ?></p>
	<br />
  
	<?php
	foreach($data as $row):
    $model_name = key($row);
 		echo '<div class="festival-list">';
		
    switch($model_name) {
        case 'Festival':
					$edition = '';
					if (!empty($row['Edition'][0])) $edition = $row['Edition'][0];
					
						echo $this->Html->link($this->Html->image('festival/profilepics/thumb.festival.' . $row['Festival']['photo_r'], array('class' => 'ppic')), '/festival/' . $row['Festival']['url'], array('escape' => false));
						if(!empty($row['Country']['locale'])) echo $this->Html->image('flags/' . $row['Country']['locale'] . '.png', array('class' => 'flag'));
						echo '<h3>' . $this->Html->link($row['Festival']['name'], '/festival/' . $row['Festival']['url']) . '</span></h3>';
						
						if ($edition) {
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
						}
						
						echo '<span class="lieu">';
						if ($row['Country']['locale'] !== 'fre') {
							 echo $row['Country']['name'];
						} else {
							if (isset($row['Region']['name'])) echo $row['Region']['name'];
							if (isset($row['City']['name'])) echo ', ' . $row['City']['name'] . ' ('. $row['Department']['code'].')';
						}
						if ($row['Festival']['capacity'] != 0)
							echo ', ' . number_format($row['Festival']['capacity'], 0, ',', ' ') . ' places';
						echo '</span>';

          break;
 
        case 'Artist':
					if (!empty($row['Artist']['fb_picture']))
						echo $this->Html->link($this->Html->image($row['Artist']['fb_picture'], array('class' => 'ppic')), '/artist/' . $row['Artist']['url'], array('escape' => false));
					else 
						echo $this->Html->link($this->Html->image('artist/profilepics/default.jpg', array('class' => 'ppic')), '/artist/' . $row['Artist']['url'], array('escape' => false));
						
					echo '<h3>' . $this->Html->link($row['Artist']['name'], '/artist/' . $row['Artist']['url']) . '</h3>';
					echo '<span class="lieu">';
					if (!empty($row['Genre'])) {
						echo '<ul class="tags">';
						foreach($row['Genre'] as $j => $genre) {
							if (is_object($genre)) $genre = get_object_vars($genre);
							echo '<li><span class="tag_left">&nbsp;</span><span class="tag">' . $genre['name'] . '</span><span class="tag_right">&nbsp;</span></li>';
							if ($j > 3) break;
						}
						echo '</ul>';
					}
					echo '</span>';
					break;
    } 
        
    echo '</div>';
	endforeach; ?> 
 
 	<div class="spacer"></div><br /><br />
 	<div id="paginator-counter">
      <?php if (!empty($data)) echo $paginator->counter(array('format' => "Page %page% sur %pages%, %current% résultats sur %count%")); ?> 
  </div>
  <br />
  <div class="paging">
      <?php echo $paginator->prev('&laquo; Précédents', array('escape' => false));?>
   |  <?php echo $paginator->numbers();?> |
      <?php echo $paginator->next('Suivants &raquo;', array('class' => 'next2', 'escape' => false));?>
  </div>
  
</div>

<div id="sidebar">
</div>
