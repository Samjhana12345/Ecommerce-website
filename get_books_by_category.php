<?php
include './php/config.php'; // Include your database configuration

if(isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    $query = "SELECT * FROM books WHERE category_id = '$category_id'";
    $result = mysqli_query($con, $query);

    $books = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }

    echo json_encode($books);
}
?>
