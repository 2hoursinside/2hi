
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les news</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/add.png') . '<br />Ajouter', array('controller' => 'posts', 'action' => 'add', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>


<table class="data">
	<tr>
  		<th width="35">Publié</td>
  		<th width="35">Edité</td>
    	<th>Titre</td>
    	<th width="20%">Festival</td>
    	<th width="17%">Date</td>
      <th width="35">Modif</td>
      <th width="35">Suppr</td>
	</tr>
    
    <?php foreach($posts as $i => $post): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	<td><?php 
			$published = $post['Post']['published'];
			$updatepub = !($post['Post']['published']);
			echo $this->Html->link($this->Html->image('icons/publie_' . $published  . '.png'), '/admin/posts/publish/'.  $post['Post']['id'] . '/' . $updatepub , array('escape' => false, 'title' => '(Dé)publier la news'));	?></td>
			<td><?php if (!empty($post['Post']['text'])) echo 'x'; ?></td>
      <td><?php echo $post['Post']['name'] ?></td>
      <td>
        <?php if (!empty($post['Festival'])) echo $post['Festival']['name']; ?>
      </td>
      <td><?php
        	$timestamp = strtotime($post['Post']['created']);
					e(strftime("%d %B %Y", $timestamp));
		?></td>

        <td><?php echo $html->link($html->image('icons/edit.png'), array('controller' => 'posts', 'action' => 'edit', 'prefix' => 'admin', $post['Post']['id']), array('escape' => false, 'title' => 'Modifier la news')); ?>
        </td>
        <td><?php
			echo $html->link($html->image('icons/delete.png'), array('controller' => 'festivals', 'action' => 'delete', 'prefix' => 'admin', $post['Post']['id']), array('escape' => false, 'title' => 'Supprimer la news'), "Etes-vous sûr de vouloir supprimer cette news ?"); 
			?>
        </td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>