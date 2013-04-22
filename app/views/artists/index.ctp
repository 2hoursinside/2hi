<?php $this->set('title_for_layout', 'Artistes'); ?>

<div class="photo-c">
</div>

<div class="wrapper">

  <div id="col" class="artists-index">
  	<h1>les artistes</h1><br />
    
  
    <h2>mainstream shit</h2>
    <p>ils sont partout, ils ont un nouveau album à vendre</p><br />
    <ul>
    <?php //debug($artists_mainstream);
   	foreach ($artists_mainstream as $artist) {
   		echo '<li>' . $html->link($artist['Artist']['name'], '/artist/' . $artist['Artist']['url']) . ', ' . $artist[0]['nbeditions'] . ' éditions</li>';
  	}
  	?>
  	</ul>
  	<br />
  	
  	<h2>le retour des superstars</h2>
  	<p>on les croyait finis, ils tentent un comeback</p><br />
    <ul>
    <?php
   	foreach ($artists_superstars as $artist) {
   		echo '<li>' . $html->link($artist['Artist']['name'], '/artist/' . $artist['Artist']['url']) . '</li>';
  	}
  	?>
  	</ul>
  	<br />
  	
  	<h2>assez hot en ce moment</h2>
  	<p>ils sont hype, mais plus pour longtemps</p><br />
    <ul>
    <?php
   	foreach ($artists_hype as $artist) {
   		echo '<li>' . $html->link($artist['Artist']['name'], '/artist/' . $artist['Artist']['url']) . '</li>';
  	}
  	?>
  	</ul>
  	<br />
  	
  	<h2>nouveaux nés</h2>
  	<p>les peut-être futurs stars</p><br />
    <ul>
    <?php
   	foreach ($artists_new as $artist) {
   		echo '<li>' . $html->link($artist['Artist']['name'], '/artist/' . $artist['Artist']['url']) . '</li>';
  	}
  	?>
  	</ul>
  
  </div>
  
  
  <div id="sidebar">
  </div>

</div>