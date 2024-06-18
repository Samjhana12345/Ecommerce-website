<?php
include './php/config.php';

if(isset($_GET['search'])){
    $search = $_GET['search'];
    
    // Query to fetch suggestions based on the search input
    $query = "SELECT DISTINCT title FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%' LIMIT 5";
    $result = mysqli_query($con, $query) or die('query failed');
    
    $suggestions = array();
    while($row = mysqli_fetch_assoc($result)){
        $suggestions[] = $row['title'];
    }
    
    echo json_encode($suggestions);
}
?>