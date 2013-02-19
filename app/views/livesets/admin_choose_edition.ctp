
<table class="toolbar">
	<tr>
    	<td><h1>Ajouter un set</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'livesets', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>

	<?php 

    echo $this->Form->create('Liveset', array('action' => 'addedition'));   
    echo $form->input('id', array('type'=>'hidden'));	 
  	echo $this->Form->input('edition_id', array('label' => 'Edition :', 'escape' => false));
  	echo $this->Form->end('Ajouter');
  	echo '</fieldset>';
  	?>
