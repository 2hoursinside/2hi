
<table class="toolbar">
	<tr>
    	<td><h1>Editer une invitation</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'invitations', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    
	<?php 
	
	echo $form->create('Invitation', array('legend' => 'true')); 
	echo '<fieldset><legend>Informations à remplir</legend>';
	
	echo $form->input('id', array('type'=>'hidden'));
	
	echo $form->input('count', array('label' => 'Nombre :', 'default' => 1));
	echo $form->input('email', array('label' => 'Adresse mail :'));
  echo $form->input('code', array('label' => 'Code :'));
	echo $form->input('sent', array('label' => 'Déjà envoyé par mail :'));
	echo $form->end('Ajouter');
	echo '</fieldset>';
   
	?>
