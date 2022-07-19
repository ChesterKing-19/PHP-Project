<?php
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
    echo("<script>alert('You are already logged in. Please Loggout to create new profile.');
    window.location.href='View.php';</script>
    ");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
global $Username, $email, $phone, $Password, $C_Password, $role;
$Username= $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$Password = $_POST['pass'];
$C_Password = $_POST['C_pass'];
$role = $_POST['select'];
        //connecting db
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "cred";
    
        //creating connection
        $conn = mysqli_connect($servername,$username,$password,$database);
        function validUser(){
            if(!preg_match('/^[a-zA-Z]([0-9a-zA-Z]){2,10}$/',$GLOBALS['Username'])){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Invaild Username!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                return false;
            }
            return true;
        }
        function validEmail(){
            if(!preg_match('/^([_\-\.0-9a-zA-Z]+)@([_\-\.0-9a-zA-Z]+)\.([a-zA-Z]){2,7}$/',$GLOBALS['email'])){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Invaild Email!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                return false;
            }
            return true;
        }
        function validPhone(){
            if(!preg_match('/^([0-9]){10}$/',$GLOBALS['phone'])){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Invaild Phone No!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                return false;
            }
            return true;
        }
        function validPass(){
            if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/',$GLOBALS['Password'])){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Invaild Password!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                return false;
            }
            return true;
        }
        function validC_Pass(){
            if($GLOBALS['Password']!=$GLOBALS['C_Password']){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Password does not match!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                return false;
            }
            return true;
        }
        if(!$conn){
            die("sorry we failed to connect: ". mysqli_connect_error());
        }
        else{
            $sql_u = "SELECT * FROM emp WHERE username='$Username'";
            $res_u = mysqli_query($conn, $sql_u);
            if (mysqli_num_rows($res_u) > 0) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Username Already Exist!.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
              }
              else{
                if(validUser()&&validEmail()&&validPhone()&&validPass()&&validC_Pass()){
                $hash = password_hash($Password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `emp` (`Username`, `Email`, `PhoneNo`, `Password`, `Role`) VALUES ('$Username', '$email', '$phone', '$hash', '$role');";
                $result = mysqli_query($conn,$sql);
                if($result){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> You are Registered Successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                }
                }
              }
        }
    }
    ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <title>Signup Form</title>
</head>
<body>
    <!-- <div id="success" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> You are Registered Successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <div id="failure" class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Wrong Entries Found, Try Again.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div> -->
    <div id="Panel" class="container">
        <h1 id="head">Sign Up</h1>
        <form action="/PHP/Signup.php" method="post">
        <div class="form-group">
            <label for="username" id="un">Username</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Enter your Name" >
            <small id="namevalid" class="form-text text-muted invalid-feedback mt-2">
                Username must be 2-10 characters long and should not start with a number.
            </small>
        </div>
        <div class="form-group">
            <label for="email" id="eml">Email</label>
            <input type="email" class="form-control" name="email" id="email"  placeholder="Enter your Email" >
            <small id="emailvalid" class="form-text text-muted invalid-feedback mt-2">
                Invalid Email!
            </small>
        </div>
        <div class="form-group">
            <label for="phone" id="ph">Phone No</label>
            <input type="input" class="form-control" name="phone" id="phone" placeholder="Enter your Phone No" >
            <small id="phonevalid" class="form-text text-muted invalid-feedback mt-2">
                Invaild Phone No!
            </small>
        </div>
        <div class="form-group">
            <label for="pass" id="p1">Password</label>
            <input type="password" class="form-control" name="pass" id="pass"  placeholder="Create your Password" >
            <small id="passwordvalid" class="form-text text-muted invalid-feedback mt-2">
                Password must contains atleast 6 characters, <br>Atleast one number,<br>Includes both lower and uppercase letters and special characters
            </small>
        </div>
        <div class="form-group">
            <label for="C_pass" id="p2">Confirm Password</label>
            <input type="password" class="form-control" name="C_pass" id="C_pass"  placeholder="Confirm your Password" >
            <small id="C_passwordvalid" class="form-text text-muted invalid-feedback mt-2">
                Your password Doesn't match!
            </small>
        </div>
        <div>
            <label for="select" id="s1">Role</label>
            <select class="form-select" name="select" id="select">
                <option selected>Select your Role</option>
                <option value="Admin">Admin</option>
                <option value="User">User</option>
              </select>
        </div>
        <div>
            <button type="submit" id="bt">Register</button>
        </div>
    </form>
        <div id="log">
            <a href="Login.php">Already a User?</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>