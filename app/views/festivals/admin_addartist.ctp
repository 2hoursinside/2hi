<table class="toolbar">
	<tr>
    	<td><h1>Ajouter plusieurs artistes à <?php echo $festival['Festival']['name']; ?></h1></td>
    	<td width="40" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'festivals', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    <?php echo $form->create('Festival', array('url' => '/admin/festivals/addartist/' . $festival['Festival']['id']));
	echo '<fieldset><legend>Informations à remplir </legend>';
	
	$ajout = array('0' => '-- Choisir --');
	$days = $ajout + $days;
	echo $form->input('festival_id', array('type'=>'hidden', 'value' => $festival['Festival']['id'])); 
	echo $form->input('artists', array('label' => 'Artiste(s) :<br /><span class="notes">(separés par des virgules)</span>', 'type' => 'textarea'));
	
	echo $form->input('edition_id', array('label' => 'Edition :'));
	echo $form->input('day_id', array('label' => 'Jour :<br /><span class="notes">(facultatif)</span>', 'options' => $days));
  echo $form->end('Ajouter');
	echo '</fieldset>';
	
	?>