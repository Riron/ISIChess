<div class="page-header">
  <h1>Bibliothèque <small>Visionnez les parties déjà jouées</small></h1>
</div>
<table class="table table-bordered table-striped">
	<thead>
    <tr>
      <th>#</th>
      <th>Joueur 1</th>
      <th>Joueur 2</th>
      <th>Date de fin</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($parties as $k => $v) {
  echo '<tr>';
	echo '<td>'.$v->id_partie.'</td>';
	echo '<td>'.$jB[$k]->login.'</td>';
	echo '<td>'.$jN[$k]->login.'</td>';
	echo '<td>'.(isset($v->dat_fin) ? $v->dat_fin : 'En cours').'</td>';
  echo '<td><a href="'.$this->generateUrl('chess', 'view', $v->id_partie).'" alt="Visionner"><i class="icon-play"></i></a></td>';
  echo '</tr>';
}
?>
	</tr>
</table>
