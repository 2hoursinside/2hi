<div>
  <form>
    <select id="crit">
      <option value="top50artist">top 50 des artistes</option>
      <option value="top10city">top 10 des villes</option>
      <option value="top50age">utilisateurs par age</option>
      <option value="rep_gender">repartition homme / femme</option>
    </select>
  </form>
</div>
<div id="resultats" style="margin-top:100px;">
  <table id="top50artist" style="border:solid, 1px, black;width: 400px; margin:auto;">
    <thead>
      <tr>
        <td style="width: 200px">Artiste</td><td>Nombre d'utilisateur l'appreciant</td>
      </tr>
    </thead>
    <tbody>
<?php
foreach($top50_artist as $artist){
  echo '<tr><td>'.$artist['a']['name'].'</td><td>'.$artist[0]['liked'].'</td></tr>';
}
?>
    </tbody>
  </table>
  
  <table id="top10city" style="border:solid, 1px, black;width: 400px; margin:auto; display:none;">
    <thead>
      <tr>
        <td style="width: 200px">Ville</td><td>Nombre d'utilisateur y habitant</td>
      </tr>
    </thead>
    <tbody>
<?php
foreach($top10_city as $city){
  echo '<tr><td>'.$city['ct']['name'].'</td><td>'.$city[0]['nb_user'].'</td></tr>';
}
?>
    </tbody>
  </table>
  
  <table id="top50age" style="border:solid, 1px, black;width: 400px; margin:auto; display:none;">
    <thead>
      <tr>
        <td style="width: 200px">Age</td><td>Nombre d'utilisateur</td>
      </tr>
    </thead>
    <tbody>
<?php
foreach($top50_age as $age){
  echo '<tr><td>'.$age[0]['age'].' ans</td><td>'.$age[0]['nb_user'].'</td></tr>';
}
?>
    </tbody>
  </table>
  
  <table id="rep_gender" style="border:solid, 1px, black;width: 400px; margin:auto; display:none;">
    <thead>
      <tr>
        <td style="width: 200px">Genre</td><td>Nombre d'utilisateur</td>
      </tr>
    </thead>
    <tbody>
<?php
foreach($rep_gender as $gender){
  echo '<tr><td>'.$gender['u']['gender'].'</td><td>'.$gender[0]['nb_user'].'</td></tr>';
}
?>
    </tbody>
  </table>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('#crit').bind('change', function(){
    var table = $('#crit option:selected').val();
    console.log(table);
    $('table').hide()
    $('#'+table).show();
  })
});
</script>