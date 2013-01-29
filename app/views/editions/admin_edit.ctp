<table class="toolbar">
	<tr>
    	<td><h1>Modifier une édition</h1></td>
    	<td width="40" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'editions', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>

	<?php 
	
	echo $form->create('Edition'); 
	echo $form->input('id', array('type'=>'hidden'));
	
	//debug($this);
        
		echo $form->input('Festival.name', array('label' => 'Festival :', 'value' => $edition['Festival']['name'], 'disabled' => true));
		echo $form->input('festival_id', array('type' => 'hidden', 'value' => $edition['Festival']['id']));
		echo $form->input('date_start', array('label' => 'Date de début :', 'maxYear' => date('Y') + 1));
		echo $form->input('date_end', array('label' => 'Date de fin :', 'maxYear' => date('Y') + 1));
		echo $form->input('price', array('label' => 'Prix :<br /><span class="notes">prix du pass total (sans camping)</span>'));
		
		echo '<br /><br />';
		echo $form->input('spotify_uri', array('label' => 'Playlist Spotify :<br /><span class="notes">clic droit / copier l\'URI Spotify</span>'));
    echo $form->input('name', array('label' => 'Nom :<br /><span class="notes">ex : collection d\'été</span>'));
		echo $form->input('date_lineup', array('selected' => '2000-01-01', 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 1, 'label' => 'Date d\'annonce de la lineup :<br /><span class="notes">(laisser la valeur par défaut si aucune date)</span>'));
		
		echo $form->end('Valider'); ?>

	
