
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les invitations</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/add.png') . '<br />Ajouter', array('controller' => 'invitations', 'action' => 'add', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
        <td width="50" class="center">
    <?php echo $html->link($html->image('icons/adds.png') . '<br />Plusieurs', array('controller' => 'invitations', 'action' => 'adds', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>


<table class="data">
	<tr>
  		<th width="35">Nb</td>
    	<th width="35%">Email</td>
      <th>Code</td>
      <th width="13%">Créé le</td>
      <th width="35">Sent</td>
      <th width="35">Edit</td>
      <th width="35">Supr</td>
	</tr>
    
    <?php foreach($invitations as $i => $invitation): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	<td><?php echo $invitation['Invitation']['count']; ?></td>
    	<td><?php echo $invitation['Invitation']['email']; ?></td>
      <td><?php echo $invitation['Invitation']['code']; ?></td>
        
        <td><?php
        	$timestamp = strtotime($invitation['Invitation']['created']);
					e(strftime("%d %B %Y", $timestamp));
		?></td>
        <td><?php if ($invitation['Invitation']['sent'] == 1) echo 'x'; ?></td>
        <td><?php echo $html->link($html->image('icons/edit.png'), array('controller' => 'invitations', 'action' => 'edit', 'prefix' => 'admin', $invitation['Invitation']['id']), array('escape' => false, 'title' => 'Modifier l\'invitation')); ?>
        </td>
        <td><?php
			echo $html->link($html->image('icons/delete.png'), array('controller' => 'invitations', 'action' => 'delete', 'prefix' => 'admin', $invitation['Invitation']['id']), array('escape' => false, 'title' => 'Supprimer l\'invitation'), "Etes-vous sûr de vouloir supprimer cette invitation ?"); 
			?>
        </td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>