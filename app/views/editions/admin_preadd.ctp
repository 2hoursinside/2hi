<table class="toolbar">
	<tr>
    	<td><h1>Ajouter une édition à <?php echo $festival['Festival']['name']; ?></h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'countries', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    <?php 
	
	echo ($form->create('Edition', array('action' => 'add')));
        
		echo $form->input('Festival.name', array('label' => 'Festival :', 'value' => $festival['Festival']['name'], 'disabled' => true));
		echo $form->input('festival_id', array('type' => 'hidden', 'value' => $festival['Festival']['id']));
		echo $form->input('date_start', array('label' => 'Date de début :', 'maxYear' => date('Y') + 1));
		echo $form->input('date_end', array('label' => 'Date de fin :', 'maxYear' => date('Y') + 1));
    echo $form->input('name', array('label' => 'Nom (facultatif) :<br /><span class="notes">ex : collection d\'été</span>'));
		echo $form->input('price', array('label' => 'Prix (facultatif) :<br /><span class="notes">prix du pass total (sans camping)</span>'));
		echo $form->input('date_lineup', array('selected' => '2000-01-01', 'dateFormat' => 'DMY', 'maxYear' => date('Y') + 1, 'label' => 'Date d\'annonce de la lineup :<br /><span class="notes">(laisser la valeur par défaut si aucune date)</span>'));
		
		echo $form->end('Valider'); ?>
