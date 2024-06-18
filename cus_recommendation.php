<?php
include './php/config.php';
session_start();
if (isset($_SESSION['admin_id'])) {
    $user_id = $_SESSION['admin_id'];
    include './templates/admin_header.php';
} elseif (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    include './templates/user_header.php';
} else {
    include './templates/header.php';
}

$title = 'Recommendation';

// Step 1: Fetch Data from the Database
include "./php/config.php"; // Include your database connection file
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch target user ID based on the email stored in the session
if (isset($_SESSION['user_email'])) {
    $query1 = "SELECT id, email FROM users WHERE email = '" . $_SESSION['user_email'] . "' LIMIT 1";
    $result1 = mysqli_query($con, $query1);

    if ($result1) {
        $row = mysqli_fetch_assoc($result1);

        if (isset($row['id'])) {
            $targetUserId = $row['id'];
        } else {
            echo "<br><br><p style=\"font-weight: bold;\">No recommendations available. Please rate books to receive recommendations.</p>";
            // Include relevant HTML or redirect to a suitable page
            exit;
        }

        $_SESSION['user'] = $row['email']; // Set session variable here
    } else {
        echo "<br><br><p style=\"font-weight: bold;\">Error fetching user data. Please try again later.</p>";
        exit;
    }
} else {
    echo "<br><br><p style=\"font-weight: bold;\">User email not found in session. Please log in.</p>";
    // Include relevant HTML or redirect to a suitable page
    exit;
}

// Move this line after fetching $row
$_SESSION['user'] = $row['email'];

$queryTargetUserRatings = "SELECT * FROM item_rating WHERE users = '$targetUserId'";
$resultTargetUserRatings = mysqli_query($con, $queryTargetUserRatings);

$queryOtherUsersRatings = "SELECT * FROM item_rating WHERE users != '$targetUserId'";
$resultOtherUsersRatings = mysqli_query($con, $queryOtherUsersRatings);

$queryAllRatings = "SELECT * FROM item_rating";
$resultAllRatings = mysqli_query($con, $queryAllRatings);



// Determine the number of neighbors to consider
$queryNumNeighbors = "SELECT COUNT(*) as num_neighbors FROM item_rating WHERE users != '$targetUserId'";
$resultNumNeighbors = mysqli_query($con, $queryNumNeighbors);
$rowNumNeighbors = mysqli_fetch_assoc($resultNumNeighbors);
$numNeighbors = $rowNumNeighbors['num_neighbors'];

// Fetch all user ratings from the database and store them in the $users array
$query = "SELECT users FROM item_rating";
$result = mysqli_query($con, $query);
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $userId = $row['users'];
    $users[$userId] = getUserRatings($userId);
}


// Check if the target user has any ratings
if (!isset($users[$targetUserId]) || count($users[$targetUserId]) == 0) {
    echo "No recommendations available. Please rate books to receive recommendations.";
    // Include relevant HTML or redirect to a suitable page
    exit;
}

// Function to get user ratings based on user ID
function getUserRatings($userId) {
    global $con; // Assuming $con is your database connection variable

    $query = "SELECT * FROM item_rating WHERE users = '$userId'";
    $result = mysqli_query($con, $query);

    $ratings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $ratings[] = $row;
    }

    return $ratings;
}

// Function to find nearest neighbors
function findNearestNeighbors($targetUserId, $users, $numNeighbors) {
    // Implement your logic to find nearest neighbors here
    // You can use similarity metrics or other algorithms for this purpose

    // For demonstration purposes, let's assume a simple random selection of neighbors
    $allUserIds = array_keys($users);
    $nearestNeighbors = array_diff($allUserIds, [$targetUserId]);
    shuffle($nearestNeighbors);
    $nearestNeighbors = array_slice($nearestNeighbors, 0, $numNeighbors);

    return $nearestNeighbors;
}

// Placeholder for the generateRecommendations function
function generateRecommendations($targetUserId, $nearestNeighbors, $users) {
    // Implement your logic to generate recommendations here

    $recommendations = [];

    // Iterate through each nearest neighbor
    foreach ($nearestNeighbors as $neighborId) {
        // Get the ratings of the neighbor
        $neighborRatings = $users[$neighborId];

        // Calculate a similarity score (for example, using cosine similarity)
        $similarity = calculateSimilarity($users[$targetUserId], $neighborRatings);

        // Iterate through each book rated by the neighbor
        foreach ($neighborRatings as $neighborRating) {
            $bookId = $neighborRating['books'];

            // Check if the target user has not rated the book
            if (!isset($users[$targetUserId][$bookId])) {
                // If the book is not already recommended, initialize the score
                if (!isset($recommendations[$bookId])) {
                    $recommendations[$bookId] = 0;
                }

                // Update the recommendation score based on similarity and neighbor's rating
                $recommendations[$bookId] += $similarity * $neighborRating['ratingNumber'];
            }
        }
    }

    // Sort the recommendations by score in descending order
    arsort($recommendations);

    return $recommendations;
}

// Function to calculate similarity between two users (cosine similarity in this example)
function calculateSimilarity($user1Ratings, $user2Ratings) {
    // Implement your similarity calculation logic here
    // For simplicity, let's use cosine similarity as an example

    $dotProduct = 0;
    $magnitudeUser1 = 0;
    $magnitudeUser2 = 0;

    foreach ($user1Ratings as $bookId => $rating) {
        if (isset($user2Ratings[$bookId])) {
            $dotProduct += $rating['ratingNumber'] * $user2Ratings[$bookId]['ratingNumber'];
        }
        $magnitudeUser1 += pow($rating['ratingNumber'], 2);
    }

    foreach ($user2Ratings as $bookId => $rating) {
        $magnitudeUser2 += pow($rating['ratingNumber'], 2);
    }

    $magnitudeUser1 = sqrt($magnitudeUser1);
    $magnitudeUser2 = sqrt($magnitudeUser2);

    if ($magnitudeUser1 == 0 || $magnitudeUser2 == 0) {
        return 0; // Avoid division by zero
    }

    return $dotProduct / ($magnitudeUser1 * $magnitudeUser2);
}

// Determine the number of neighbors to consider
$queryNumNeighbors = "SELECT COUNT(*) as num_neighbors FROM item_rating WHERE users != '$targetUserId'";
$resultNumNeighbors = mysqli_query($con, $queryNumNeighbors);
$rowNumNeighbors = mysqli_fetch_assoc($resultNumNeighbors);
$numNeighbors = $rowNumNeighbors['num_neighbors'];

// Fetch all user ratings from the database and store them in the $users array
$query = "SELECT users FROM item_rating";
$result = mysqli_query($con, $query);
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $userId = $row['users'];
    $users[$userId] = getUserRatings($userId);
}

// Check if the target user has any ratings
if (!isset($users[$targetUserId]) || count($users[$targetUserId]) == 0) {
    echo "No recommendations available. Please rate books to receive recommendations.";
    // Include relevant HTML or redirect to a suitable page
    exit;
}

// Find nearest neighbors for the target user
$nearestNeighbors = findNearestNeighbors($targetUserId, $users, $numNeighbors);

// Generate recommendations for the target user
$recommendations = generateRecommendations($targetUserId, $nearestNeighbors, $users);

// Display the recommended books
// Modify the code to fetch book details and display relevant information
foreach ($recommendations as $bookId => $score) {
    // Fetch book details from the database
    $query = "SELECT * FROM books WHERE id = '$bookId'";
    $result = mysqli_query($con, $query);
    $bookDetails = mysqli_fetch_assoc($result);

    // Fetch maximum quantity for the book from the database
    $queryMaxQuantity = "SELECT MAX(qty) as max_qty FROM books WHERE id = '$bookId'";
    $resultMaxQuantity = mysqli_query($con, $queryMaxQuantity);
    $rowMaxQuantity = mysqli_fetch_assoc($resultMaxQuantity);
    $maxQuantity = $rowMaxQuantity['max_qty'];

    echo '<div class="row">';
    echo '<div class="col-sm-2" style="width:150px">';
    echo '<img class="product_image" src="./src/uploads/' . $bookDetails["image"] . '" style="width:100px;height:200px;padding-top:10px;">';
        echo '</div>'; 
    echo '<div class="col-sm-4">';
    echo '<h4 style="margin-top:10px;">' . $bookDetails["title"] . '</h4>';
    echo $bookDetails["description"];


   
    echo '<form action="" method="post" class="cart-box">';
    echo '<input type="hidden" name="book_id" value="' . $bookDetails['id'] . '">';
    echo '<input type="hidden" name="book_title" value="' . $bookDetails['title'] . '">';
    echo '<input type="hidden" name="book_price" value="' . $bookDetails['price'] . '">';
    echo '<input type="hidden" name="book_image" value="' . $bookDetails['image'] . '">';
    echo '<input type="number" name="book_quantity" value="1" min="1" max="' . $maxQuantity . '" id="book_quantity" class="qty">';
    echo '<button type="submit" value="Add to cart" name="add_to_cart" class="btn">';
    echo '<img src="./src/cart.svg" alt="" style="width:25px; margin-right:10px;"> Add to cart</button>';
    echo '</form>';
  
    echo '</div>';
}


if(isset($_POST['add_to_cart'])){

    $book_id = $_POST['book_id'];
    $book_title = $_POST['book_title'];
    $book_price = $_POST['book_price'];
    $book_image = $_POST['book_image'];
    $book_quantity = $_POST['book_quantity'];

    $sql = "SELECT * FROM cart WHERE title = '$book_id' AND user_id = '$user_id'";
    $result = mysqli_query($con, $sql);

    if(mysqli_num_rows($result) > 0){
        echo '<script>alert("Book already added to cart!")</script>';
    } else {
        $sql = "INSERT INTO cart (user_id, title, price, quantity, image) VALUES ('$user_id', '$book_title', '$book_price', '$book_quantity', '$book_image')";
        $result = mysqli_query($con, $sql);

        if($result){
            echo '<script>alert("Book Added to Cart Successfully!"); 
            window.location.href="cus_recommendation.php";</script>';  
        } else {
            echo '<script>alert("Book not added to cart!")</script>';
        }
    }
}        
?>
<style>
.book-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-gap: 20px;
}
.book-info {
    display: flex;
  flex-direction: column;
}
.row {
    display: flex;
    flex-wrap: wrap;
  }

  .col-sm-2 {
    width: 33.33%; /* Each book takes up one-third of the row */
    box-sizing: border-box;
    padding: 10px; /* Adjust as needed for spacing */
  }

  .product_image {
    width: 100%;
    height: auto; /* Maintain aspect ratio */
    max-height: 200px; /* Limit the maximum height */
  }

  .col-sm-4 {
    width: 66.66%; /* Two-thirds of the row for book details and form */
    box-sizing: border-box;
    padding: 10px; /* Adjust as needed for spacing */
  }

  .cart-box {
    margin-top: 20px;
  }

  .qty {
    width: 50px;
  }

  .btn {
    background-color: #4CAF50;
    color: white;
    padding: 10px;
    border: none;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin-top: 10px;
    cursor: pointer; 




</style>