
<table class="toolbar">
	<tr>
    	<td><h1>Ajouter un utilisateur</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'users', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    
	<?php 
	
	echo $form->create('User', array('legend' => 'true')); 
	echo '<fieldset><legend>Informations à remplir</legend>';
	
	echo $form->input('login', array('label' => 'Nom d\'utilisateur :'));
	echo $form->error('login', "Le login ne doit contenir que des chiffres et/ou des lettres et être unique à chaque utilisateur.");
    echo $form->input('password', array('label' => 'Mot de passe :', 'value' => ''));
	echo $form->input('password_confirm', array('type' => 'password', 'label' => 'Confirmez le mot de passe :', 'value' => ''));
    echo $form->input('name', array('label' => 'Nom et prénom :'));
	echo $form->input('role', array('options' => array(
		'normal' => 'Utilisateur',
		'admin' => 'Administrateur',
 	), 'label' => 'Rôle :'));
	
	echo $form->end('Ajouter');
	echo '</fieldset>';
   
	?>
