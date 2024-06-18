<?php
$title = 'Search';
include './php/config.php';
session_start();



 
function getPopularSearchQueriesWithCounts($con) {
    $sql = "SELECT query, search_count FROM search_queries ORDER BY search_count DESC LIMIT 5"; // Change the LIMIT as needed
    $result = mysqli_query($con, $sql);
    $queries = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $queries[] = $row; // Include both the query and search count in the array
    }

    return $queries;
}

// function getPopularSearchQueries($con) {
//     $sql = "SELECT query FROM search_queries ORDER BY search_count DESC LIMIT 5"; // Change the LIMIT as needed
//     $result = mysqli_query($con, $sql);
//     $queries = [];

//     while ($row = mysqli_fetch_assoc($result)) {
//         $queries[] = $row['query'];
//     }

//     return $queries;
// }
if (isset($_GET['search'])) {
    $searchInput = $_GET['search'];

    // Check if a record with the same query exists
    $checkQuery = "SELECT COUNT(*) as count FROM search_queries WHERE query = '$searchInput'";
    $checkResult = mysqli_query($con, $checkQuery);

    if ($checkResult) {
        $row = mysqli_fetch_assoc($checkResult);
        $count = $row['count'];

        if ($count > 0) {
            // Update the search count for the existing record
            $updateQuery = "UPDATE search_queries SET search_count = search_count + 1 WHERE query = '$searchInput'";
            mysqli_query($con, $updateQuery);
        } else {
            // Insert a new record
            $insertQuery = "INSERT INTO search_queries (query, search_count) VALUES ('$searchInput', 1)";
            mysqli_query($con, $insertQuery);
        }
    }
}

// Rest of your code...


// Assuming you have already retrieved the top search queries
$topSearchQueries = getPopularSearchQueriesWithCounts($con);

// Initialize an array to store book results
$topSearchBooks = [];

// Loop through the top search queries
foreach ($topSearchQueries as $queryData) {
    $query = $queryData['query'];

    // Modify your search query to include the current top query
    $search_books = mysqli_query($con, "SELECT * FROM books WHERE title LIKE '%$query%' OR author LIKE '%$query%' ORDER BY title") or die('query failed');

    // Add the books that match the current top query to the results array
    while ($fetch_books = mysqli_fetch_assoc($search_books)) {
        $topSearchBooks[] = $fetch_books;
    }
}

// Function to get the average rating for a book
function getAverageRating($book_id, $con) {
    $sql = "SELECT AVG(ratingNumber) AS average FROM item_rating WHERE books = '$book_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['average'] ? $row['average'] : 0; // Return the average rating or 0 if there are no ratings yet
}

if (isset($_SESSION['admin_id'])) {
    $user_id = $_SESSION['admin_id'];
    include './templates/admin_header.php';
} elseif (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    include './templates/user_header.php';
} else {
    include './templates/header.php';
}

if (isset($_POST['add_to_cart'])) {
    // Add to cart functionality
    if (!isset($user_id)) {
        echo '<script>alert("Please login to add books to cart!");
        window.location.href="login.php";</script>';
    } else {

        $book_id = $_POST['book_id'];
        $book_title = $_POST['book_title'];
        $book_price = $_POST['book_price'];
        $book_image = $_POST['book_image'];
        $book_quantity = $_POST['book_quantity'];

        $sql = "SELECT * FROM cart WHERE title = '$book_id' AND user_id = '$user_id'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<script>alert("Book already added to cart!")</script>';
        } else {
            $sql = "INSERT INTO cart (user_id, title, price, quantity, image) VALUES ('$user_id', '$book_title', '$book_price', '$book_quantity', '$book_image')";
            $result = mysqli_query($con, $sql);

            if ($result) {
                echo '<script>alert("Book Added to Cart Successfully!"); 
            window.location.href="shop.php";</script>';
            } else {
                echo '<script>alert("Book not added to cart!")</script>';
            }
        }
    }

}
?>

<link rel="stylesheet" href="./css/shop.css">
<link rel="stylesheet" href="./css/search.css">
<link rel="stylesheet" href="./css/suggestion.css">






<div class="title-section">
    <div class="title-section-container">
        <h1><?php echo $title ?></h1>
        <br>
        <ul class="breadcrump">
            <li><i class="fa-solid fa-house"></i> Home</li>
            <li>&gt; <?php echo $title ?></li>
        </ul>
    </div>
</div>

<div class="main-container" style="display:block;align-items: flex-start;">
    <div class="search-page-bar">
        <div class="search">
            <form action="" method="get" class="search-page-form">
                <input type="text" name="search" placeholder="Search books..." id="search-input">
                <button type="submit" name="submit-search" class="search-btn">
                    <i class="fa fa-search"></i>
                </button>
            </form>
            <div id="suggestions-container"></div>
        </div>
    </div>

    <div class="book-container">
        <?php
        $select_books = mysqli_query($con, "SELECT * FROM books ORDER BY title") or die('query failed');
        $select_category = mysqli_query($con, "SELECT name FROM categories INNER JOIN books ON categories.id = books.category_id") or die('query failed');

        if (isset($_GET['submit-search'])) {
            $search = $_GET['search'];
            $search_books = mysqli_query($con, "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%' ORDER BY title") or die('query failed');

            if (mysqli_num_rows($search_books) > 0) {
                // Convert the search results to an array for binary search
                $books_array = [];
                while ($fetch_books = mysqli_fetch_assoc($search_books)) {
                    $books_array[] = $fetch_books;
                }

                // Binary search implementation
                $search_result = binarySearch($books_array, $search);
                if ($search_result !== false) {
                    $fetch_books = $books_array[$search_result];
                    $fetch_category = mysqli_fetch_assoc($select_category);

                    // Display the book card
                    $average = getAverageRating($fetch_books['id'], $con);

                    ?>

 
<link rel="stylesheet" href="./css/search_recommadation.css">
<div class="top-search-books"><h1>top search book</h1>
    <?php
    $bookCount = 0; // Initialize a counter

    foreach ($topSearchBooks as $book) {
        if ($bookCount >= 3) {
            // Display only the top 3 books
            break;
        }
        ?>
        <div class="book-card">
         
            <img class="product_image" src="./src/uploads/<?php echo $book["image"]; ?>" style="width:100px;height:200px;padding-top:10px;">
            <h4 style="margin-top:10px;"><?php echo $book["title"]; ?></h4>
            <div>
                <span class="average"><?php printf('%.1f', getAverageRating($book['id'], $con)); ?> <small>/ 5</small></span>
                <span class="rating-reviews"><a href="show_rating.php?item_id=<?php echo $book["id"]; ?>">Rating & Reviews</a></span>
            </div>
        </div>
        <?php
        $bookCount++; // Increment the counter
    }
    ?>
</div>





                   <div class="book-card">
    <form action="" method="post" class="cart-box">
        <img class="image" src="./src/uploads/<?php echo $fetch_books['image'] ?>" alt="">
        <div class="info">
            <h3><?php echo $fetch_books['title'] ?></h3>
            <p><?php echo $fetch_category['name'] ?></p>
        
            <div class="price-qty">
                <span>Rs<?php echo $fetch_books['price'] ?></span>
                <input type="number" name="book_quantity" value="1" min="1" id="book_quantity" class="qty">
            </div>
            <input type="hidden" name="book_id" value="<?php echo $fetch_books['id'] ?>">
            <input type="hidden" name="book_title" value="<?php echo $fetch_books['title'] ?>">
            <input type="hidden" name="book_price" value="<?php echo $fetch_books['price'] ?>">
            <input type="hidden" name="book_image" value="<?php echo $fetch_books['image'] ?>">
            <div class="btn-1">
                <button type="submit" value="Add to cart" name="add_to_cart" class="btn"><img src="./src/cart.svg"
                        alt="" style="width:25px; margin-right:10px;"> Add to cart
                </button>
                <div><span class="average"><?php printf('%.1f', $average); ?> <small>/ 5</small></span>
                            <div> <small> </small></span> <span class="rating-reviews"><a href="show_rating.php?item_id=<?php echo $fetch_books["id"]; ?>">Rating & Reviews</a></span></div>
                            <p class="description"><?php echo substr($fetch_books['description'], 0, 50); ?><span class="dots">...</span><span class="more" style="display: none;"><?php echo substr($fetch_books['description'], 50); ?></span></p>
<button class="read-more-btn">Read More</button>

<style>
    .dots {
        display: inline;
    }

    .more {
        display: none;
    }
</style>

<script>
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("read-more-btn")) {
            var description = event.target.previousElementSibling;
            var dots = description.querySelector('.dots');
            var moreText = description.querySelector('.more');

            if (dots.style.display === "none" || dots.style.display === "") {
                dots.style.display = "inline";
                event.target.innerHTML = "Read Less";
                moreText.style.display = "inline";
            } else {
                dots.style.display = "none";
                event.target.innerHTML = "Read More";
                moreText.style.display = "none";
            }
        }
    });
</script>






            </div>
        </div>
    </form>
</div>

                    <?php
                } else {
                    echo '<p class="empty">No result found!</p>';
                }
            } else {
                echo '<p class="empty">No result found!</p>';
            }
        } else {
            echo '<p class="empty">Search for something</p>';
        }

        // Binary search implementation
        function binarySearch($arr, $search)
        {
            $left = 0;
            $right = count($arr) - 1;

            while ($left <= $right) {
                $mid = floor(($left + $right) / 2);

                if ($arr[$mid]['title'] == $search) {
                    return $mid; // Match found
                }

                if ($arr[$mid]['title'] < $search) {
                    $left = $mid + 1; // Discard left half
                } else {
                    $right = $mid - 1; // Discard right half
                }
            }

            return false; // Match not found
        }
        ?>
    </div>
</div>

<?php
include './templates/newsletter.php';
include './templates/footer.php';
?>

<script>
    const searchInput = document.getElementById('search-input');
    const suggestionsContainer = document.getElementById('suggestions-container');

    searchInput.addEventListener('input', function () {
        const searchValue = this.value.trim();

        if (searchValue !== '') {
            fetchSuggestions(searchValue);
        } else {
            suggestionsContainer.innerHTML = '';
        }
    });

    function fetchSuggestions(searchValue) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `search_suggestions.php?search=${searchValue}`, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const suggestions = JSON.parse(xhr.responseText);
                displaySuggestions(suggestions);
            }
        };

        xhr.send();
    }

    function displaySuggestions(suggestions) {
        suggestionsContainer.innerHTML = '';

        suggestions.forEach(function (suggestion) {
            const suggestionItem = document.createElement('div');
            suggestionItem.classList.add('suggestion-item');
            suggestionItem.textContent = suggestion;
            suggestionsContainer.appendChild(suggestionItem);

            suggestionItem.addEventListener('click', function () {
                searchInput.value = suggestion;
                suggestionsContainer.innerHTML = '';
            });
        });
    }
</script>
<script>
     const searchInput = document.getElementById('search-input');
   

    searchInput.addEventListener('input', function () {
        const searchValue = this.value.trim();

        if (searchValue !== '') {
            fetchSuggestions(searchValue);
        } else {
            suggestionsContainer.innerHTML = '';
        }
    });
    </script>