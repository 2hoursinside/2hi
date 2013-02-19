
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Dernières demandes</h1></td>
    	<td width="50" class="center">
        </td>
    </tr>
</table>


<table class="data">
	<tr>
    	  <th width="15%">Par</td>
        <th width="20%">Nom</td>
        <th>Commentaire</td>
        <th width="15%">Date</td>
        <th width="30">Link</td>
        <th width="35">Edit</td>
        <th width="35">Supr</td>
	</tr>
    
    <?php foreach($submissions as $i => $submission): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	 <td><?php echo $this->Html->link($submission['User']['login'], '/profil/' . $submission['User']['login']); ?></td>
       <td><?php echo $submission['Submission']['name']; ?></td>
       <td><?php echo substr($submission['Submission']['comment'], 0, 100) . '...'; ?></td>
       <td><?php
        	$timestamp = strtotime($submission['Submission']['created']);
					e(strftime("%d %B %Y", $timestamp));
		?></td>
        <td><?php if (!empty($submission['Submission']['source'])) echo $this->Html->link('x', $submission['Submission']['source']); ?></td>
        <td><?php echo $html->link($html->image('icons/edit.png'), array('controller' => 'submissions', 'action' => 'edit', 'prefix' => 'admin', $submission['Submission']['id']), array('escape' => false, 'title' => 'Modifier')); ?>
        </td>
        <td><?php
			echo $html->link($html->image('icons/delete.png'), array('controller' => 'submissions', 'action' => 'delete', 'prefix' => 'admin', $submission['Submission']['id']), array('escape' => false, 'title' => 'Supprimer'), "Etes-vous sûr de vouloir supprimer cette submission ?"); 
			?>
        </td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>