<?php $this->set("title_for_layout", 'Se connecter'); ?>

<div class="photo-c">
</div>


<div class="wrapper">

  <div id="login">
    <h1>Se connecter</h1>
    
    <div id="login-3jd">
    	 <?php
  			echo $this->Form->create('User');
  			echo '<fieldset class="infosperso"><legend>Login </legend>';
  			echo $this->Form->input('login', array('label' => 'Nom d\'utilisateur :'));
  			echo $this->Form->input('password', array('label' => 'Mot de passe :'));
  			echo '<label for="BtnSave">&nbsp;</label><button type="submit" class="button save" id="BtnSave">Connexion</button>';
  			echo $this->Form->end();
  			echo '</fieldset><br />';
      ?>
      <!--
      <fieldset class="infosperso"><legend>Avec Facebook</legend>
      <?php echo $this->Facebook->login(array('perms' => 'email,user_birthday,user_hometown,user_likes,user_location')); ?>
      </fieldset>
      -->
    </div>
    
    <div id="register-3jd">
      <?php
          echo '<fieldset class="infosperso"><legend>Inscription <span class="notes">(sur invitation)</span></legend>';
  				echo '3 Jours Dehors fonctionne actuellement en beta privée. Vous devez avoir une invitation pour vous inscrire au service.<br /><br /><br />';
  				echo $this->Html->link('Inscription', '/inscription', array('class' => 'button next'));
  				echo '<br /><br /><br />';
  				echo '<span title="Y U NO GIVE ME INVITATION?" id="y-u-no-give-me-invitation">Pas d\'invitation ? </span>';
  				echo $this->Html->link('Demandez la vôtre.', '/');
          echo '<br /><br /></fieldset><br />';
      ?>
      <br />
    </div>
  </div>
  
  
  
  <div id="sidebar">
  </div>

</div>