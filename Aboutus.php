<?php
$title = 'About Us';
session_start();

if(isset($_SESSION['admin_id'])){
  $user_id = $_SESSION['admin_id'];
  include './templates/admin_header.php';
} elseif(isset($_SESSION['user_id'])){
  $user_id = $_SESSION['user_id'];
  include './templates/user_header.php';
}   else
  include './templates/header.php';
?>
     <link rel="stylesheet" href="./css/aboutus.css">


<div class="main-container">



<div class="topic">About Us</div>
<br>
<div class ="description">
    <p>Online Book Store has opened up entryway for universe of Books to make perusing lovable and  advantageous for all by obtaining all aver the planet.
    Eden Books can follow its bookselling legacy to the early piece of the twentieth  century when it was laid out
      in the core of the business region of that time which was Sapallumcheen Rd Hope, British.</p>
   
        


<br>
</div>
<div class="row">

<div class="col">
     <img src="src/book.jpg" alt="Mission" width:"100%;">
 </div>
 <div class="col"><img src="src/img1.jpg" alt="Vison" width:"100%;"></div>
</div>
</div>  

<br>
<?php
include './templates/newsletter.php';
include './templates/footer.php';
?>

    

    