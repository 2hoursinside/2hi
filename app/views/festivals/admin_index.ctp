
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les festivals</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/add.png') . '<br />Ajouter', array('controller' => 'festivals', 'action' => 'add', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>


<table class="data">
	<tr>
  		<th width="35">Chk</td>
    	<th width="30%">Nom</td>
        <th width="15%">Nb Edition</td>
        <th width="">Lieu</td>
        <th width="35">Edit</td>
        <th width="35">Jour</td>
        <th width="35">Arti</td>
        <th width="35">Modif</td>
        <th width="35">Suppr</td>
	</tr>
    
    <?php foreach($festivals as $i => $festival): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	<td><?php 
			$mustbechecked = $festival['Festival']['must_be_checked'];
			$updatecheck = !($festival['Festival']['must_be_checked']);
			echo $this->Html->link($this->Html->image('icons/publie_' . $mustbechecked  . '.png'), '/admin/festivals/check/'.  $festival['Festival']['id'] . '/' . $updatecheck , array('escape' => false, 'title' => 'Doit être rechecké sous peu'));	?></td>
    	<td><?php echo $festival['Festival']['name']; ?></td>
        <td>
        	<?php if($festival['Edition']) echo count($festival['Edition']); ?>
        </td>
        <td><?php echo $festival['Country']['name']; if (isset($festival['Region']['name'])) echo ', ' . $festival['Region']['name']; ?></td>
        <td><?php echo $html->link($html->image('icons/date.png'), array('controller' => 'editions', 'action' => 'preadd', 'prefix' => 'admin', $festival['Festival']['id']), array('escape' => false, 'title' => 'Ajouter une édition')); ?>
        </td>
        <td><?php echo $html->link($html->image('icons/date.png'), array('controller' => 'days', 'action' => 'many', 'prefix' => 'admin', $festival['Festival']['id']), array('escape' => false, 'title' => 'Ajouter des jours')); ?>
        </td>
        <td><?php echo $html->link($html->image('icons/music.png'), array('controller' => 'festivals', 'action' => 'addartist', 'prefix' => 'admin', $festival['Festival']['id']), array('escape' => false, 'title' => 'Ajouter des artistes')); ?>
        </td>
        <td><?php echo $html->link($html->image('icons/edit.png'), array('controller' => 'festivals', 'action' => 'edit', 'prefix' => 'admin', $festival['Festival']['id']), array('escape' => false, 'title' => 'Modifier le festival')); ?>
        </td>
        <td><?php
			echo $html->link($html->image('icons/delete.png'), array('controller' => 'festivals', 'action' => 'delete', 'prefix' => 'admin', $festival['Festival']['id']), array('escape' => false, 'title' => 'Supprimer le festival'), "Etes-vous sûr de vouloir supprimer ce festival ?"); 
			?>
        </td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>