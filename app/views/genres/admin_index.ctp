
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les genres</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/add.png') . '<br />Ajouter', array('controller' => 'genres', 'action' => 'add', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>


<table class="data">
	<tr>
    	<th>Nom</td>
        <th width="35">Edit</td>
        <th width="35">Suppr</td>
	</tr>
    
    <?php foreach($genres as $i => $genre): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	<td><?php echo $genre['Genre']['name']; ?></td>
        <td><?php echo $html->link($html->image('icons/edit.png'), array('controller' => 'genres', 'action' => 'edit', 'prefix' => 'admin', $genre['Genre']['id']), array('escape' => false, 'title' => 'Modifier le genre')); ?>
        </td>
        <td><?php
			echo $html->link($html->image('icons/delete.png'), array('controller' => 'genres', 'action' => 'delete', 'prefix' => 'admin', $genre['Genre']['id']), array('escape' => false, 'title' => 'Supprimer le genre'), "Etes-vous sûr de vouloir supprimer ce genre ?"); 
			?>
        </td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>