<?php
class Rating{
	private $host  = 'localhost';
    private $user  = 'root';
    private $password   = "";
    private $database  = "edenbookstore";    
	private $itemUsersTable = 'users';
	private $itemTable = 'books';	
    private $itemRatingTable = 'item_rating';
	private $dbConnect = false;
    public function __construct(){
        if(!$this->dbConnect){ 
            $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
    }
	private function getData($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[]=$row;            
		}
		return $data;
	}
	private function getNumRows($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}	
	
	public function userLogin($email, $password){
		$sqlQuery = "
			SELECT id, email
			FROM ".$this->itemUsersTable." 
			WHERE email=? AND password=?";
			
		try {
			$stmt = $this->dbConnect->prepare($sqlQuery);
			if (!$stmt) {
				throw new Exception($this->dbConnect->error);
			}
			$stmt->bind_param("ss", $email, $password);
			$stmt->execute();
			$result = $stmt->get_result();
			
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;            
			}
			return $data;
		} catch (Exception $e) {
			echo 'Error: ' . $e->getMessage();
			return array(); // Return an empty array in case of an error
		}
	}
	
	
	
	
	
		
	public function getItemList(){
		$sqlQuery = "
			SELECT * FROM ".$this->itemTable;
		return  $this->getData($sqlQuery);	
	}
	public function getItem($books){
		$sqlQuery = "
			SELECT * FROM ".$this->itemTable."
			WHERE id='".$books."'";
		return  $this->getData($sqlQuery);	
	}
	public function getItemRating($books){
		$sqlQuery = "
			SELECT r.ratingId, r.books, r.users, r.ratingNumber, r.title, r.comments, r.created, r.modified
			FROM ".$this->itemRatingTable." as r
			LEFT JOIN ".$this->itemUsersTable." as u ON (r.users = r.users)
			WHERE r.books = '".$books."'";
		return  $this->getData($sqlQuery);		
	}
	public function getRatingAverage($books){
		$itemRating = $this->getItemRating($books);
		$ratingNumber = 0;
		$count = 0;		
		foreach($itemRating as $itemRatingDetails){
			$ratingNumber+= $itemRatingDetails['ratingNumber'];
			$count += 1;			
		}
		$average = 0;
		if($ratingNumber && $count) {
			$average = $ratingNumber/$count;
		}
		return $average;	
	}
	public function saveRating($POST, $userID){		
		$insertRating = "INSERT INTO ".$this->itemRatingTable." (books, users, ratingNumber, title, comments, created, modified) VALUES ('".$POST['books']."', '".$userID."', '".$POST['rating']."', '".$POST['title']."', '".$POST["comment"]."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
		mysqli_query($this->dbConnect, $insertRating);	
	}
}
?>