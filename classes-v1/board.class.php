<?php
class Board {
    private $board = array();
    private $square = array();

    function __construct($etat){
    	$this->buildBoard($etat);
    }

    /**
    * Permet de construire un plateau de jeu vide
    * @param $etat Etat de la partie en cours (code FEN)
    */
    function buildBoard($etat){
        if($etat == null){
            $etat = 'tcfdrfct/pppppppp/8/8/8/8/PPPPPPPP/TCFDRFCT';
        }
        $this->parseEtat($etat);

        $string = array(
            "0" => "",
            "1" => "A",
            "2" => "B",
            "3" => "C",
            "4" => "D",
            "5" => "E",
            "6" => "F",
            "7" => "G",
            "8" => "H"
        );

        $html = '<table class="echiquier">';
        for($y = 0; $y < 9; $y++) {
            $color = $y % 2 ? 'white' : 'black';
            $html .= '<tr>';
            for($x = 0; $x < 9;$x++) {
                        if($y == 8){
                            $html .= '<td><strong>'.$string[$x].'</strong></td>';
                        }
                        else if($x%9 == 0){
                            $html .= '<td><strong>'.(8-$y).'</strong></td>';
                        }
                        else{
                            $html .= '<td class="'.$color.'">';
                            $html .= isset($this->square[$x][$y]) ? $this->image($this->square[$x][$y]) : '';
                            $html .= '</td>';
                            $color = ($color == 'white') ? 'black' : 'white';
                        }
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        $this->board = $html;
    }

    /**
    * Permet de parser l'etat d'une partie enregistree au format FEN et remplit la varaible $square
    * @param $etat code Fen de l'etat de la partie
    */
    function parseEtat($etat){
        $pieces = array(
            't' => 'rook',
            'c' => 'knight',
            'f' => 'bishop',
            'd' => 'queen',
            'r' => 'king',
            'p' => 'pawn'

        );

        $positions = explode("/", $etat);
        foreach ($positions as $k => $v) {
            $j = 0;
            for($p=0; $p<strlen($v); $p++){
                $chr = substr($v, $p, 1);
                if(is_numeric($chr)){
                    for($i=0; $i<$chr; $i++) {
                        $this->square[$j+1][$k] = null;
                        $j++;
                    }
                }
                else {
                    $this->square[$j+1][$k] = $chr;
                    $j++;
                }
            }
        }
    }

    /**
    * Permet de creer l'image d'une pieve
    * @param $piece La piece dont on veut l'image
    */
    function image($piece){
        $pieces = array(
            't' => 'rook',
            'c' => 'knight',
            'f' => 'bishop',
            'd' => 'queen',
            'r' => 'king',
            'p' => 'pawn'
        );

        if(strtolower($piece) == $piece){
            return '<img src="'.WEBROOT.'webroot/img/pieces/black_'.$pieces[strtolower($piece)].'.gif" alt="Black '.$pieces[strtolower($piece)].'">';
        }
        else{
            return '<img src="'.WEBROOT.'webroot/img/pieces/white_'.$pieces[strtolower($piece)].'.gif" alt="White '.$pieces[strtolower($piece)].'">';
        }

    }

    /**
    * Permet de generer l'etat du jeu sous forme de code FEN
    * @return Le code FEN
    */
    function toFen(){
        $fen = array();
        $tmp = 0;
        //debug($this->square);
        for($i=0; $i<8; $i++){    
            foreach ($this->square as $k => $v) {
                if($v[$i]==null){
                    $tmp++;
                }
                else{
                    if($tmp != 0){
                        if(!isset($fen[$i])){
                            $fen = $fen + array($i => $tmp);
                        }
                        else{
                            $fen[$i] .= $tmp;
                        }
                        $tmp = 0;
                    }
                    if(!isset($fen[$i])){
                            $fen = $fen + array($i => $v[$i]);
                    }
                    else{
                        $fen[$i] .= $v[$i];
                    }   
                }
            }
            if($tmp > 0){
                if(!isset($fen[$i])){
                    $fen = $fen + array($i => $tmp);
                }
                else{
                    $fen[$i] .= $tmp;
                }
                $tmp = 0;
            }
            $tmp = 0;
        }
        return implode('/',$fen);
    }

    /**
    * Accesseur pour $board
    * @return Board::board
    */
    function board(){
        return $this->board;
    }

    /**
    * Acccesseur pour $square
    * @return Board::square
    */
    function square($x = null, $y = null){
        if($x== null && $y == null){
            return $this->square;
        }
        else{
            return $this->square[$x][$y];
        }
        
    }

    /**
    * Setter pour $square
    * @param $x abscisse du tableau $square
    * @param $y ordonnee du tableau $square
    * @param $value valeur a attribuer à $square[$x][$y]
    */
    function setSquare($x, $y, $value){
        $this->square[$x][$y] = $value;
    }
}