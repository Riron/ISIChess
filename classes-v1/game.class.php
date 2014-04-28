<?php
class Game {
	public $board;
	public $html;
	
	function __construct($etat){
		require(ROOT.'classes/board.class.php');
		$this->board = new Board($etat);
		$this->html = $this->board->board();

	}

    /**
    *
    */
    function jouerCoup($data){
    	if($this->checkCoup($data['startX'], $data['startY'], $data['endX'], $data['endY'])){
            $this->move($data['startX'], $data['startY'], $data['endX'], $data['endY']);
            return true;
        }
        return false;
    }

    /**
    *
    */
    function checkCoup($start, $end){
    	// $square = $this->board->square();
    	// $this->getCoups($square[$start.x][$start.y]);
        return true;
    }

    /**
    *
    */
    function move($startX, $startY, $endX, $endY){
        $this->board->setSquare($endX, $endY, $this->board->square($startX, $startY));
        $this->board->setSquare($startX, $startY, null);
        //$this->board->buildBoard(null);
    }

    /**
    * Reconstruit le plateau de jeu
    * @param $etat Etat du jeu (code FEN)
    */
    function remakeBoard($etat){
        $this->board->buildBoard($etat);
        $this->html = $this->board->board();
    }
}