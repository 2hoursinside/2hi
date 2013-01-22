<table class="toolbar">
	<tr>
    	<td><h1>Ajouter plusieurs jours à <?php echo $festival['Festival']['name']; ?></h1></td>
    	<td width="40" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'festivals', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>

	<?php 
	
	echo $form->create('Day', array('action' => 'adds'));
	echo '<fieldset><legend>Informations à remplir </legend>';
	
	echo $form->input('nb', array('label' => 'Nombre de jours :'));
	echo $form->input('edition_id', array('label' => 'Edition :'));
	
	echo $form->input('festival_id', array('type'=>'hidden', 'value' => $festival['Festival']['id'])); 
	
	echo '<span class="notes">A utiliser uniquement si aucun jour de l\'édition n\'a déjà été créé.</span>';
  echo $form->end('Suivant');
	echo '</fieldset>';

	?>
	
    <div id="tableau">
    
    </div>