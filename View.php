<?php  
session_start();
if(!isset($_SESSION['loggedin'])){
    echo("<script>alert('Login to access the view page.');
    window.location.href='Login.php';</script>
    ");
}
$update = false;
$delete = false;
global $V_user,$V_email,$V_phone;
// Connect to the Database 
$servername = "localhost";
$username = "root";
$password = "";
$database = "cred";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Die if connection was not successful
if (!$conn){
    die("Sorry we failed to connect: ". mysqli_connect_error());
}

if(isset($_GET['delete'])){
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `emp` WHERE `S.No` = $sno";
  $result = mysqli_query($conn, $sql);
  if($result){
    header('location:View.php');
  }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
if (isset( $_POST['snoEdit'])){
global $sno,$us,$email,$phoneno,$V_user,$V_email,$V_phone;
  // Update the record
    $sno = $_POST["snoEdit"];
    $us = $_POST["usernameEdit"];
    $email = $_POST["emailEdit"];
    $phoneno = $_POST["phoneEdit"];
    $role = $_POST['roleEdit'];
    function validUser(){
      if(!preg_match('/^[a-zA-Z]([0-9a-zA-Z]){2,10}$/',$GLOBALS['us'])){
          $GLOBALS['V_user'] = true;
          return false;
      }
      return true;
  }
  function validEmail(){
      if(!preg_match('/^([_\-\.0-9a-zA-Z]+)@([_\-\.0-9a-zA-Z]+)\.([a-zA-Z]){2,7}$/',$GLOBALS['email'])){
        $GLOBALS["V_email"] = true;
          return false;
      }
      return true;
  }
  function validPhone(){
      if(!preg_match('/^([0-9]){10}$/',$GLOBALS['phoneno'])){
          $GLOBALS['V_phone'] = true;
          return false;
      }
      return true;
  }
  if(validUser()&&validEmail()&&validPhone()){
    $sql = "UPDATE `emp` SET Username = '$us' , Email = '$email' , PhoneNo = '$phoneno' , Role = '$role'  WHERE `S.No` = '$sno'";
  $result = mysqli_query($conn, $sql);
  if($result){
    $update = true;
}
else{
    echo "We could not update the record successfully";
}

  }
}
}
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="view1.css">

  <title>View</title>

</head>

<body>
  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Record</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="/PHP/View.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" id="usernameEdit" name="usernameEdit" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="text" class="form-control" id="emailEdit" name="emailEdit" aria-describedby="emailHelp">
            </div> 
            <div class="form-group">
              <label for="phoneno">PhoneNo</label>
              <input type="text" class="form-control" id="phoneEdit" name="phoneEdit" aria-describedby="emailHelp">
            </div> 
            <div>
            <label for="role" id="s1">Role</label>
            <select class="form-select" name="roleEdit" id="roleEdit">
                <option value="Admin">Admin</option>
                <option selected value="User">User</option>
              </select>
        </div>
          </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php
  if($delete){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Record has been deleted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($update){
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Record has been updated successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <?php
  if($GLOBALS["V_user"]){
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
    <strong>Error!</strong> Invalid Username!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  if($GLOBALS["V_email"]){
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
    <strong>Error!</strong> Invalid Email!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  if($GLOBALS["V_phone"]){
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
    <strong>Error!</strong> Invalid PhoneNo!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
  }
  ?>
  <div id="uViewPanel" class="container">
    <p class="h1">Username: <?php echo $_SESSION["username"];?></p>
    <p class="h2">Role: <?php echo $_SESSION["role"];?></p>
    <div>
    <a name="" id="bt" class="btn btn-primary" href="Logout.php" role="button">Logout</a>
  </div>
    <table class="table" id="myTable">
      <thead>
        <tr class="tHead">
          <th scope="col">S.No</th>
          <th scope="col">Username</th>
          <th scope="col">Email</th>
          <th scope="col">PhoneNo</th>
          <th scope="col">Role</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if($_SESSION["role"]=="Admin"){
          $sql = "SELECT * FROM `emp` WHERE Role = 'User'";
          $result = mysqli_query($conn, $sql);
          $sno = 0;
          while($row = mysqli_fetch_assoc($result)){
            $sno++;
            echo "<tr>
            <th scope='row'>". $sno."</th>
            <td>". $row['Username'] . "</td>
            <td>". $row['Email'] . "</td>
            <td>". $row['PhoneNo'] . "</td>
            <td>". $row['Role'] . "</td>
            <td> <button class='edit btn btn-sm btn-primary' id=".$row['S.No'].">Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['S.No'].">Delete</button>  </td>
          </tr>";
        } 
        }else{
            $sql = "SELECT * FROM `emp` WHERE Role = 'User'";
          $result = mysqli_query($conn, $sql);
          $sno = 0;
          while($row = mysqli_fetch_assoc($result)){
            $sno++;
            echo "<tr>
            <th scope='row'>". $sno."</th>
            <td>". $row['Username'] . "</td>
            <td>". $row['Email'] . "</td>
            <td>". $row['PhoneNo'] . "</td>
            <td>". $row['Role'] . "</td>
            <td> <button class='edit btn btn-sm btn-primary' id=".$row['S.No']." disabled>Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['S.No']." disabled>Delete</button>  </td>
          </tr>";
        }           
        }
          ?>
      </tbody>
    </table>
  </div>
  <hr>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="Updatescript1.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();

    });
  </script>
  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        tr = e.target.parentNode.parentNode;
        user = tr.getElementsByTagName("td")[0].innerText;
        email = tr.getElementsByTagName("td")[1].innerText;
        phone = tr.getElementsByTagName("td")[2].innerText;
        role = tr.getElementsByTagName("td")[3].innerText;
        console.log(user,email,phone);
        usernameEdit.value = user;
        emailEdit.value = email;
        phoneEdit.value = phone;
        roleEdit.value = role;
        snoEdit.value = e.target.id;
        console.log(e.target.id)
        $('#editModal').modal('toggle');
      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        sno = e.target.id.substr(1);

        if (confirm("Are you sure you want to delete this record!")) {
          console.log("yes");
          window.location = `/PHP/View.php?delete=${sno}`;
        }
        else {
          console.log("no");
        }
      })
    })
  </script>
</body>

</html>