
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les régions</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/add.png') . '<br />Ajouter', array('controller' => 'regions', 'action' => 'add', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>


<table class="data">
	<tr>
    	<th width="25%">Nom</th>
        <th>Pays</th>
        <th width="35">Edit</th>
        <th width="35">Suppr</th>
	</tr>
    
    <?php foreach($regions as $i => $region): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	<td><?php echo $region['Region']['name']; ?></td>
        <td><?php echo $region['Country']['name']; ?></td>
        <td><?php echo $html->link($html->image('icons/edit.png'), array('controller' => 'regions', 'action' => 'edit', 'prefix' => 'admin', $region['Region']['id']), array('escape' => false, 'title' => 'Modifier la région')); ?>
        </td>
        <td><?php
			echo $html->link($html->image('icons/delete.png'), array('controller' => 'regions', 'action' => 'delete', 'prefix' => 'admin', $region['Region']['id']), array('escape' => false, 'title' => 'Supprimer la région'), "Etes-vous sûr de vouloir supprimer cette région ?"); 
			?>
        </td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>