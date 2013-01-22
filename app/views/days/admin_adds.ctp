<br />
<h2 class="green">Ajouter <?php echo $nb; ?> jours au festival <?php echo $festival['Festival']['name']; ?> édition <?php echo $edition['Edition']['date_start']; ?></h2>

<?php
	$dates = array();
	
	$date = new DateTime($edition['Edition']['date_start']);
	for ($x = 0; $x < $nb; $x++) {
		if ($x != 0) {
			$date->modify('+1 day');
		}
			$dates[] = $date->format('Y-m-d');
	}
?>


<?php echo $form->create('Day', array('action' => 'addmany')); 
	echo $form->input('festival_id', array('type'=>'hidden', 'value' => $edition['Edition']['festival_id'])); ?>
<table class="data">
	<tr>
    	<th>Jour</th>
        <th>Date</th>
	</tr>
    
    <?php for ($i = 1; $i <= $nb; $i++) { ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	<td>Jour <?php echo $i; ?></td>
      <?php 
			$j = $i - 1;
			$edition_id		= 'Day.' . $j . '.edition_id';
			$date				= 'Day.' . $j . '.date';			
      echo $form->input($edition_id, array('type'=>'hidden', 'value' => $edition['Edition']['id'])); 
			?>
        <td><?php echo $form->input($date, array('label' => false, 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 1, 'selected' => $dates[$j] )); ?></td>        
    </tr>
    <?php } ?>    
    
</table>

<?php echo $form->end('Ajouter'); ?>