<?php
include './php/config.php';



// Fetch categories from the database
$query = "SELECT * FROM categories"; // Modify this query according to your database structure
$result = mysqli_query($con, $query);

$categories = array();
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/logged_user_header.css">
    <link rel="stylesheet" type="text/css" href="./css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer"
    />
    <script src="./js/form.js" defer></script>
    <script src="./js/script.js" defer></script>

    <title>
        <?php echo $title; ?>
    </title>
</head>

<body>

    <header>
        <div class="header">
            <div class="top-container">
                <div class="logo">
                    <a href="index.php" class="brand-logo">
                        <!-- <img src="./src/logo.png" alt="logo" id="logo"> -->
                        <h2>Online Book store</h2>
                    </a>
                </div>
                <div class="cat-button">
                <button class="category"> Category</button>
                <div class="c-list">
                    <?php
                    foreach ($categories as $category) {
                        echo '<button class="links category-btn" data-category-id="' . $category['id'] . '">' . $category['name'] . '</button>';
                    }
                    ?>
                    <button class="links" style="border-radius: 0 0 10px 10px;">Stories</button>
                </div>
            </div>
                <div class="bottom-container">
                <nav>
                    <div class="nav-links">
                        <ul id="nav-mobile" class="right hide-on-med-and-down">
                            <li><a href="aboutus.php ">About Us</a></li>
                            <li><a href="shop.php ">Shop</a></li>
                            <!-- <li><a href="faq.php ">FAQ</a></li> -->
                            <li><a href="contactus.php ">Contact</a></li>

                        </ul>
                    </div>
                </nav>
            </div>
                <div class="search-bar">
                    <div class="search">
                        
                            <a href="search.php"><button type="submit" class="search-btn" name="search-btn">
                            <i class="fa fa-search"></i>
                        </button></a>
                        
                    </div>
                </div>
                <div class="cart-icon">
                        <?php
                        $select_cart_number = mysqli_query($con, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                        $cart_rows_number = mysqli_num_rows($select_cart_number); 
                        ?>
                        <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
                </div>

                <div class="user-box">
                    
                
                    <div class="user-button">
                    <span>
    <img src="./src/logged_profile.svg" alt="" style="margin-right:5px; width:30px; height:auto">
</span>
<?php echo $_SESSION['user_name']; ?>
<i class="fa-solid fa-angle-down" id="user-popup-dropdown" style="margin-left:10px;"></i>


                    </div>
                    
                    <div class="user-box-popup">
                        <div class="user-list">
                            <a href="user_dashboard.php">
                                <i class="fa-solid fa-user-circle"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="logout.php">
                                <i class="fa-solid fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
                

            </div>
            
        </div>
    </header>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var categoryButtons = document.querySelectorAll(".category-btn");

            categoryButtons.forEach(function(button) {
                button.addEventListener("click", function() {
                    var categoryId = button.getAttribute("data-category-id");
                    fetchCategoryContent(categoryId);
                });
            });
        });

        function fetchCategoryContent(categoryId) {
            // Make an API call to fetch content based on the selected category ID
            // Replace 'get_books_by_category.php?category_id=' with the correct path
            fetch("get_books_by_category.php?category_id=" + categoryId)
                .then(response => response.json())
                .then(data => {
                    // Display the fetched content on the page
                    displayContent(data);
                })
                .catch(error => {
                    console.error("Error fetching content:", error);
                });
        }

      
function displayContent(content) {
    var bookContainer = document.querySelector(".book-container"); // Replace with the actual container element

    // Clear any existing content in the container
    bookContainer.innerHTML = "";

    // Loop through the fetched books and create elements for each book
    content.forEach(function(book) {
        var bookCard = document.createElement("div");
        bookCard.className = "book-card";

        var imageElement = document.createElement("img");
        imageElement.className = "book-image";
        imageElement.src = "./src/uploads/" + book.image;

        var titleElement = document.createElement("h3");
        titleElement.textContent = book.title;

        var authorElement = document.createElement("p");
        authorElement.textContent = "Author: " + book.author;

        var priceElement = document.createElement("p");
        priceElement.textContent = "Price: Rs" + book.price; // Assuming 'Rs' is the currency symbol

        // var ratingElement = document.createElement("p");
        // ratingElement.textContent = "Rating: " + book.rating; // Replace 'rating' with the actual property in your book data

        var descriptionElement = document.createElement("p");
        descriptionElement.className = "book-description";
        descriptionElement.textContent = book.description;

        var addToCartButton = document.createElement("button");
        addToCartButton.textContent = "Add to Cart";
        addToCartButton.className = "add-to-cart-button";
        addToCartButton.addEventListener("click", function() {
            addToCart(book.id); // Call the addToCart function with book ID
        });

        // ... Create other elements for other book details

        // Append the elements to the book card
        bookCard.appendChild(imageElement);
        bookCard.appendChild(titleElement);
        bookCard.appendChild(authorElement);
        bookCard.appendChild(priceElement); // Add the price
      
        // bookCard.appendChild(addToCartButton);
        bookCard.appendChild(descriptionElement); // Add the description

        // ... Append other elements to the book card

        // Append the book card to the container
        bookContainer.appendChild(bookCard);
    });
}



    </script>