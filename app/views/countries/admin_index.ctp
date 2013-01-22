
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les countries</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/add.png') . '<br />Ajouter', array('controller' => 'countries', 'action' => 'add', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>


<table class="data">
	<tr>
    	<th>Nom</td>
        <th width="35">Edit</td>
        <th width="35">Suppr</td>
	</tr>
    
    <?php foreach($countries as $i => $country): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	<td><?php echo $country['Country']['name']; ?></td>
        <td><?php echo $html->link($html->image('icons/edit.png'), array('controller' => 'countries', 'action' => 'edit', 'prefix' => 'admin', $country['Country']['id']), array('escape' => false, 'title' => 'Modifier le pays')); ?>
        </td>
        <td><?php
			echo $html->link($html->image('icons/delete.png'), array('controller' => 'countries', 'action' => 'delete', 'prefix' => 'admin', $country['Country']['id']), array('escape' => false, 'title' => 'Supprimer le pays'), "Etes-vous sûr de vouloir supprimer ce pays ?"); 
			?>
        </td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>