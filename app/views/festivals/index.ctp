<?php $this->set('title_for_layout', 'Festivals'); ?>
 		    
    <ul>
    <?php
 	foreach ($festivals as $festival) {
 		echo '<li>' . $html->link($festival['Festival']['name'], '/festival/' . $festival['Festival']['url']) . '</li>';
	}
	?>
	</ul>