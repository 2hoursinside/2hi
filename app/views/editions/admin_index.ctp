
<table class="toolbar">
	<tr>
    	<td><h1 class="green">Les éditions</h1></td>
    	<td width="50" class="center">
        </td>
    </tr>
</table>
 
<?php
    echo $form->create('Edition', array('action' => 'temp')); 
    echo $form->input('festival_id', array( 'type' => 'select', 'label' => 'Choisissez un festival :'));
	
    echo $form->end('OK');

	?>
