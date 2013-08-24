<?php

class TicTacToe{
	
	public function drawBoard($board){
		//$board is a list of 10 strings representing the board (ignore index 0)
		echo ' ||';
		echo ' ' . $board[7]. ' | ' . $board[8] . ' | ' . $board[9];
		echo ' ||'.PHP_EOL;
		echo '--------------'.PHP_EOL;
		echo ' ||';
		echo ' ' . $board[4]. ' | ' . $board[5] . ' | ' . $board[6];
		echo ' ||'.PHP_EOL;
		echo '--------------'.PHP_EOL;
		echo ' ||';
		echo ' ' . $board[1]. ' | ' . $board[2] . ' | ' . $board[3];
		echo ' ||'.PHP_EOL;
	}
	
	public function inputPlayerLetter(){
		//Let the player choose the letter they want to be
		//Return a list with the player's letter as the first item, and the computer's letter as the second
		$letter = '';
		while ($letter !== 'X' || $letter !== 'O'){
			echo 'Do you want to be X or O? ';
			$letter = strtoupper(fgets(STDIN));
			if(substr($letter, 0, 1) === 'O'){
				return array(0 => 'O', 1 => 'X');
			}
			else{
				return array(0 => 'X', 1 => 'O');
			}
		}
	}
	
	public function whoGoesFirst(){
		if(rand(0,1) == 0){
			return 'computer';
		}
		else{
			return 'player';
		}
	}
	
	public function playAgain(){
		echo 'Do you want to play again? (yes or no) ';
		$answer = strtoupper(fgets(STDIN));
		$answer = substr($answer, 0, 1);
		if($answer[0] == 'Y'){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function makeMove(& $board, $letter, $move){
		$board[$move] = $letter;
	}
	
	public function isWinner($bo, $le){
		if($bo[7] == $le && $bo[8] == $le && $bo[9] == $le){ //across the top
			return true;
		}elseif($bo[4] == $le && $bo[5] == $le && $bo[6] == $le){ //across the middle
			return true;
		}elseif($bo[1] == $le && $bo[2] == $le && $bo[3] == $le){ //across the bottom
			return true;
		}elseif($bo[7] == $le && $bo[4] == $le && $bo[1] == $le){ //down the left side
			return true;
		}elseif($bo[8] == $le && $bo[5] == $le && $bo[2] == $le){ //down the middle
			return true;
		}elseif($bo[9] == $le && $bo[6] == $le && $bo[3] == $le){ //down the right side
			return true;
		}elseif($bo[7] == $le && $bo[5] == $le && $bo[3] == $le){ //diagonal
			return true;
		}elseif($bo[9] == $le && $bo[5] == $le && $bo[1] == $le){ //diagonal
			return true;
		}else{
			return false;
		}
	}
	
	public function getBoardCopy($board){
		$dupeBoard = array();
		for($i = 0; $i < count($board); $i++){
			$dupeBoard[] = $board[$i];
		}
		return $dupeBoard;
	}
	
	public function isSpaceFree($board, $move){
		//return true if the passed move is free in the passed board
		if(isset($board[$move])){
			if($board[$move] == ' '){
				return true;
			}else{
				return false;
			}
		}
	}
	
	public function getPlayerMove($board){
		$move = ' ';
		$min = 1;
		$max = 9;
		
		while (!filter_var($move, FILTER_VALIDATE_INT, array("options"=>array("min_range"=>$min, "max_range"=>$max))) || !$this->isSpaceFree($board, intval($move))){
			echo 'What is your next move? (1-9) ';
			$move = fgets(STDIN);
		}
		return intval($move);
	}
	
	public function chooseRandomMoveFromList($board, $movesList){
		//returns a valid move from the passed list on the passed board.
		//return null if there is no valid move.
		$possibleMoves = array();
		for($i = 1; $i < count($board); $i++){
			if($this->isSpaceFree($board, $i)){
				$possibleMoves[] = $board[$i];
			}
		}
		
		if(count($possibleMoves) != 0){
			return array_rand($possibleMoves);
		}else{
			return null;
		}
	}
	
	public function getComputerMove($board, $computerLetter){
		//given a board and the computer letter, determine where to move and return that move
		if($computerLetter === 'X'){
			$playerLetter = 'O';
		}else{
			$playerLetter = 'X';
		}
		
		//Here is the algorithm for the Tic Tac Toe AI
		//First, check if we can win in the next move
		for($i = 1; $i < 10; $i++){
			$copy = $this->getBoardCopy($board);
			if($this->isSpaceFree($copy, $i)){
				$this->makeMove($copy, $computerLetter, $i);
				if($this->isWinner($copy, $computerLetter)){
					return $i;
				}
			}
		}
		
		//check if the player could win on his next move, and block them
		for($i = 1; $i < 10; $i++){
			$copy = $this->getBoardCopy($board);
			if($this->isSpaceFree($copy, $i)){
				$this->makeMove($copy, $playerLetter, $i);
				if($this->isWinner($copy, $playerLetter)){
					return $i;
				}
			}
		}
		
		//try to take one of the corners, if it is free
		$corners = array(1,3,7,9);
		$move = $this->chooseRandomMoveFromList($board, $corners);
		if($move != null){
			return $move;
		}
		
		//try to take the center, if it is free
		if($this->isSpaceFree($board, 5)){
			return 5;
		}
		
		//move on one of the sides
		$sides = array(2,4,6,8);
		return $this->chooseRandomMoveFromList($board, $sides);
	}
	
	public function isBoardFull($board){
		//return true if every space on the board has been taken. Otherwise return false.
		for($i = 1; $i < 10; $i++){
			if ($this->isSpaceFree($board, $i)) {
				return false;
			}
		}
		return true;
	}
	
}


//NOW! LET'S PLAY
$tictac = new TicTacToe();

echo "Let's play Tic Tac Toe!\n\n";

while (true) {
	//Reset the board
	$theBoard = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');
	
	//ask letter of player, X or O
	$letters = $tictac->inputPlayerLetter();
	$playerLetter = $letters[0];
	$computerLetter = $letters[1];
	
	$turn = $tictac->whoGoesFirst();
	echo "The $turn will go first\n";
	
	$gameIsPlaying = true;
	
	while($gameIsPlaying){
		if($turn === 'player'){
			//Player's turn
			$tictac->drawBoard($theBoard);
			$move = $tictac->getPlayerMove($theBoard);
			$tictac->makeMove($theBoard, $playerLetter, $move);
			
			if($tictac->isWinner($theBoard, $playerLetter)){
				$tictac->drawBoard($theBoard);
				echo 'Hooray! You have won the game!'."\n";
				$gameIsPlaying = false;
			}else{
				if($tictac->isBoardFull($theBoard)){
					$tictac->drawBoard($theBoard);
					echo 'The game is a tie!'."\n";
					break;
				}else{
					$turn = 'computer';
				}
			}
		}else{
			//Computer's turn
			$move = $tictac->getComputerMove($theBoard, $computerLetter);
			$tictac->makeMove($theBoard, $computerLetter, $move);
			
			if($tictac->isWinner($theBoard, $computerLetter)){
				$tictac->drawBoard($theBoard);
				echo 'The computer has beaten you! You lose.'."\n";
				$gameIsPlaying = false;
			}else{
				if($tictac->isBoardFull($theBoard)){
					$tictac->drawBoard($theBoard);
					echo 'The game is a tie!'."\n";
					break;
				}
				else{
					$turn = 'player';
				}
			}
		}
	}
	
	if(!$tictac->playAgain()){
		break;
	}
}

?>
