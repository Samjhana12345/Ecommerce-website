<?php
$title = 'Shop';
include './php/config.php';
session_start();


// Function to get the average rating for a book
function getAverageRating($book_id, $con) {
    $sql = "SELECT AVG(ratingNumber) AS average FROM item_rating WHERE books = '$book_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['average'] ? $row['average'] : 0; // Return the average rating or 0 if there are no ratings yet
}
// Pagination variables
$resultsPerPage = 4; // Number of results per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number

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

<link rel="stylesheet" href="./css/shop.css" >
<link rel="stylesheet" href="./css/pagination.css" >

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
<div class="category-buttons">
        <?php
        $select_categories = mysqli_query($con, "SELECT * FROM categories") or die('query failed');
        while ($fetch_categories = mysqli_fetch_assoc($select_categories)) {
            ?>
            <button class="category-btn" data-category-id="<?php echo $fetch_categories['id']; ?>">
    <?php echo $fetch_categories['name']; ?>
</button>

        <?php
        }
        ?>
    </div>


    <link rel="stylesheet" href="./css/recommendations.css">
    <div class="book-container">
      <!-- Recommendation Section -->
      <div class="recommendation-section">
    <?php
    // Connect to the database and execute the SQL query
    $connection = mysqli_connect("localhost", "root", "", "edenbookstore");

    if ($connection) {
        $query = "SELECT b.id, b.title, b.author, b.publisher, b.price, b.qty, c.name AS category, b.description, b.image,
                AVG(r.ratingNumber) AS averageRating
                FROM books b
                INNER JOIN categories c ON b.category_id = c.id
                LEFT JOIN item_rating r ON b.id = r.books
                GROUP BY b.id
                ORDER BY averageRating DESC";

        $result = mysqli_query($connection, $query);

        if ($result) {
            // Pagination Variables
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $itemsPerPage = 3; // Number of items to display per page
            $totalItems = mysqli_num_rows($result);
            $totalPages = ceil($totalItems / $itemsPerPage);
            $startIndex = ($currentPage - 1) * $itemsPerPage;

            // Limit the query based on pagination
            $query .= " LIMIT $startIndex, $itemsPerPage";
            $result = mysqli_query($connection, $query);

            // Output HTML to display the recommendations
            echo "<h2>Top Rated Items</h2>";
            echo "<div class='book-container'>";

            while ($row = mysqli_fetch_assoc($result)) {
                $bookId = $row['id'];
                $title = $row['title'];
                $author = $row['author'];
                $publisher = $row['publisher'];
                $price = $row['price'];
                $category = $row['category'];
                $image = $row['image'];
                $averageRating = $row['averageRating'];

                // Display each recommended book
                echo "<div class='book-card'>";
                echo "<form action='' method='post' class='cart-box'>";
                echo "<img class='image' src='./src/uploads/$image' alt=''>";
                echo "<div class='info'>";
                echo "<h3>$title</h3>";
                echo "<p>$category</p>";
                echo "<p>Author: $author</p>";
                echo "<p>Publisher: $publisher</p>";
                echo "<div class='price-qty'>";
                echo "<span>Rs$price</span>";
                echo "<input type='number' name='book_quantity' value='1' min='1' id='book_quantity' class='qty'>";
                echo "</div>";
                echo "<input type='hidden' name='book_id' value='$bookId'>";
                echo "<input type='hidden' name='book_title' value='$title'>";
                echo "<input type='hidden' name='book_price' value='$price'>";
                echo "<input type='hidden' name='book_image' value='$image'>";
                echo "<div class='btn-1'>";
                echo "<button type='submit' value='Add to cart' name='add_to_cart' class='btn'><img src='./src/cart.svg' alt='' style='width:25px; margin-right:10px;'> Add to cart</button>";
                echo "</div>";
                echo "<span class='average'>" . sprintf('%.1f', $averageRating) . " <small>/ 5</small></span>";
                echo "<div> <small> </small></span> <span class='rating-reviews'><a href='show_rating.php?item_id=$bookId'>Rating & Reviews</a></span></div>";
                echo "</div>";
                echo "</form>";
                echo "</div>";
            }

            echo "</div>";

            // Pagination Links
            echo "<div class='pagination'>";
            if ($totalPages > 1) {
                if ($currentPage > 1) {
                    echo "<a href='shop.php?page=" . ($currentPage - 1) . "' class='previous'>Previous</a>";
                }

                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($currentPage == $i) {
                        echo "<a href='shop.php?page=$i' class='active'>$i</a>";
                    } else {
                        echo "<a href='shop.php?page=$i'>$i</a>";
                    }
                }

                if ($currentPage < $totalPages) {
                    echo "<a href='shop.php?page=" . ($currentPage + 1) . "' class='next'>Next</a>";
                }
            }
            echo "</div>";
        } else {
            echo "Error in executing SQL query: " . mysqli_error($connection);
        }

        // Close the database connection
        mysqli_close($connection);
    } else {
        echo "Failed to connect to the database.";
    }
    ?>
</div>

















<!-- All Books with Pagination -->
<div class="all-books-section">
    <?php
    // Pagination code for displaying all books with pagination
    // ...

    ?>
</div>

        <?php
        $select_books = mysqli_query($con, "SELECT * FROM books") or die('query failed');
        $totalResults = mysqli_num_rows($select_books); // Total number of results
        $totalPages = ceil($totalResults / $resultsPerPage); // Total number of pages

        // Calculate the starting index for the current page
        $startIndex = ($current_page - 1) * $resultsPerPage;

        $select_books = mysqli_query($con, "SELECT * FROM books LIMIT $startIndex, $resultsPerPage") or die('query failed');

        if (mysqli_num_rows($select_books) > 0) {
            while ($fetch_books = mysqli_fetch_assoc($select_books)) {
                $book_id = $fetch_books['id'];
                $select_category = mysqli_query($con, "SELECT name FROM categories INNER JOIN books ON categories.id = books.category_id WHERE books.id = $book_id") or die('query failed');
                $fetch_category = mysqli_fetch_assoc($select_category);
                
                $average = getAverageRating($book_id, $con);
                ?>
                <div class="book-card">
                    <form action="" method="post" class="cart-box">
                        <img class="image" src="./src/uploads/<?php echo $fetch_books['image'] ?>" alt="">
                        <div class="info">
                            <h3><?php echo $fetch_books['title'] ?></h3>
                            <p><?php echo $fetch_category['name'] ?></p>
                            <div class="price-qty">
                                <span>Rs<?php echo $fetch_books['price'] ?></span>
                                <?php
    // Fetch maximum quantity for the book from the database
    $queryMaxQuantity = "SELECT MAX(qty) as max_qty FROM books WHERE id = '{$fetch_books['id']}'";
    $resultMaxQuantity = mysqli_query($con, $queryMaxQuantity);
    $rowMaxQuantity = mysqli_fetch_assoc($resultMaxQuantity);
    $maxQuantity = $rowMaxQuantity['max_qty'];
    ?>
    <input type="number" name="book_quantity" value="1" min="1" max="<?php echo $maxQuantity; ?>" id="book_quantity" class="qty">
</div>
                            <input type="hidden" name="book_id" value="<?php echo $fetch_books['id'] ?>">
                            <input type="hidden" name="book_title" value="<?php echo $fetch_books['title'] ?>">
                            <input type="hidden" name="book_price" value="<?php echo $fetch_books['price'] ?>">
                            <input type="hidden" name="book_image" value="<?php echo $fetch_books['image'] ?>">
                            
                             

                            <div class="btn-1">
                                <button type="submit" value="Add to cart" name="add_to_cart" class="btn"><img
                                        src="./src/cart.svg" alt=""
                                        style="width:25px; margin-right:10px;"> Add to cart</button>
                            </div>
                            <span class="average"><?php printf('%.1f', $average); ?> <small>/ 5</small></span>
                            <div> <small> </small></span> <span class="rating-reviews"><a href="show_rating.php?item_id=<?php echo $fetch_books["id"]; ?>">Rating & Reviews</a></span></div>
                            <?php echo $fetch_books["description"]; ?>
                        </div>
                    </form>
                </div>
            <?php
            }
        } else {
            echo '<p class="empty">No books added yet!</p>';
        }
        ?>

    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <?php if ($current_page > 1): ?>
                <a href="admin_shop.php?page=<?php echo ($current_page - 1); ?>" class="previous">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($current_page == $i): ?>
                    <a href="admin_shop.php?page=<?php echo $i; ?>" class="active"><?php echo $i; ?></a>
                <?php else: ?>
                    <a href="shop.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($current_page < $totalPages): ?>
                <a href="shop.php?page=<?php echo ($current_page + 1); ?>" class="next">Next</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php
    include './templates/newsletter.php';
    include './templates/footer.php';
    ?>

  