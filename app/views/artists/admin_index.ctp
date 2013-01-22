
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les artistes</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/add.png') . '<br />Ajouter', array('controller' => 'artists', 'action' => 'add', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>


 <?php
 				echo $html->link($html->image('lettres/9.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', '9'), array('escape' => false));
        echo $html->link($html->image('lettres/a.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'a'), array('escape' => false));
				echo $html->link($html->image('lettres/b.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'b'), array('escape' => false));
				echo $html->link($html->image('lettres/c.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'c'), array('escape' => false));
				echo $html->link($html->image('lettres/d.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'd'), array('escape' => false));
        echo $html->link($html->image('lettres/e.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'e'), array('escape' => false));
        echo $html->link($html->image('lettres/f.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'f'), array('escape' => false));
        echo $html->link($html->image('lettres/g.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'g'), array('escape' => false));
        echo $html->link($html->image('lettres/h.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'h'), array('escape' => false));
        echo $html->link($html->image('lettres/i.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'i'), array('escape' => false));
				echo $html->link($html->image('lettres/j.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'j'), array('escape' => false));
				echo $html->link($html->image('lettres/k.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'k'), array('escape' => false));
				echo $html->link($html->image('lettres/l.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'l'), array('escape' => false));
				echo $html->link($html->image('lettres/m.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'm'), array('escape' => false));
				echo $html->link($html->image('lettres/n.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'n'), array('escape' => false));
				echo $html->link($html->image('lettres/o.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'o'), array('escape' => false));
				echo $html->link($html->image('lettres/p.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'p'), array('escape' => false));
				echo $html->link($html->image('lettres/q.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'q'), array('escape' => false));
				echo $html->link($html->image('lettres/r.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'r'), array('escape' => false));
				echo $html->link($html->image('lettres/s.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 's'), array('escape' => false));
				echo $html->link($html->image('lettres/t.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 't'), array('escape' => false));
				echo $html->link($html->image('lettres/u.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'u'), array('escape' => false));
				echo $html->link($html->image('lettres/v.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'v'), array('escape' => false));
				echo $html->link($html->image('lettres/w.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'w'), array('escape' => false));
				echo $html->link($html->image('lettres/x.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'x'), array('escape' => false));
				echo $html->link($html->image('lettres/y.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'y'), array('escape' => false));
				echo $html->link($html->image('lettres/z.png'), array('controller' => 'artists','action' => 'index', 'prefix' => 'admin', 'z'), array('escape' => false));
				?>

<table class="data">
	<tr>
    	<th width="20%">Nom</td>
        <th width="">Genre(s)</th>
        <th width="35">Bio</td>
        <th width="35">Fb</td>
        <th width="35">Edit</td>
        <th width="35">Suppr</td>
	</tr>
    
    <?php foreach($artists as $i => $artist): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	<td><?php echo $artist['Artist']['name']; ?></td>
        <td>
        	<?php
        	foreach($artist['Genre'] as $j => $genre) {
				if ($j != 0) 
					echo ', ' . $genre['name'];
				else
					echo $genre['name'];
			}
			?>
        </td>

        <td><?php if(!empty($artist['Biographies'][0]['content'])) echo 'x'; ?><?php if(!empty($artist['Biographies'][1]['content'])) echo 'x'; ?></td>
        
        <td><?php
        	if(!empty($artist['Artist']['facebook_id'])) echo 'x';
		?></td>
        
        <td><?php echo $html->link($html->image('icons/edit.png'), array('controller' => 'artists', 'action' => 'edit', 'prefix' => 'admin', $artist['Artist']['id']), array('escape' => false, 'title' => 'Modifier l\'artiste')); ?>
        </td>
        <td><?php
			echo $html->link($html->image('icons/delete.png'), array('controller' => 'artists', 'action' => 'delete', 'prefix' => 'admin', $artist['Artist']['id']), array('escape' => false, 'title' => 'Supprimer l\'artiste'), "Etes-vous sûr de vouloir supprimer cet artiste ?"); 
			?>
        </td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>

<?php //debug($artist); ?>