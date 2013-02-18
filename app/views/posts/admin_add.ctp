<table class="toolbar">
	<tr>
    	<td><h1>Ajouter une news</h1></td>
    	<td width="50" class="center">
		<?php echo $this->Html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'posts', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
  <?php 
	echo ($form->create('Post', array('action' => 'add')));
    
  echo $form->input('published', array('label' => 'Publié :'));
  echo $form->input('name', array('label' => 'Nom :', 'class' => 'big'));
  echo $form->input('festival_id', array('label' => 'Festival :'));

  echo $form->input('text', array('label' => 'Texte :'));
	 
	 
	 echo $form->end('Valider'); ?>
	 
	 