<table class="toolbar">
	<tr>
    	<td><h1>Modifier une région</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'regions', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    <?php 
	
	echo ($form->create('Region', array('action' => 'add')));
	echo ($form->input('Region.id'));
	
	
	 ?> 
     
    <fieldset>
        <legend>Titres</legend>
        <?php
        echo $form->input('name', array('label' => 'Nom :'));
		echo $form->input('country_id', array('label' => 'Pays :'));
        ?> 
    </fieldset>
     
    <?php e($form->end('Valider')); ?>
