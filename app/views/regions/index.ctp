
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les pays</h1></td>
    	<td width="50" class="center">
        </td>
    </tr>
</table>


<table>
	<tr>
    	<th>Nom</td>
        <th>Contenu</td>
        <th>Date</td>
	</tr>
    
    <?php foreach($actualites as $i => $actualite): ?>
    <tr <?php if ($i&1) echo 'class="pair"'; ?>>
    	<td><?php echo $actualite['Actualite']['title']; ?></td>
        <td><?php echo substr($actualite['Actualite']['text'], 0, 100); ?></td>
        
        <td><?php
        	$timestamp = strtotime($actualite['Actualite']['created']);
			e(strftime("%d %B %Y", $timestamp));
		?></td>
        
    </tr>
    <?php endforeach; ?>    
    
</table>