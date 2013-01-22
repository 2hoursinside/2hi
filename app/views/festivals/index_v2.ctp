 <?php $this->pageTitle = 'Tous les festivals'; ?>
 	    
        
        <div class="filter">
        <?php
		//////////
		// COUNTRY
		//////////
		echo $html->link($html->image('flags/fre.png', array('class' => 'absmiddle', 'border' => 0)), 
						array('controller' => 'festivals', 'action' => 'index', 'country' => 'fre'), 
						array('escape' => false, 'title' => 'France')
		);
		echo $html->link($html->image('flags/bel.png', array('class' => 'absmiddle', 'border' => 0)), 
						array('controller' => 'festivals', 'action' => 'index', 'country' => 'bel'), 
						array('escape' => false, 'title' => 'Belgique')
		);
		echo $html->link($html->image('flags/lux.png', array('class' => 'absmiddle', 'border' => 0)), 
						array('controller' => 'festivals', 'action' => 'index', 'country' => 'lux'), 
						array('escape' => false, 'title' => 'Luxembourg')
		);
		echo $html->link($html->image('flags/ger.png', array('class' => 'absmiddle', 'border' => 0)), 
						array('controller' => 'festivals', 'action' => 'index', 'country' => 'ger'), 
						array('escape' => false, 'title' => 'Allemagne')
		);
		echo $html->link($html->image('flags/ned.png', array('class' => 'absmiddle', 'border' => 0)), 
						array('controller' => 'festivals', 'action' => 'index', 'country' => 'ned'), 
						array('escape' => false, 'title' => 'Pays-Bas')
		);
		echo $html->link($html->image('flags/eng.png', array('class' => 'absmiddle', 'border' => 0)), 
						array('controller' => 'festivals', 'action' => 'index', 'country' => 'eng'), 
						array('escape' => false, 'title' => 'Angleterre')
		);
		echo $html->link($html->image('flags/ira.png', array('class' => 'absmiddle', 'border' => 0)), 
						array('controller' => 'festivals', 'action' => 'index', 'country' => 'ira'), 
						array('escape' => false, 'title' => 'Irlande')
		);

		echo '<br /><br />';
		
		
		//////////
		// GENRES
		//////////
		if(!empty($this->params['named']['genre'])) {
			// genre filter
			foreach($genres as $j => $genre) {
				if($this->params['named']['genre'] == $genre['Genre']['url']) {
					echo $html->link($genre['Genre']['name'], '/festivals/genre,' . $genre['Genre']['url'], array('class' => 'tag active'));
				} else {
					echo $html->link($genre['Genre']['name'], '/festivals/genre,' . $genre['Genre']['url'], array('class' => 'tag'));
				}
			}
		} else {
			// no genre filter
			foreach($genres as $j => $genre) {
				echo $html->link($genre['Genre']['name'], array('controller' => 'festivals', 'action' => 'index', 'genre' => $genre['Genre']['url']), array('class' => 'tag'));
			}
		}
		?>
        </div>
        
        
        
        
        <br /><br /><br />
        <h2 class="title">Festivals à venir</h2><br />
        <?php
        if (!empty($editions_after)) { 
		foreach ($editions_after as $i => $edition) {
			
			if ($i % 4 == 0) echo '<div class="spacer"></div>';
			
			echo '<div class="festivals-artists festivals-index">';
			echo $html->image('flags/' . $edition['Festival']['Country']['locale'] . '.png', array('class' => 'absmiddle')); ?>
			
			<?php 
			if (strlen($edition['Festival']['name']) < 18) {
				echo '<h3>';
				echo $html->link($edition['Festival']['name'], '/festival/' . $edition['Festival']['url'], array('class' => 'decoblue')); 
				echo '</h3>';
				echo $html->image('festival/thumb.festival.' . $edition['Festival']['photo_r'], array('class' => 'festival-imgc', 'width' => 175));
			} else {
				echo '<h3 class="title-medium">';
				echo $html->link($edition['Festival']['name'], '/festival/' . $edition['Festival']['url'], array('class' => 'decoblue')); 	
				echo '</h3>';
				echo $html->image('festival/thumb.festival.' . $edition['Festival']['photo_r'], array('class' => 'festival-imgc img-medium', 'width' => 175));
			}?> <br />
			 
			 <span class="infos-festival">
				<p class="infos-date">
				<?php	
					$timestamp_start = strtotime($edition['Edition']['date_start']);
					$timestamp_end = strtotime($edition['Edition']['date_end']);
					if ($timestamp_start != $timestamp_end)
						echo strftime("%d", $timestamp_start) . ' - ' . strftime("%d %B %Y", $timestamp_end);
					else 
						echo strftime("%d %B %Y", $timestamp_end);
				?>
				</p>
				<!--<p class="infos-country"><?php echo $edition['Festival']['Country']['name']; 
				if (!empty($edition['Festival']['Region']['name'])) echo ', ' . $edition['Festival']['Region']['name']; ?>
				</p>
				-->
				<p class="infos-places"><?php echo $edition['Festival']['places']; ?> places, <?php echo $edition['Festival']['price']; ?> €</p>
			 </span>
			<div class="spacer"></div>
			<?php
			foreach($edition['Festival']['Genre'] as $j => $genre) {
				echo $html->link($genre['name'], '/festivals/genre/' . $genre['url'], array('class' => 'tag'));
			}
			echo '</div>'; 
		}
		} else {
			echo '<div class="festivals-artists"><h3>Aucun pour le moment.</h3></div>';	
		} 
		echo '<div class="spacer"></div>'; //debug($editions_before); ?>
		
        
        
        
        
        
        
        
         <br /><br /><br />
    	<h2 class="title">Festivals passés</h2><br />
        
        <?php
        if (!empty($editions_before)) { 
		foreach ($editions_before as $i => $edition) {
			
			if ($i % 4 == 0) echo '<div class="spacer"></div>';
			
			echo '<div class="festivals-artists festivals-index">';
			echo $html->image('flags/' . $edition['Festival']['Country']['locale'] . '.png', array('class' => 'absmiddle')); ?>
			
			<?php 
			if (strlen($edition['Festival']['name']) < 18) {
				echo '<h3>';
				echo $html->link($edition['Festival']['name'], '/festival/' . $edition['Festival']['url'], array('class' => 'decoblue')); 
				echo '</h3>';
				echo $html->image('festival/thumb.festival.' . $edition['Festival']['photo_r'], array('class' => 'festival-imgc', 'width' => 175));
			} else {
				echo '<h3 class="title-medium">';
				echo $html->link($edition['Festival']['name'], '/festival/' . $edition['Festival']['url'], array('class' => 'decoblue')); 	
				echo '</h3>';
				echo $html->image('festival/thumb.festival.' . $edition['Festival']['photo_r'], array('class' => 'festival-imgc img-medium', 'width' => 175));
			}?> <br />
			 
			 <span class="infos-festival">
				<p class="infos-date">
				<?php	
					$timestamp_start = strtotime($edition['Edition']['date_start']);
					$timestamp_end = strtotime($edition['Edition']['date_end']);
					if ($timestamp_start != $timestamp_end)
						echo strftime("%d", $timestamp_start) . ' - ' . strftime("%d %B %Y", $timestamp_end);
					else 
						echo strftime("%d %B %Y", $timestamp_end);
				?>
				</p>
				<!--<p class="infos-country"><?php echo $edition['Festival']['Country']['name']; 
				if (!empty($edition['Festival']['Region']['name'])) echo ', ' . $edition['Festival']['Region']['name']; ?>
				</p>
				-->
				<p class="infos-places"><?php echo $edition['Festival']['places']; ?> places, <?php echo $edition['Festival']['price']; ?> €</p>
			 </span>
			<div class="spacer"></div>
			<?php
			foreach($edition['Festival']['Genre'] as $j => $genre) {
				echo $html->link($genre['name'], '/festivals/genre/' . $genre['url'], array('class' => 'tag'));
			}
			echo '</div>'; 
		}
		} else {
			echo '<div class="festivals-artists"><h3>Aucun pour le moment.</h3></div>';	
		} 
		echo '<div class="spacer"></div>'; //debug($editions_before); ?>