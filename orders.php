<?php
$title = 'Orders';
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


?>
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
        crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
        crossorigin="anonymous"></script>
<link rel="stylesheet" href="./css/orders.css">

   
<div class="main-container book-main">

    <?php include './templates/user_navigation.php' 

    ?>
   
    <div class="orders-container">
        
            <?php
                $order_query = mysqli_query($con, "SELECT * FROM orders WHERE user_id = $user_id") or die('query failed');
                
                if(mysqli_num_rows($order_query) > 0){
                    while($fetch_orders = mysqli_fetch_assoc($order_query)){

            ?>

            <div class="order-box">
                <p> placed on : <span><?php echo $fetch_orders['placed_date']; ?></span> </p>
                <p> name : <span><?php echo $fetch_orders['name']; ?></span> </p>
                <p> number : <span><?php echo $fetch_orders['phone']; ?></span> </p>
                <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                <p> address : <span><?php echo $fetch_orders['address']; ?></span> </p>
                <p> payment method : <span><?php echo $fetch_orders['payment_method']; ?></span> </p>
                <p> your orders : <span><?php echo $fetch_orders['total_books']; ?></span> </p>
                <p> total price : <span>Rs<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
                <p> Payment status : <span style="color:<?php if($fetch_orders['order_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; } ?>;"><?php echo $fetch_orders['order_status']; ?></span> </p>
                 <a href="#" data-amount="<?php echo $fetch_orders['total_price'] ?>" id='payment-button-1' class="btn btn-primary pay-khalti">Pay with Khalti</a>     

            </div>

            <?php
            }
            }else{
                echo '<p class="empty">no orders placed yet!</p>';
            }
            ?>
            
            <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<script src="khalti-client.js" type="text/javascript"></script>
<!-- <link rel="stylesheet" href="https://rawgit.com/google/code-prettify/master/styles/sons-of-obsidian.css" /> -->
<script type="text/javascript">
    $(function(){
        // just show the live js here.
        $.ajax({url: "khalti-client.js", success: function(resp){
            $("#js-code-here").text(resp.trim());
            addEventListener('load', function(event) { PR.prettyPrint(); }, false);
        }, dataType: 'html'});
        $.get({url: "example.js", success: function(resp){
            $("#js-example-here").text(resp.trim());
            addEventListener('load', function(event) { PR.prettyPrint(); }, false);
        }, dataType: 'html'});
    });
</script>
                
    </div>

        
        
</div>

<?php

    include './templates/newsletter.php';
    include './templates/footer.php';

?>