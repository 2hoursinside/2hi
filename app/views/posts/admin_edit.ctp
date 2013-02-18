<table class="toolbar">
	<tr>
    	<td><h1>Modifier une news</h1></td>
    	<td width="50" class="center">
		<?php echo $this->Html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'posts', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
  <?php 
	echo ($form->create('Post', array('action' => 'edit')));
	echo ($form->input('Post.id'));
    
  echo $form->input('published', array('label' => 'Publié :'));
  echo $form->input('name', array('label' => 'Nom :', 'class' => 'big'));
  echo $form->input('url', array('label' => 'URL :', 'class' => 'big'));
  echo $form->input('festival_id', array('label' => 'Festival :'));
	 
	 //debug($post['Artist']);
	 if (empty($post['Post']['text'])) {
	   $content = 'Le festival ' . $post['Festival']['name'] . ' a rajouté ' . count($post['Artist']) . ' artistes à sa lineup. 

';
  	 foreach ($post['Artist'] as $artist) {
    	 $content .= $this->Html->link($artist['name'], '/artist/' . $artist['url']) . ', ';
  	 }
  	 echo $form->input('text', array('label' => 'Texte :', 'value' => $content));
  	 debug ($content);
	 } else {
  	 echo $form->input('text', array('label' => 'Texte :'));
	 }
	 
	 
	 echo $form->end('Valider'); ?>
	 
	 