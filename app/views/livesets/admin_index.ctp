
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les sets</h1></td>
    	<td width="50" class="center">
    	 <td width="50" class="center">
		<?php echo $html->link($html->image('icons/add.png') . '<br />Ajouter', array('controller' => 'livesets', 'action' => 'add', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
        </td>
    </tr>
</table>


<table class="data">
	<tr>
    	  <th width="15%">Artiste</td>
        <th width="25%">Festival</td>
        <th>Nom</th>
        <th width="17%">Ajouté le</td>
        <th width="30">Link</td>
        <th width="35">Edit</td>
        <th width="35">Supr</td>
	</tr>
    
    <?php foreach($livesets as $i => $liveset): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	 <td><?php echo $this->Html->link($liveset['Artist']['name'], '/artist/' . $liveset['Artist']['url']); ?></td>
    	 <td><?php 
    	   $timestamp = strtotime($liveset['Edition']['date_start']);
    	   echo $this->Html->link($liveset['Festival']['name'] . ' ' . strftime("%Y", $timestamp) , '/festival/' . $liveset['Festival']['url']); ?></td>
       <td><?php echo $liveset['Liveset']['name']; ?></td>
       <td><?php
        	$timestamp = strtotime($liveset['Liveset']['created']);
					e(strftime("%d %B %Y", $timestamp));
		?></td>
        <td><?php if (!empty($liveset['Liveset']['url'])) echo $this->Html->link($this->Html->image('icons/soundcloud.png'), $liveset['Liveset']['url'], array('escape' => false)); ?></td>
        <td><?php echo $html->link($html->image('icons/edit.png'), array('controller' => 'livesets', 'action' => 'edit', 'prefix' => 'admin', $liveset['Liveset']['id']), array('escape' => false, 'title' => 'Modifier')); ?>
        </td>
        <td><?php
			echo $html->link($html->image('icons/delete.png'), array('controller' => 'livesets', 'action' => 'delete', 'prefix' => 'admin', $liveset['Liveset']['id']), array('escape' => false, 'title' => 'Supprimer'), "Etes-vous sûr de vouloir supprimer ce set ?"); 
			?>
        </td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>