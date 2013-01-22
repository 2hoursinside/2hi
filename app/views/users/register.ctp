<?php
$this->set("title_for_layout", 'Inscription');

echo $this->Html->script('jquery.tipsy', false);
echo $this->Html->scriptBlock("
	$(document).ready(function() {
		$('#register [title]').tipsy({trigger: 'focus', gravity: 'w'});
	});
	", array('inline' => false)); 
?>
<div class="photo-c">
  <?php echo $html->image('festival/covers/default.jpg'); ?>
</div>



<div id="login">
  <h1>Inscription</h1>
  
  <div id="register">
  	 <?php
			echo $this->Form->create('User', array('url' => '/inscription'));
			echo '<fieldset class="infosperso"><legend></legend>';
			
			if (isset($facebook_user['birthday'])) {
				$bd = explode('/', $facebook_user['birthday']);				
			}
			
			if (!empty($errors)) {
				echo '<div class="result error">';
				foreach ($errors as $error) {
					echo '<li>'. $error . '</li>';
				}
				echo '</div>';
			}
			
			
			echo '<br />';/*
			echo '<br /><label id="fbLabel" for="fbLogin">S\'inscrire avec Facebook :</label>';
			echo '<div id="fbLogin">';
				if (empty($facebook_user))
					echo $this->Facebook->login(array('perms' => 'email,user_birthday,user_hometown,user_likes,user_location'));
				else 
					echo $this->Facebook->logout();
				echo '<span class="notes">(cela va nous permettre de récupérer vos artistes favoris pour la suite)</span><br /><br />';
			echo '</div><br />';
			*/
			
			echo $this->Form->input('login', array('id' => 'loginInput', 'title' => 'Chiffres et lettres uniquement.', 'label' => '<span>*</span> Nom d\'utilisateur :'));
			echo $this->Form->input('email', array('title' => 'L\'adresse à laquelle vous avez reçu votre invitation.', 'label' => '<span>*</span> Adresse mail : '));
			echo $this->Form->input('password', array('title' => 'Minimum 6 caractères.', 'label' => '<span>*</span> Votre mot de passe : ', 'value' => ''));
			echo $this->Form->input('password_confirm', array('type' => 'password', 'label' => '<span>*</span> Confirmez le : ', 'value' => ''));
			echo '<br />';
			echo $this->Form->input('gender', array('label' => '<span>*</span> Sexe :', 'options' => array('male' => 'Homme', 'female' => 'Femme')));
			
			echo $this->Form->input('birth_date', array('label' => '<span>*</span> Date de naissance :', 'dateFormat' => 'DMY', 'maxYear' => date('Y'), 'minYear' => date('Y') - 80));
			
			echo $this->Form->input('postal_code', array('label' => '<span>*</span> Code postal :'));
			echo $this->Form->input('invitation_code', array('title' => 'Le code que vous avez reçu dans votre mail d\'invitation.', 'label' => '<span>*</span> Code d\'invitation : '));
			echo '<br /><label for="BtnSave">&nbsp;</label><button type="submit" class="button save" id="BtnSave">Inscription</button>';
			echo $this->Form->end();
			echo '<br /><br /><br /></fieldset><br />';
    ?>

  </div>
</div>



<div id="sidebar">
</div>