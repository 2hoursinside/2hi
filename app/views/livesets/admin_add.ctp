
<table class="toolbar">
	<tr>
    	<td><h1>Ajouter un set</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'livesets', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>

	<?php 

    echo $this->Form->create('Liveset', array('action' => 'add'));   
  	echo $this->Form->input('name', array('label' => 'Nom du set : <span class="notes">(facultatif)<br />ex: Agoria presents FORMS</span>', 'escape' => false));
    echo $this->Form->input('url', array('label' => 'Lien soundcloud : <br /><span class="notes">(avec http)</span>'));
    echo $this->Form->input('festival_id', array('label' => 'Festival :'));
  	echo $this->Form->input('artist_id', array('label' => 'Artiste :'));
  	
  	echo $this->Form->end('Suivant');
  	echo '</fieldset>';
  	?>
