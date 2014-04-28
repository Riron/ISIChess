<?php
class Users extends Model {
	public $table = 'utilisateur';

	function findAdversaire($id_adversaire){
		return $this->findFirst(array(
				'champs' => array('login'),
				'conditions' => array('id_utilisateur' => $id_adversaire)
		))->login;
	}
	
}