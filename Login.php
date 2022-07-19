<?php
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
    echo("<script>alert('You are already logged in.');
    window.location.href='View.php';</script>
    ");
}
$login = false;
$showError = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $Username = $_POST['username'];
    $Password = $_POST['pass'];

    //connecting db
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "cred";

    //creating connection
    $conn = mysqli_connect($servername,$username,$password,$database);
    if(!$conn){
        die("sorry we failed to connect: ". mysqli_connect_error());
    }
    else{
        $sql= "SELECT * FROM emp WHERE Username = '$Username'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if ($num == 1){
            while($row=mysqli_fetch_assoc($result)){
                if(password_verify($Password, $row['Password'])){
                        $role = $row['Role'];
                        $login = true;
                        
                        $_SESSION['loggedin']=true;
                        $_SESSION['username']=$Username;
                        $_SESSION['role'] = $role;
                        header("Location: View.php");
                }
                else{
                    $showError = "Invalid Credentials1";
                }
            }
        } 
        else{
            $showError = "Invalid Credentials";
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
    <title>Login Form</title>
</head>
<body>
<?php
      if($login){
    echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Loggedin Successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div> ';
    }
    if($showError){
        echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> '. $showError.'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> ';
    
    }
?>

    <div id="LoginPanel" class="container">
        <h1 id="head">LogIn</h1>
        <form action="/PHP/Login.php" method="post">
        <div class="form-group">
            <label for="username" id="un">Username</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Enter your Name" >
            <small id="namevalid" class="form-text text-muted invalid-feedback mt-2">
                Username must be 2-10 characters long and should not start with a number.
            </small>
        </div>
        <div class="form-group">
            <label for="pass" id="p1">Password</label>
            <input type="password" class="form-control" name="pass" id="pass"  placeholder=" Create your Password" >
            <small id="passwordvalid" class="form-text text-muted invalid-feedback mt-2">
                Password must contains atleast 6 characters, <br>Atleast one number,<br>Includes both lower and uppercase letters and special characters
            </small>
        </div>
        <div>
            <button type="submit" id="bt">Login</button>
        </div>
    </form>
        <div id="log">
            <a href="Signup.php">New User?</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script>
        if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}
    </script>
</body>
</html>