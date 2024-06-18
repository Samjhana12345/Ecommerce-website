<?php

$title = 'Sign up';
include './templates/header.php';
include './php/config.php';


if(isset($_POST['submit'])){

$fName = mysqli_real_escape_string($con, $_POST['firstName']);
$lName = mysqli_real_escape_string($con, $_POST['lastName']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$phone = mysqli_real_escape_string($con, $_POST['phone']);
$address = mysqli_real_escape_string($con, $_POST['address']);
$password = md5($_POST['password']);


$select_users = mysqli_query($con, "SELECT * FROM users WHERE email = '$email'") or die('query failed');

if(mysqli_num_rows($select_users) > 0){
    echo '<script>alert("Email already exists!"); 
    window.location.href="register.php";</script>';
}else{
    $sql = "INSERT INTO users (firstName, lastName, email, phone, address, password) VALUES ('$fName', '$lName', '$email', '$phone', '$address', '$password')";
    echo '<script>alert("Registration Successful!"); 
    window.location.href="login.php";</script>';
}
$result = mysqli_query($con, $sql);
}

mysqli_close($con);

?>

    <div class="main-container">
        <div class="container">
            <div class="form-container">
                <h2>Create an Account</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


                    <div class="half-container">
                        <div class="input-field half-column">
                            <i class="fas fa-user "></i>
                            <input type="text" name="firstName" id="firstName" placeholder="First Name" pattern="[A-Za-z]+" required>
                        </div>

                        <div class="input-field half-column">
                            <i class="fas fa-user "></i>
                            <input type="text" name="lastName" id="lastName" placeholder="Last Name" pattern="[A-Za-z]+" required>
                        </div>

                    </div>

                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="email" placeholder="Email" required>
                    </div>

                    <div class="input-field ">
                        <i class="fa-solid fa-phone"></i>
                        <input type="tel" name="phone" id="phone" placeholder="Phone Number (e.g., 98XXXXXXXX)" required pattern="9[87][0-9]{8}" title="Please enter a valid Nepali phone number">
                    </div>

                    <div class="input-field ">
                        <i class="fa-solid fa-location-dot"></i>
                        <input type="text" name="address" id="address" placeholder="Address" required>
                    </div>


                   

                       
                    

                   


                    <div class="input-field">
                        <i class="fas fa-key"></i>
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <i class="far fa-eye" id="togglePassword"></i>
                    </div>

                    <div class="input-field">
                        <i class="fas fa-key"></i>
                        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
                    </div>
                    
                    <input type="submit" value="Create Account" name="submit" class="btn" id="submit-btn">
                    <p>Already have an account?<a href="./login.php"> Login now</a></p>
                </form>
            </div>
        </div>

    </div>


<?php
include './templates/newsletter.php';
include './templates/footer.php';
?>