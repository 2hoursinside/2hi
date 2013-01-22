<table class="toolbar">
	<tr>
    	<td><h1>Ajouter un pays</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'countries', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    <?php 
	
	echo ($form->create('Country', array('action' => 'add')));
	 ?> 
     
    <fieldset>
        <legend>Titres</legend>
        <?php
        echo $form->input('name', array('label' => 'Nom :'));
        ?> 
    </fieldset>
     
    <?php e($form->end('Valider')); ?>
