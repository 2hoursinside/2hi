
<table class="toolbar">
	<tr>
    	<td><h1>Ajouter des invitations</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'invitations', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    
	<?php 
	
	echo $form->create('Invitation', array('legend' => 'true')); 
	echo '<fieldset><legend>Informations à remplir</legend>';
	
	echo $form->input('emails', array('type' => 'textarea', 'label' => 'Adresses mail :<br /><span class="notes">(séparés par virgule)</span>'));
	echo $form->end('Ajouter');
	echo '</fieldset>';
   
	?>
