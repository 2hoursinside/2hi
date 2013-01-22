<?php $this->set('title_for_layout', 'Editer vos artistes');  

echo $this->Html->scriptBlock("
	$(document).ready(function() {
		$(function() {
      $('.tooltip-top').tipsy({gravity: 's', live: true});
 	  });			
		
		 $('#suggest').bind('focus', function(){
			if(this.value == 'Ex : Daft Punk')
				this.value = '';
		});
		
		$('#suggest').bind('blur', function(){
			if(this.value == '')
				this.value = 'Ex : Daft Punk';
		});
		
		$('#col .rm-artist a').live('click', function(){
			$('.tipsy').remove();
			$.post(
				'" . $this->webroot . "users/editArtist', 
				{ id: $(this).attr('id'), name: $(this).attr('name'), rel: $(this).attr('rel') },
				function(result) { 
					if (result) {
						result = JSON.parse(result);
						var artist = '#art' + result.id;
						var favorite = '#fav' + result.id;
						 $(artist).fadeOut(500, function () {
							 $(artist).remove();
						 });
						 $(favorite).fadeOut(500, function () {
							 $(favorite).remove();
						 });
					} else {
						alert('Oops, problème de sauvegarde');
					}
				}
			);
		});
		
		$('#col .add-fav a').live('click', function(){
			$('.tipsy').remove();
			$.post(
				'" . $this->webroot . "users/editArtist', 
				{ id: $(this).attr('id'), name: $(this).attr('name'), rel: $(this).attr('rel') },
				function(result) { 
					if (result) {
						result = JSON.parse(result);
						if (result.alert) {
							alert(result.alert);
						} else {
							$('.no-favorite').fadeOut();
							var artist = '#art' + result.id + ' .add-fav';
							var link = artist + ' a';
							$(link).attr('rel', 'rm-fav');
							$(link).attr('original-title', 'Supprimer des favoris');
							$(artist).removeClass('add-fav').addClass('rm-fav');
							
							$('#art' + result.id).clone().prependTo('#edit-favorites').attr('id', 'fav' + result.id);
							$('#fav' + result.id + ' div.rm-artist').remove();
						}
					} else {
						alert('Oops, problème de sauvegarde');
					}
				}
			);
		});
		
		$('#col .rm-fav a').live('click', function(){
			$('.tipsy').remove();
			$.post(
				'" . $this->webroot . "users/editArtist', 
				{ id: $(this).attr('id'), name: $(this).attr('name'), rel: $(this).attr('rel') },
				function(result) { 
					if (result) {
						result = JSON.parse(result);
						var artist = '#art' + result.id + ' .rm-fav';
						var link = artist + ' a';
						var favorite = '#fav' + result.id;
						$(favorite).fadeOut(500, function () {
							 $(favorite).remove();
						});
						$(link).attr('rel', 'add-fav');
						$(link).attr('original-title', 'Ajouter aux favoris');
						$(artist).removeClass('rm-fav').addClass('add-fav');
					} else {
						alert('Oops, problème de sauvegarde');
					}
				}
			);
		});
			
		$('#edit-similar .add-artist a').live('click', function(){
			$('.tipsy').remove();
			$.post(
				'" . $this->webroot . "users/editArtist', 
				{ id: $(this).attr('id'), name: $(this).attr('name'), rel: $(this).attr('rel') },
				function(result) { 
					if (result) {
						result = JSON.parse(result);
						if (result.alert) {
							alert(result.alert);
						} else {
							$('.no-artist').fadeOut();
							var similar = '#sim' + result.id;
							var popular = '#pop' + result.id;
							$(similar).clone().prependTo('#edit-artists').attr('id', 'art' + result.id);
							$('#art' + result.id + ' div.add-artist').remove();
							$('#art' + result.id + ' div.rm-artist').show();
							
							$(similar).fadeOut(500, function () {
							 $(similar).remove();
						 });
						  $(popular).fadeOut(500, function () {
							 $(popular).remove();
						 });
						}
					} else {
						alert('Oops, problème de sauvegarde');
					}
				}
			);
		});
		
		$('#edit-popular .add-artist a').live('click', function(){
			$('.tipsy').remove();
			$.post(
				'" . $this->webroot . "users/editArtist', 
				{ id: $(this).attr('id'), name: $(this).attr('name'), rel: $(this).attr('rel') },
				function(result) { 
					if (result) {
						result = JSON.parse(result);
						if (result.alert) {
							alert(result.alert);
						} else {
							$('.no-artist').fadeOut();
							var similar = '#sim' + result.id;
							var popular = '#pop' + result.id;
							$(popular).clone().prependTo('#edit-artists').attr('id', 'art' + result.id);
							$('#art' + result.id + ' div.add-artist').remove();
							$('#art' + result.id + ' div.rm-artist').show();
							
							$(similar).fadeOut(500, function () {
							 $(similar).remove();
						 });
						  $(popular).fadeOut(500, function () {
							 $(popular).remove();
						 });
						}
					} else {
						alert('Oops, problème de sauvegarde');
					}
				}
			);
		});
		
		$('#edit-similar .add-fav a').live('click', function(){
			$('.tipsy').remove();
			$.post(
				'" . $this->webroot . "users/editArtist', 
				{ id: $(this).attr('id'), name: $(this).attr('name'), rel: $(this).attr('rel') },
				function(result) { 
					if (result) {
						result = JSON.parse(result);
						if (result.alert) {
							alert(result.alert);
						} else {
							$('.no-favorite').fadeOut();
							$('.no-artist').fadeOut();
							var similar = '#sim' + result.id;
							var popular = '#pop' + result.id;
							$(similar).clone().prependTo('#edit-artists').attr('id', 'art' + result.id);
							$('#art' + result.id + ' div.add-artist').remove();
							$('#art' + result.id + ' div.add-fav').remove();
							$('#art' + result.id + ' div.rm-artist').show();
							$('#art' + result.id + ' div.rm-fav').show();
							
							$(similar).clone().prependTo('#edit-favorites').attr('id', 'fav' + result.id);
							$('#fav' + result.id + ' div.rm-fav').show();
							$('#fav' + result.id + ' div.add-fav').remove();
							$('#fav' + result.id + ' div.add-artist').remove();
							
							$(similar).fadeOut(500, function () {
							 $(similar).remove();
						 });
						  $(popular).fadeOut(500, function () {
							 $(popular).remove();
						 });
						}
					} else {
						alert('Oops, problème de sauvegarde');
					}
				}
			);
		});
		
		$('#edit-popular .add-fav a').live('click', function(){
			$('.tipsy').remove();
			$.post(
				'" . $this->webroot . "users/editArtist', 
				{ id: $(this).attr('id'), name: $(this).attr('name'), rel: $(this).attr('rel') },
				function(result) { 
					if (result) {
						result = JSON.parse(result);
						if (result.alert) {
							alert(result.alert);
						} else {
							$('.no-favorite').fadeOut();
							$('.no-artist').fadeOut();
							var similar = '#sim' + result.id;
							var popular = '#pop' + result.id;
							$(popular).clone().prependTo('#edit-artists').attr('id', 'art' + result.id);
							$('#art' + result.id + ' div.add-artist').remove();
							$('#art' + result.id + ' div.add-fav').remove();
							$('#art' + result.id + ' div.rm-artist').show();
							$('#art' + result.id + ' div.rm-fav').show();
							
							$(popular).clone().prependTo('#edit-favorites').attr('id', 'fav' + result.id);
							$('#fav' + result.id + ' div.rm-fav').show();
							$('#fav' + result.id + ' div.add-fav').remove();
							$('#fav' + result.id + ' div.add-artist').remove();
							
							$(similar).fadeOut(500, function () {
							 $(similar).remove();
						 });
						  $(popular).fadeOut(500, function () {
							 $(popular).remove();
						 });
						}
					} else {
						alert('Oops, problème de sauvegarde');
					}
				}
			);
		});
		
		$(function() {
		function log( message ) {
			$( '<div/>' ).text( message ).prependTo( '#log' );
			$( '#log' ).scrollTop( 0 );
		}

		$( '#suggest' ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: 'http://developer.echonest.com/api/v4/artist/suggest',
					dataType: 'jsonp',
					data: {
						name: request.term,
						format: 'jsonp',
						results: 5,
						api_key: 'N6E4NIOVYMTHNDM8J'
					},
					success: function( data ) {
						response( $.map( data.response.artists, function( item ) {
							return {
								label: item.name,
								value: item.name
							}
						}));
					}
				});
			},
			minLength: 2,
			select: function( event, ui ) {
				$.post(
					'" . $this->webroot . "users/editArtist', 
					{ name: ui.item.label, rel: 'add-new' },
					function(result) { 
						if (result) {
							result = JSON.parse(result);
							if (result.alert) {
								alert(result.alert);
							} else {
								$('.no-artist').fadeOut();
								
								var similar = '#sim' + result.id;
								var popular = '#pop' + result.id;
								var html = result.html;
								$('#edit-artists').prepend(result.html);
								console.info(html);
								
								$(similar).fadeOut(500, function () {
								 $(similar).remove();
							 });
								$(popular).fadeOut(500, function () {
								 $(popular).remove();
							 });
							}
						} else {
							alert('Oops, problème de sauvegarde');
						}
					}
				);
			},
			open: function() {
				$( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
			},
			close: function() {
				$( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
			}
		});
	});
		
	});
	", array('inline' => false));

?>

<div class="photo-c">
  <?php echo $html->image('user/covers/default_' . $user['User']['gender'] . '.jpg'); ?>
</div>

<div id="col" class="profil-artists">
	<h1><?php echo $this->Html->link($user['User']['login'], '/profil/' . $user['User']['login']); ?> &raquo; artistes</h1>
  <p>Editer vos artistes et choisissez vos favoris.</p><br /><br />
  
	<table width="100%">
  <tr>
  	<td width="50%">
    <h2>Ajouter un artiste</h2><br />
    <input id="suggest" value="Ex : Daft Punk" />
    </td>
    <td width="50%">
    <h2>Importer vos artistes</h2><br />
    <label for="importFb"><?php echo $this->Html->image('icons/facebook.png', array('class' => 'absmiddle')); ?> &nbsp;Avec Facebook</label><div id="importFb">
   	<?php 
		if (empty($facebook_user))
			echo $this->Facebook->login(array('perms' => 'user_likes'));
		else 
			echo $this->Html->link('&raquo; Importer', '/users/importFromFb', array('escape' => false));
		?>
    </div><br />
    <label for="importLastfm"><?php echo $this->Html->image('icons/lastfm.png', array('class' => 'absmiddle')); ?> &nbsp;Avec Last Fm</label><div id="importLastfm">
		<?php 
    if (empty($user['User']['lastfm']))
			echo $this->Html->link('&raquo; Veuillez indiquer votre compte.', '/profil/' . $user['User']['login'] . '/parametres', array('escape' => false));
		else
			echo $this->Html->link('&raquo; Importer', '/users/importFromLastfm', array('escape' => false));
    ?>
    </div>
    <br /><br /><br /><br />
    </td>
  </tr>
  <tr>
  <td>
  	<h2>Vos artistes</h2>
    <p>Les artistes que vous aimeriez voir en live.</p><br />
    <div id="edit-artists">
    	<?php
			if (!empty($artists)) { ?>
				<?php
				foreach($artists as $artist) {
					$artistsuser = $artist['ArtistsUser'];
					$artist = $artist['Artist'];
					echo '<div class="edit-artist" id="art' . $artist['id'] . '">';
					if (!empty($artist['fb_picture']))
						echo $this->Html->image($artist['fb_picture'], array('class' => 'ppic'));
					else 
						echo $this->Html->image('artist/profilepics/default.jpg', array('class' => 'ppic'));
					
					// ajouter aux favoris
					if ($artistsuser['favorite'] == 1) {
						echo '<div class="rm-fav">' .  $this->Html->link('', '#', array('rel' => 'rm-fav', 'class' => 'tooltip-top', 'title' => 'Supprimer des favoris', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
					} else {
						echo '<div class="add-fav">' .  $this->Html->link('', '#', array('rel' => 'add-fav', 'class' => 'tooltip-top', 'title' => 'Ajouter aux favoris', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
					}
					
					// supprimer
					echo '<div class="rm-artist">' .  $this->Html->link('', '#', array('rel' => 'rm-artist', 'class' => 'tooltip-top', 'title' => 'Supprimer', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
					
					echo '<div class="edit-artist-name">' . $this->Html->link($artist['name'], '/artist/' .$artist['url']) . '</div>';
					echo  '</div>';
				}
				?>
				</ul>
			<?php 
			} else { 
				echo "<p class='no-artist'>Aucun artiste suivi.</p>"; 
			}
			?>
    </div>
  </td>
  <td>
  	<h2>Vos favoris</h2>
    <p>Vous êtes prêt à tuer pour voir ces artistes en live.</p><br />
    <div id="edit-favorites">
    	<?php
			if (!empty($favorites)) { ?>
				<?php
				foreach($favorites as $artist) {
					$artist = $artist['Artist'];
					echo '<div class="edit-artist" id="fav' . $artist['id'] . '">';
					if (!empty($artist['fb_picture']))
						echo $this->Html->image($artist['fb_picture'], array('class' => 'ppic'));
					else 
						echo $this->Html->image('artist/profilepics/default.jpg', array('class' => 'ppic'));
					
					// supprimer des favoris
					echo '<div class="rm-fav">' .  $this->Html->link('', '#', array('rel' => 'rm-fav', 'class' => 'tooltip-top', 'title' => 'Supprimer des favoris', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
					
					echo '<div class="edit-artist-name">' . $this->Html->link($artist['name'], '/artist/' .$artist['url']) . '</div>';
					echo  '</div>';
				}
				?>
				</ul>
			<?php 
			} else { 
				echo "<p class='no-favorite'>Aucun favori.</p>"; 
			}
			?>
    </div>
  </td>

  </tr>
  </table>
  <br />
</div>

<div id="sidebar" class="profil">
	<div id="edit-similar" class="sidebar_block">
		<h2>artistes similaires</h2>
    <?php
			if (!empty($similar_artists)) { ?>
				<?php
				foreach($similar_artists as $artist) {
					$artist = $artist['Artist'];
					echo '<div class="edit-artist" id="sim' . $artist['id'] . '">';
					if (!empty($artist['fb_picture']))
						echo $this->Html->image($artist['fb_picture'], array('class' => 'ppic'));
					else 
						echo $this->Html->image('artist/profilepics/default.jpg', array('class' => 'ppic'));
					
					// ajouter aux favoris
						echo '<div class="add-fav">' .  $this->Html->link('', '#', array('rel' => 'add-fav', 'class' => 'tooltip-top', 'title' => 'Ajouter aux favoris', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
						
					// supprimer favoris // caché
					echo '<div class="rm-fav" style="display:none;">' .  $this->Html->link('', '#', array('rel' => 'rm-fav', 'class' => 'tooltip-top', 'title' => 'Supprimer des favoris', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
					
					// ajouter
					echo '<div class="add-artist">' .  $this->Html->link('', '#', array('rel' => 'add-artist', 'class' => 'tooltip-top', 'title' => 'Ajouter', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
					
					// supprimer caché
					echo '<div class="rm-artist" style="display:none;">' .  $this->Html->link('', '#', array('rel' => 'rm-artist', 'class' => 'tooltip-top', 'title' => 'Supprimer', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
					
					echo '<div class="edit-artist-name">' . $this->Html->link($artist['name'], '/artist/' .$artist['url']) . '</div>';
					echo  '</div>';
				}
				?>
				</ul>
			<?php 
			} else { 
				echo "<p>Aucun artiste similaire.</p>"; 
			}
			?>
  </div>
  
  <div id="edit-popular" class="sidebar_block">
		<h2>artistes populaires</h2>
    <!--<p>Cliquez pour les ajouter à votre liste d'artistes.</p>-->
    <?php
			if (!empty($popular_artists)) { ?>
				<?php
				foreach($popular_artists as $artist) {
					$artist = $artist['Artist'];
					echo '<div class="edit-artist" id="pop' . $artist['id'] . '">';
					if (!empty($artist['fb_picture']))
						echo $this->Html->image($artist['fb_picture'], array('class' => 'ppic'));
					else 
						echo $this->Html->image('artist/profilepics/default.jpg', array('class' => 'ppic'));
					
					// ajouter aux favoris
						echo '<div class="add-fav">' .  $this->Html->link('', '#', array('rel' => 'add-fav', 'class' => 'tooltip-top', 'title' => 'Ajouter aux favoris', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
						
					// supprimer favoris // caché
					echo '<div class="rm-fav" style="display:none;">' .  $this->Html->link('', '#', array('rel' => 'rm-fav', 'class' => 'tooltip-top', 'title' => 'Supprimer des favoris', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
					
					// ajouter
					echo '<div class="add-artist">' .  $this->Html->link('', '#', array('rel' => 'add-artist', 'class' => 'tooltip-top', 'title' => 'Ajouter', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
					
					// supprimer caché
					echo '<div class="rm-artist" style="display:none;">' .  $this->Html->link('', '#', array('rel' => 'rm-artist', 'class' => 'tooltip-top', 'title' => 'Supprimer', 'escape' => false, 'onclick' => 'return false;', 'name' => $artist['id'])) . '</div>';
					
					echo '<div class="edit-artist-name">' . $this->Html->link($artist['name'], '/artist/' .$artist['url']) . '</div>';
					echo  '</div>';
				}
				?>
				</ul>
			<?php 
			} else { 
				echo "<p>Aucun artiste suivi.</p>"; 
			}
			?>
  </div>

</div>