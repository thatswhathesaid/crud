<?php 
  session_start();

  if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true)
  {
    header("location: login.php");
    exit();
  }

  include("config.php");
?>
<?php
   $new_password = "";
   $confirm_password = "";

   $new_password_err = "";
   $confirm_password_err = "";

   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
      //password 
      if(empty(trim($_POST['new_password'])))
      {
        $new_password_err = "Please input a new password.";
      }
      elseif(strlen(trim($_POST['new_password'])) < 6)
      {
        $new_password_err = "Please is atleast 6 characters.";
      }
      else
      {
          $new_password = trim($_POST['new_password']);
      }
      //confirm password
      if(empty(trim($_POST['confirm_password'])))
      {
        $confirm_password_err = "Please input password again for confirmation.";
      }
      else//for confirmation of new password
      {
          $confirm_password = trim($_POST['confirm_password']);
          if(empty($new_password_err) && ($new_password != $confirm_password))
          {
            $confirm_password_err = "New Password and password confirmation did not match.";
          }
      }

      if(empty($new_password_err) && empty($confirm_password_err))
      {
        $sql = "UPDATE users SET password = ? WHERE id = ? ";

        if($stmt = mysqli_prepare($link, $sql))
        {
          mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

          $param_password = password_hash($new_password, PASSWORD_DEFAULT);
          $param_id = $_SESSION['id'];

          if(mysqli_stmt_execute($stmt))
          {
            session_destroy();
            header("location: login.php");
            exit();
          }
          else
          {
            echo "Oops! Something went wrong. Pls try again";
          }
        }
        mysqli_stmt_close($stmt);
      }
      mysqli_close($link);
    }  
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->

    <style type="text/css">
     .container
     {
      padding-top: 80px;
     }
    </style>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.css"/>
    <title>Sample CRUD</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2>Reset Password</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control <?php echo(!empty($new_password_err)) ? 'is-invalid': ''?>" value="<?=$new_password?>">
                    <span class="invalid-feedback"><?=$password_err ?></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo(!empty($confirm_password_err)) ? 'is-invalid': ''?>" value="<?=$confirm_password?>">
                    <span class="invalid-feedback"><?=$confirm_password_err ?></span>
                </div>
                
                <hr>
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>              
        </div>
      </div>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="js/bootstrap.bundle.min.js"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
