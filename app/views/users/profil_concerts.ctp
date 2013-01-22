<?php $this->set('title_for_layout', 'Editer vos concerts');  

echo $this->Html->scriptBlock("
  $(document).ready(function() {
  
    $('#suggest').bind('focus', function(){
			if(this.value == 'Qui ? Ex : Daft Punk')
				this.value = '';
		});
		
		$('#suggest').bind('blur', function(){
			if(this.value == '')
				this.value = 'Qui ? Ex : Daft Punk';
		});
		
		$('#place').bind('focus', function(){
			if(this.value == 'Où ? Ex : Olympia')
				this.value = '';
		});
		
		$('#place').bind('blur', function(){
			if(this.value == '')
				this.value = 'Où ? Ex : Olympia';
		});
		
		$('#date').bind('focus', function(){
			if(this.value == 'Quand ? Ex : 3 Juin 2012')
				this.value = '';
		});
		
		$('#date').bind('blur', function(){
			if(this.value == '')
				this.value = 'Quand ? Ex : 3 Juin 2012';
		});
		
		$('#date').datepicker({
			changeMonth: true,
			changeYear: true
		});
		
		
		
		$('#suggest').autocomplete({
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
			select: function( event, ui ) {},
			open: function() {
				$( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
			},
			close: function() {
				$( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
			}
		});
		
		/**
		$('#place').autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: 'https://api.foursquare.com/v2/venues/suggestCompletion',
					dataType: 'jsonp',
					data: {
						query: request.term,
						ll: '48.5,2.2',
						v: 20120725,
						limit: 5,
						client_id: 'EQDCWNUFCTHEMHNO4I530YIJCM5EW5PY2NV1B24BA5Q5VHQD',
						client_secret: 'LX32K5V051CF5WDFI04KESUFZGUGAKK4AY0F2GDYS00435NJ'
					},
					success: function( data ) {
						response( $.map( data.response.minivenues, function( item ) {
							return {
								label: item.name,
								value: item.name
							}
						}));
					}
				});
			},
			minLength: 2,
			select: function( event, ui ) {},
			open: function() {
				$( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
			},
			close: function() {
				$( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
			}
		});
		*/
		
		$('#place').autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=olympia&language=fr&sensor=false&location=48.5,2.2&key=AIzaSyAQZfdQWlIXofkcJCrKCiU7DpL8BY3Yi94',
					dataType: 'jsonp',
					data: {
						query: request.term,
						ll: '48.5,2.2',
						v: 20120725,
						limit: 5,
						client_id: 'EQDCWNUFCTHEMHNO4I530YIJCM5EW5PY2NV1B24BA5Q5VHQD',
						client_secret: 'LX32K5V051CF5WDFI04KESUFZGUGAKK4AY0F2GDYS00435NJ'
					},
					success: function( data ) {
						response( $.map( data.response.minivenues, function( item ) {
							return {
								label: item.name,
								value: item.name
							}
						}));
					}
				});
			},
			minLength: 2,
			select: function( event, ui ) {},
			open: function() {
				$( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
			},
			close: function() {
				$( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
			}
		});
		
		
		$('#addConcertForm').submit(function() {
			var artist_name = $('#suggest').attr('value');
			var place_name = $('#place').attr('value');
			var date = $('#date').attr('value');
			console.info(artist_name);
			$.post(
				'" . $this->webroot . "concerts/add',
				{ artist_name: artist_name, place_name: place_name, date: date },
				function(result) { 
					if (result) {
						console.info(result);
					} else {
						alert('Oops, problème de sauvegarde');
					}
				}
			);
		
			return false;
		});
  });
  
", array('inline' => false));

?>

<div class="photo-c">
  <?php echo $html->image('user/covers/default_' . $user['User']['gender'] . '.jpg'); ?>
</div>

<div id="col" class="profil-artists">
  <div id="edit-concerts">
  
	<h1><?php echo $this->Html->link($user['User']['login'], '/profil/' . $user['User']['login']); ?> &raquo; concerts</h1>
  <p>C'est ici que vous allez vous créer votre journal de concerts, et y renseigner tous les artistes que vous avez vus.</p><br /><br />
  
  <table width="100%">
  <tr>
  <td width="50%">
    <h2>Ajouter un concert</h2>
    <p>Indiquez tous vos précédents concerts.</p><br />
    <?php 
		echo ($form->create('Concert', array('id' => 'addConcertForm'))); 
		echo $form->input('artist_name', array('label' => false, 'id' => 'suggest', 'value' => 'Qui ? Ex : Daft Punk'));
		echo $form->input('place_name', array('label' => false, 'id' => 'place', 'value' => 'Où ? Ex : Olympia'));
		echo $form->input('date', array('label' => false, 'id' => 'date', 'value' => 'Quand ? Ex : 3 Juin 2012')) . '<br />'; ?>
    <button class="button save" type="submit">Ajouter</button>
    </form>
    <br /><br /><br /><br />
  </td>
  
  <td width="50%">
    <h2>Compléter vos festivals</h2>
    <p>Quels artistes vous avez vus lors de vos festivals ?</p><br />
    
    <?php
    
    if (!empty($seen_editions)) {
      echo '<ul class="list-concerts">';
      foreach($seen_editions as $edition) {
        $ts_start = strtotime($edition['Edition']['date_start']);
        echo '<li>';
        echo $this->Html->image('festival/profilepics/thumb.festival.' . $edition['Edition']['Festival']['photo_r'], array('class' => 'smallppic headline'));
        echo '<h3>' . $edition['Edition']['Festival']['name'] . ' ' . strftime("%Y", $ts_start) . '</h3>';
        $nb_artists = 0;
        echo '<span>' . $nb_artists . ' artistes vus</span>';
        echo '</li>';
      }
      echo '</ul>';
    } else {
      echo "Vous n'avez pas encore assisté à de festivals.";
    }
    ?>
  </td>
  </tr>
  </table>
  <?php // debug($seen_editions); ?>
  <br />
  
  </div>
</div>

<div id="sidebar" class="profil">
	<div id="last-concerts" class="sidebar_block">
		<h2>derniers concerts</h2>
	</div>
</div>

