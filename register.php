<?php include_once("config.php")?>
<?php
   $username = "";
   $password = "";
   $confirm_password = "";

   $username_err = "";
   $password_err = "";
   $confirm_password_err = "";

   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
      $input_username = trim($_POST["username"]); 
        if(empty($input_username))
        {
          $username_err = "Please input a username.";
        }
        elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST['username'])))
        {
            $username_err = "Username can only contain letters, numbers, and underscore.";
        }
        else
        {
          $sql = "SELECT id FROM users WHERE username=?";
          if($stmt = mysqli_prepare($link, $sql))
          {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = trim($_POST['username']);
            
            if(mysqli_stmt_execute($stmt))
            {
              $result = mysqli_stmt_get_result($stmt);
              if(mysqli_num_rows($result) == 1)
              {
                $username_err = "This username is already taken.";
              }
              else
              {
                $username = trim($_POST['username']);  
              }
            }
            else
            {
              echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
          } 
        }
        //password 
        if(empty(trim($_POST['password'])))
        {
          $password_err = "Please input a password.";
        }
        elseif(strlen(trim($_POST['password'])) < 6)
        {
          $password_err = "Please is atleast 6 characters.";
        }
        else
        {
            $password = trim($_POST['password']);
        }
        //confirm password
        if(empty(trim($_POST['confirm_password'])))
        {
          $confirm_password_err = "Please input password again for confirmation.";
        }
        else
        {
            $confirm_password = trim($_POST['confirm_password']);
            if(empty($password_err) && ($password != $confirm_password))
            {
              $confirm_password_err = "Password and password confirmation did not match.";
            }
        }

        if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
        {
          $sql = "INSERT INTO users (username, password) VALUE (?, ?)";

          if($stmt = mysqli_prepare($link, $sql))
          {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if(mysqli_stmt_execute($stmt))
            {
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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.css"/>
    <title>Sample CRUD</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2>Sign Up</h2>
            <p>Please fill this form to create an account.</p>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control <?php echo(!empty($username_err)) ? 'is-invalid': ''?>" value="<?=$username?>">
                    <span class="invalid-feedback"><?=$username_err ?></span>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control <?php echo(!empty($password_err)) ? 'is-invalid': ''?>" value="<?=$password?>">
                    <span class="invalid-feedback"><?=$password_err ?></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo(!empty($confirm_password_err)) ? 'is-invalid': ''?>" value="<?=$password?>">
                    <span class="invalid-feedback"><?=$confirm_password_err ?></span>
                </div>
                
                <hr>
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </form>
            <p>
              Already have an account?
              <a href="login.php">Login Here</a>
            </p>              
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
