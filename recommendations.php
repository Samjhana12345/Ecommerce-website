<?php
// Assume you have a database connection established

session_start();
include('inc/header.php');

// Function to get user's preferred categories based on past ratings
function getUserPreferredCategories($userId) {
    // Implement logic to fetch and return the user's preferred categories
    // This could involve analyzing their past ratings and book categories
    // Return an array of category IDs or names
}

// Function to recommend books to a user
function recommendBooks($userId) {
    // Get the user's preferred categories
    $preferredCategories = getUserPreferredCategories($userId);

    // Initialize an array to store recommended books
    $recommendedBooks = [];

    // Loop through each preferred category
    foreach ($preferredCategories as $category) {
        // Query the database to get top-rated books in this category that the user hasn't rated
        $sql = "SELECT b.id, b.title, b.author, AVG(r.ratingNumber) AS averageRating
                FROM books b
                LEFT JOIN item_rating r ON b.id = r.books
                WHERE b.category = '$category' AND b.id NOT IN (
                    SELECT r.books FROM item_rating r WHERE r.users = '$userId'
                )
                GROUP BY b.id
                ORDER BY averageRating DESC
                LIMIT 3"; // You can adjust the limit as needed

        // Execute the SQL query and fetch results
        $result = mysqli_query($dbConnect, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $recommendedBooks[] = $row;
            }
        }
    }

    return $recommendedBooks;
}

// Example usage:
$userId = 1; // Replace with the user's ID
$recommendations = recommendBooks($userId);

// Display recommended books
foreach ($recommendations as $book) {
    echo "Title: {$book['title']}, Author: {$book['author']}, Average Rating: {$book['averageRating']}<br>";
}
?>
