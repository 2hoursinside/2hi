
<table class="toolbar">
	<tr>
    	<td><h1>Modifier un utilisateur</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'submissions', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>

	<?php 

    echo $this->Form->create('Submission', array('action' => 'edit'));   
    echo $form->input('id', array('type'=>'hidden'));	
  	echo $this->Form->input('name', array('label' => 'Nom du festival :'));
    echo $this->Form->input('source', array('label' => 'Lien vers le site :'));
    echo $this->Form->input('comment', array('label' => 'Commentaires :'));
  	echo $this->Form->input('user_id', array('label' => 'User :'));
  	
  	echo $this->Form->end('Modifier');
  	echo '</fieldset>';
  	?>
