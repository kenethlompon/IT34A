<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
           $fullName = $_POST["fullname"];
           $city_municipality=$_POST["city_municipality"];
           $state_province = $_POST["state_province"];
           $postalcode = $_POST["postalcode"];
           $country = $_POST["country"];
           $phoneNo = $_POST["phoneNo"];
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];

           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();

           if (empty($fullName) OR empty($city_municipality) OR empty($state_province) OR empty($postalcode) OR empty($country) OR empty($phoneNo) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password does not match");
           }
           
           require_once "database.php";
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           }else{

            $sql = "INSERT INTO users (fullname,city_municipality,state_province,postalcode,country,phoneNo, email, password) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"ssssssss",$fullName,$city_municipality,$state_province,$postalcode,$country,$phoneNo, $email, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.<a href= login.php>Login ";

            }else{
                die("Something went wrong");
            }
           }
        }
        ?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <h3>Register<h3>
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:">
            </div>
            <div class="form-group">
                <input type="emamil" class="form-control" name="city_municipality" placeholder="City/Municipality:">
            </div>
            <div class="form-group">
                <input type="emamil" class="form-control" name="state_province" placeholder="State/Province:">
            </div>
            <div class="form-group">
                <input type="emamil" class="form-control" name="postalcode" placeholder="Postal Code:">
            </div>
            <div class="form-group">
                <input type="emamil" class="form-control" name="country" placeholder="Country:">
            </div>
            <div class="form-group">
                <input type="emamil" class="form-control" name="phoneNo" placeholder="PhoneNo:">
            </div>
            <div class="form-group">
                <input type="emamil" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
            </div>
            
       <div class="mb-1">
         <label class="form-label">Select User Type:</label>
       </div>
       <select class="form-select mb-3"
               name="usertype" 
               aria-label="Default select example">
           <option selected value="user">user</option>
           <option value="admin">admin</option>
       </select>
            
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
                
            </div>
        </form>
        <div>
        <div><p>Already have an account?<a href="login.php">Login Here</a></p></div>
      </div>
    </div>
</body>
</html>