<table class="toolbar">
	<tr>
    	<td><h1>Modifier un genre</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'genres', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    <?php 
	
	echo ($form->create('Genre', array('action' => 'add')));
	echo ($form->input('Genre.id'));
	
	
	 ?> 
     
    <fieldset>
        <legend>Titres</legend>
        <?php
        echo $form->input('name', array('label' => 'Nom :'));
        ?> 
    </fieldset>
     
    <?php e($form->end('Valider')); ?>
