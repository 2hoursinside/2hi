
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les éditions de <?php echo $festival['Festival']['name']; ?></h1></td>
    	<td width="40" class="center">
		<?php echo $html->link($html->image('icons/add.png') . '<br />Ajouter', array('controller' => 'editions', 'action' => 'add', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>


<table class="data">
	<tr>
    	<th width="25%">Festival</td>
        <th>Edition</td>
        <th width="70">Prix</td>
        <th width="35">Spoti</td>
        <th width="35">Edit</td>
        <th width="35">Supr</td>
	</tr>
    
    <?php foreach($editions as $i => $edition): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	<td><?php echo $edition['Festival']['name']; ?></td>
        <td>
          <?php 
          $timestamp_start = strtotime($edition['Edition']['date_start']);
          $timestamp_end = strtotime($edition['Edition']['date_end']);
          echo 'du ' . strftime("%d %B", $timestamp_start) . ' au ' . strftime("%d %B %Y", $timestamp_end);
          ?>
        </td>
        <td><?php if ($edition['Edition']['price'] != 0) echo $edition['Edition']['price'] . ' €'; ?> </td>
        
        <td><?php if ($edition['Edition']['spotify_uri'] != '') echo $html->image('icons/spotify.png'); ?></td>
        
        <td><?php echo $html->link($html->image('icons/edit.png'), array('controller' => 'editions', 'action' => 'edit', 'prefix' => 'admin',$edition['Edition']['id']), array('escape' => false)); ?></td>
        <td><?php echo $html->link($html->image('icons/delete.png'), array('controller' => 'editions', 'action' => 'delete', 'prefix' => 'admin', $edition['Edition']['id']), array('escape' => false)); ?></td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>

