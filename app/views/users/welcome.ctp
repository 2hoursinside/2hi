<?php
$this->set('title_for_layout', 'Bienvenue');
?>

<div class="photo-c">
  <?php echo $html->image('user/covers/default_' . $user['User']['gender'] . '.jpg'); ?>
</div>

<div id="col" class="home">
	<h1>Bienvenue,</h1>
	<br />
  Nous avons créé 3 Jours Dehors dans le but de faciliter la découverte et la recherche de festivals sans avoir à parcourir les centaines de sites officiels des festivals. <br /><br />
  Si le site est en beta, c'est parce qu'il n'est pas vraiment terminé, et très largement améliorable. Il manque encore des fonctionnalités essentielles au site, il nous manque des programmations, des festivals, et surtout il nous manque vos retours ! <br /><br />
  
  Nous (créateurs de 3JD) sommes tous des festivaliers et avons pensé le service pour qu'il soit le plus agréable et fiable à utiliser pour tous les festivaliers. Mais pour cela nous avons également besoin de vous, puisque c'est un site qui vous est destiné. Faites nous part de tous les bugs que vous rencontrez, dîtes nous si la recommandation que nous allons vous proposer vous est satisfaisante, s'il y a des choses que vous ne comprenez pas ou qu'il manque...<br /><br />
	
  Bref, dîtes-nous tout <strong>via le bouton feedback</strong> tout en bas ou directement à cette adresse mail : <a href="mailto:contact@3joursdehors.fr">contact@3joursdehors.fr</a>. <br /><br />
  
  Bonne traque,<br /><br />
  
  <span class="grey">
  L'équipe 3 Jours Dehors
  </span>
  <br /><br /><br />
  
  <?php echo $this->Html->link('Voir votre profil', '/profil/' . $user['User']['login'], array('class' => 'button next')); ?>
  <?php echo $this->Html->link('Ajouter des artistes', '/profil/' . $user['User']['login'] . '/artists', array('class' => 'button add')); ?>
</div>

<div id="sidebar" class="home">
	
  
</div>