<?php $this->set('title_for_layout', 'Paramètres');  ?>

<div class="photo-c">
</div>

<div class="wrapper">

  <div id="col" class="profil-parametres">
  	<h1><?php echo $this->Html->link($user['User']['login'], '/profil/' . $user['User']['login']); ?> &raquo; paramètres</h1>
    <p>Editer votre compte et vos informations personnelles</p><br />
      
    <?php 
  	if (isset($result)) echo $result;
  
  	echo $this->Form->create('User', array('url' => '/profil/' . $user['User']['login'] . '/parametres')); 
  	
  	echo '<fieldset class="infosperso"><legend>Informations personnelles</legend>';
  	echo $this->Form->input('id', array('type'=>'hidden'));
  	  //echo $this->Form->input('password', array('label' => 'Mot de passe <span class="notes">(ne rien mettre pour ne pas écraser l\'actuel)</span>', 'value' => ''));
  	//echo $this->Form->input('password_confirm', array('type' => 'password', 'label' => 'Confirmez le mot de passe'));
    echo $this->Form->input('first_name', array('label' => 'Prénom :'));
  	echo $this->Form->input('last_name', array('label' => 'Nom :'));
  	
  	echo $this->Form->input('gender', array('label' => 'Sexe :', 'options' => array('male' => 'Homme', 'female' => 'Femme')));
  	echo $this->Form->input('birth_date', array('label' => 'Date de naissance :', 'dateFormat' => 'DMY', 'maxYear' => date('Y'), 'minYear' => date('Y') - 80, 'selected' => $user['User']['birth_date']));
  	echo $this->Form->input('postal_code', array('label' => 'Code postal :', 'default' => $user['City']['postal_code']));
  	echo '</fieldset>';
  	
  	echo '<fieldset class="infossocial"><legend>Réseaux sociaux</legend>';
  	echo $this->Form->input('twitter', array('label' => $this->Html->image('icons/twitter.png', array('class' => 'absmiddle')) . ' Twitter :<span class="notes">ex: 3joursdehors</span>'));
  	echo $this->Form->input('lastfm', array('label' => $this->Html->image('icons/lastfm.png', array('class' => 'absmiddle')) . ' Last Fm :<span class="notes">ex: 3joursdehors</span>', 'after' => $this->Html->link('&raquo; Importer mes artistes', '/users/importFromLastfm', array('escape' => false, 'class' => 'tooltip', 'original-title' => 'Patientez pendant l\'importation.'))));
  	echo $this->Form->input('soundcloud', array('label' => $this->Html->image('icons/soundcloud.png', array('class' => 'absmiddle')) . ' Soundcloud :<span class="notes">ex: 3joursdehors</span>'));
  	echo $this->Form->input('hypem', array('label' => $this->Html->image('icons/hypem.png', array('class' => 'absmiddle')) . ' Hype Machine :<span class="notes">ex: 3joursdehors</span>'));
  	echo '</fieldset><br />';
  	
  	echo '<label for="BtnSave">&nbsp;</label><button type="submit" class="button save" id="BtnSave">Modifier</button>';
  	echo $this->Form->end();
  	?>
  </div>
  
  <div id="sidebar">
  </div>
</div>