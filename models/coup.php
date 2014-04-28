<?php
class Coup extends model{

	public $table = 'coup';

	function getEtat($id){
		$etat = $this->findFirst(array(
			'champs' => array('cod_fen'),
			'conditions' => array('id_partie' => $id),
			'order' => array('champ' => 'num_coup', 'sens' => 'DESC')
		));
		if(!empty($etat))
			return $etat->cod_fen;
	}
	
}