<?php
$title = 'Checkout';
include './php/config.php';
session_start();


if(isset($_SESSION['admin_id'])){
        $user_id = $_SESSION['admin_id'];
        include './templates/admin_header.php';
    } elseif(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        include './templates/user_header.php';
        
}   else
    header('location:login.php');

if(isset($_POST['order-btn'])){

    $name = mysqli_real_escape_string($con, $_POST['firstName'] . ' ' .$_POST['lastName']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = $_POST['phone'];
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $payment_method = $_POST['payment_method'];
    $date = date('Y-m-d');
    
    $cart_total = 0;
    $cart_books[] = '';

    $cart_query = mysqli_query($con, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($cart_query) > 0){
        while($cart_row = mysqli_fetch_assoc($cart_query)){
            $cart_total += $cart_row['price'];
            $cart_books[] = $cart_row['title'] . ' (' .$cart_row['quantity'].')';
            $sub_total = ($cart_row['price'] * $cart_row['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_books = implode(', ', $cart_books);
    $order_query = mysqli_query($con, "SELECT * FROM orders WHERE name = '$name' AND phone = '$phone' AND email = '$email' AND payment_method = '$payment_method' AND address = '$address' AND total_books = '$total_books' AND total_price = '$cart_total'") or die('query failed');
    
if($cart_total == 0){
    echo '<script>alert("Your cart is empty!");
     window.location.href="shop.php";</script>';
    
} else {
    if(mysqli_num_rows($order_query) > 0){
        echo '<script>alert("Order already placed!")</script>';
    } else {
        mysqli_query($con, "INSERT INTO orders (user_id, name, phone, email, payment_method, address , total_books, total_price, placed_date) 
        VALUES ('$user_id', '$name', '$phone', '$email', '$payment_method', '$address' , '$total_books', '$cart_total', '$date')") or die('query failed');
    }
    $delete_cart = mysqli_query($con, "DELETE FROM cart WHERE user_id = '$user_id'") or die('query failed');
    echo '<script>alert("Order placed successfully!");
    window.location.href="shop.php";</script>';
    
}

}

?>

<link rel="stylesheet" href="./css/contactus.css">
<link rel="stylesheet" href="./css/checkout.css">
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
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

    <div class="main-container" style="align-items: flex-start; margin-bottom:80px;">

        <div class="display-order">
            <?php 
                $final_total = 0;
                $select_cart = mysqli_query($con, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
                if(mysqli_num_rows($select_cart) > 0){
                    while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                        $total_price = ($fetch_cart['quantity'] * $fetch_cart['price']);
                        $final_total += $total_price;
            ?>            
            
            <div class="checkout-order-display">
        
                <div class="checkout-order-display-details">
                    <p><i class="fa-solid fa-book" style="margin-right:10px; color:var(--color-secondary);"></i><?php echo $fetch_cart['title']; ?> <span> - <?php echo 'Rs' . $fetch_cart['price'].' ('.$fetch_cart['quantity']; ?>Qty)</span></p>
                </div>
            </div>


            <?php            
                    }
                } else {
                    echo '<h1>Your cart is empty</h1>';
                }
            ?>
            <div class="final-total"><span>Total - Rs <?php echo $final_total; ?></span></div>
        </div>

        


        <div class="checkout">
            <div class="form-container">
            <?php
                $select_cart = mysqli_query($con, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
                $select_user= mysqli_query($con, "SELECT * FROM users INNER JOIN cart ON users.id = cart.user_id") or die('query failed');


                if(mysqli_num_rows($select_cart) > 0){
                    if(($fetch_cart = mysqli_fetch_assoc($select_cart)) AND ($fetch_user = mysqli_fetch_assoc($select_user))) {
                ?>

                <form aiction="" method="POST">
                    <h3>Place your order</h3>
                    <div class="half-container">
                        <div class="input-field half-column">
                            <i class="fas fa-user"></i>
                            <input type="text" name="firstName" id="firstName" placeholder="First Name" pattern="[A-Za-z]+" required >
                        </div>

                        <div class="input-field half-column">
                            <i class="fas fa-user"></i>
                            <input type="text" name="lastName" id="lastName" placeholder="Last Name" pattern="[A-Za-z]+"  required >
                        </div>

                    </div>

                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="email" placeholder="Email"required>
                    </div>

                    <div class="input-field ">
                        <i class="fa-solid fa-phone"></i>
                        <!-- <input type="tel" name="phone" id="phone" placeholder="Phone Number" required > -->
                        <input type="tel" name="phone" id="phone" placeholder="Phone Number (e.g., 98XXXXXXXX)" required pattern="9[87][0-9]{8}" title="Please enter a valid Nepali phone number">

                    </div>


                    <div class="input-field ">
                        <i class="fa-solid fa-location-dot"></i>
                        <input type="text" name="address" id="address" placeholder="Address"required>
                    </div>



                    <div class="half-container">

                       

                      

                        <div class="input-field">
                        <i class="fa-solid fa-credit-card"></i>
                            <select id="country" name="payment_method" required>
                                <option value=" " disable selected>- Select Payment Method -</option>                              
                                
                                <option value="Bank Deposit">Khalti </option>
                            </select>
                        </div>


                    </div>

                    <input type="submit" value="Place Order" name="order-btn" class="btn" id="order-btn" ">
                    


                </form>
                <?php
                    }
                    } else {
                        echo '<h1>Your cart is empty</h1>';
                    }
                ?>        
                
            </div>
        </div>
    </div>
    
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<script src="khalti-client.js" type="text/javascript"></script>
<!-- <link rel="stylesheet" href="https://rawgit.com/google/code-prettify/master/styles/sons-of-obsidian.css" /> -->
<script type="text/javascript">
    $(function(){
        // just show the live js here.
        $.ajax({url: "khalti-client.js", success: function(resp){
            $("#js-code-here").text(resp.trim());
            addEventListener('load', f<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['order_btn'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   //$address = mysqli_real_escape_string($conn, 'flat no. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code']);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
         $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(', ',$cart_products);
   

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method'  AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   if($cart_total == 0){
      $message[] = 'your cart is empty';
   }else{
      if(mysqli_num_rows($order_query) > 0){
         $message[] = 'order already placed!'; 
      }else{
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'order placed successfully!';
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      }
   }
   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">home</a> / checkout </p>
</div>

<section class="display-order">

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo 'Rs.'.$fetch_cart['price'].'/-'.' x '. $fetch_cart['quantity']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   <div class="grand-total"> grand total : <span>Rs.<?php echo $grand_total; ?>/-</span> </div>

</section>

<section class="checkout">

   <form action="" method="post">
      <h3>place your order</h3>
      <div class="flex">
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" required placeholder="enter your name">
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="number" name="number" required placeholder="enter your number">
         </div>
         <div class="inputBox">
            <span>your email :</span>
            <input type="email" name="email" required placeholder="enter your email">
         </div>
         <div class="inputBox">
            <span>payment method :</span>
            <select name="method">
               <!-- <option value="cash on delivery">cash on delivery</option> -->
               <!-- <option value="credit card">credit card</option> -->
               <option value="Khalti">Khalti</option>
               <!-- <option value="paytm">eswea</option> -->
            </select>
         </div>
         <!-- <div class="inputBox">
            <span>address line 01 :</span>
            <input type="number" min="0" name="flat" required placeholder="e.g. flat no.">
         </div> -->
         <!-- <div class="inputBox">
            <span>address  :</span>
            <input type="text" name="street" required placeholder="Kathmandu">
         </div> -->
         <div class="inputBox">
            <span>city :</span>
            <input type="text" name="city" required placeholder="e.g. Kathmandu">
         </div>
         <!-- <div class="inputBox">
            <span>state :</span>
            <input type="text" name="state" required placeholder="e.g. maharashtra">
         </div> -->
         <div class="inputBox">
            <span>country :</span>
            <input type="text" name="country" required placeholder="e.g. nepal">
         </div>
         <!-- <div class="inputBox">
            <span>pin code :</span>
            <input type="number" min="0" name="pin_code" required placeholder="e.g. 123456">
         </div> -->
      </div>
      <input type="submit" value="order now" class="btn" name="order_btn">
   </form>

</section>









<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>unction(event) { PR.prettyPrint(); }, false);
        }, dataType: 'html'});
        $.get({url: "example.js", success: function(resp){
            $("#js-example-here").text(resp.trim());
            addEventListener('load', function(event) { PR.prettyPrint(); }, false);
        }, dataType: 'html'});
    });
</script>

<?php

include './templates/footer.php';

?>