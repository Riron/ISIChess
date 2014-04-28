<?php 
class Model {

	static $connection = array();
	private $db = 'default';
	protected $table;
	protected $pdo;
	public static $compteur;

	function __construct(){
		$conf = Config::$databases[$this->db];
		if(isset(Model::$connection[$this->db])){
			return true;
		}
		try{
			$this->pdo = new PDO('mysql: host='.$conf['host'].';dbname='.$conf['database'], $conf['login'], $conf['password']);
			Model::$connection[$this->db] = $this->pdo;
		}
		catch(PDOException $e){
			debug($e->getMessage());
			die('Impossible de se connecter a la db');
		}
	}

	/**
	* Permet de rechercher des informations dans la BDD
	* @param $req les parametres de la requete
	* @return le resultat de la requete
	*/
	function find($req){
		$sql = 'SELECT ';
		// Quels champs selectionner
		if(isset($req['champs'])){
			foreach ($req['champs'] as $k => $v) {
				$champs[] = $v;
			}
			$sql .= implode(', ', $champs);
		}
		else{
			$sql .= ' * ';
		}
		$sql .= ' FROM '.$this->table.' ';
		// Conditions de la requete (WHERE)
		if(isset($req['conditions'])){
			if(is_array($req['conditions'])){
				$sql .= 'WHERE ';
				$cond = array();
				foreach ($req['conditions'] as $k => $v) {
					if($k != 'or' && $v != 'IS NULL'){
						$cond[] = $k .'='.Model::$connection[$this->db]->quote($v);
					}
					if($v == 'IS NULL'){
						$cond[] = $k .' '.$v;
					}
				}
				// Si conditions avec OR
				if(isset($req['conditions']['or'])){
					$condOr = array();
					foreach ($req['conditions']['or'] as $l => $w) {
						$condOr[] = $l .'='.Model::$connection[$this->db]->quote($w);
					}
					$or = '( '.implode('OR ',$condOr).' )';
					$cond[] = $or;
				}
				$sql .= implode(' AND ',$cond);
			}
			else{
				$sql .= 'WHERE '.$req['conditions'];
			}
		}

		//Order by
		if(isset($req['order'])){
			$sql .= ' ORDER BY '.$req['order']['champ'].' '.$req['order']['sens'];
		}
		$pre = Model::$connection[$this->db]->prepare($sql);
		$pre->execute();

		//On incrémente le compteur de requetes
		Model::$compteur++;
		//On retourne le resultat de la requete sous forme d'objet (FETCH_OBJ)
		return $pre->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	* Permet de rechercher le premier element d'un find, renvoie directement le resultat et 
	* pas le resultat contenu dans l'indice 0 d'un tableau
	* @param $req les parametres de la requete
	* @return le resultat de la requete
	*/
	function findFirst($req){
		return current($this->find($req));
	}

	/**
	* Permet d'inserer un element dans la BDD
	* @param $req les parametres de la requete
	*/
	function insert($req){
		$sql = 'INSERT INTO '.$this->table.' ('; 
		$champs = array();
		foreach ($req['champs'] as $k => $v) {
			$champs[] = $k;
		}
		$sql .= implode(', ',$champs);
		$sql .= ') VALUES ( :';
		$sql .= implode(', :', $champs).' )';
		
		$pre = Model::$connection[$this->db]->prepare($sql);
		
		foreach ($req['champs'] as $k => &$v) {
			$pre->bindParam(':'.$k, $v);
		}

		$pre->execute();
		//On incrémente le compteur de requetes
		Model::$compteur++;

		return Model::$connection[$this->db]->lastInsertId();

	}

	/**
	* Permet de réaliser une jointure interne
	* @param les parametres de la requete
	*/
	function innerJoin($req){
		$sql = 'SELECT ';
		// Quels champs selectionner
		if(isset($req['champs'])){
			foreach ($req['champs'] as $k => $v) {
				$champs[] = $v;
			}
			$sql .= implode(', ', $champs);
		}
		else{
			$sql .= ' * ';
		}

		$sql .= ' FROM '.$this->table.' ';
		$sql .= 'INNER JOIN '.$req['tableEtrangere'].' ';
		$sql .= 'ON '.$req['jointure'];

		// Conditions de la requete (WHERE)
		if(isset($req['conditions'])){
			if(is_array($req['conditions'])){
				$sql .= ' WHERE ';
				$cond = array();
				foreach ($req['conditions'] as $k => $v) {
					if($k != 'or' && $v != 'IS NULL'){
						$cond[] = $k .'='.Model::$connection[$this->db]->quote($v);
					}
					if($v == 'IS NULL'){
						$cond[] = $k .' '.$v;
					}
				}
				// Si conditions avec OR
				if(isset($req['conditions']['or'])){
					$condOr = array();
					foreach ($req['conditions']['or'] as $l => $w) {
						$condOr[] = $l .'='.Model::$connection[$this->db]->quote($w);
					}
					$or = '( '.implode('OR ',$condOr).' )';
					$cond[] = $or;
				}
				$sql .= implode(' AND ',$cond);
			}
			else{
				$sql .= 'WHERE '.$req['conditions'];
			}
		}

		$pre = Model::$connection[$this->db]->prepare($sql);
		$pre->execute();

		//On incrémente le compteur de requetes
		Model::$compteur++;
		//On retourne le resultat de la requete sous forme d'objet (FETCH_OBJ)
		return $pre->fetchAll(PDO::FETCH_OBJ);

	}

	/**
	* Permet de realiser une requete de type UPDATE 
	*/
	function update($req){
		$sql = 'UPDATE '. $this->table.' SET ';
		foreach ($req['champs'] as $k => $v) {
			//Pour certaines requetes nécéssité de ne pas mettre de quote (ex: incrémentation)
			if(isset($req['champs']['noQuote'])){
				if($k != 'noQuote')
					$champs[] = $k.' = '.$v;
			}
			else
				$champs[] = $k.' = '.Model::$connection[$this->db]->quote($v);
				
		}
		$sql .= implode(', ', $champs);

		$sql .= ' WHERE ';
		if(is_array($req['conditions'])){
			$cond = array();
			foreach ($req['conditions'] as $k => $v) {
				$cond[] = $k .'='.Model::$connection[$this->db]->quote($v);
			}
			$sql .= implode('AND ',$cond);
		}
		else{
			$sql .= $req['conditions'];
		}
		
		$pre = Model::$connection[$this->db]->prepare($sql);
		$pre->execute();
		
		//On incrémente le compteur de requetes
		Model::$compteur++;

	}

	/**
	*
	*/
	function delete($req){
		$sql = 'DELETE FROM '.$this->table.' WHERE ';
		if(is_array($req['conditions'])){
			$cond = array();
			foreach ($req['conditions'] as $k => $v) {
				$cond[] = $k .'='.Model::$connection[$this->db]->quote($v);
			}
			$sql .= implode('AND ',$cond);
		}
		else{
			$sql .= $req['conditions'];
		}

		$pre = Model::$connection[$this->db]->prepare($sql);
		$pre->execute();

		
		//On incrémente le compteur de requetes
		Model::$compteur++;

	}
}