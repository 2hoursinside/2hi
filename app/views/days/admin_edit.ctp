<table class="toolbar">
	<tr>
    	<td><h1>Modifier des jours à un festival</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'festivals', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    <?php 
	
	echo ($form->create('Day', array('action' => 'add')));
	 echo ($form->input('Day.id'));
	 ?> 
    
    <fieldset>
        <legend>Titres</legend>
        <?php
        echo $form->input('date', array('label' => 'Date :'));
        ?> 
    </fieldset>
     
    <?php e($form->end('Valider')); ?>
