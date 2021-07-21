<?php 
  session_start();

  if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true)
  {
    header("location: index.php");
    exit();
  }

  include_once("config.php")
?>
<?php
   $username = "";
   $password = "";

   $username_err = "";
   $password_err = "";
   $login_err = "";

   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
      //username login
      if(empty(trim($_POST["username"])))
      {
        $username_err = "Please enter a username.";
      }
      else
      {
        $username = trim($_POST["username"]);
      }

      //password login
      if(empty(trim($_POST["password"])))
      {
        $password_err = "Please enter a password.";
      } 
      else
      {
        $password = trim($_POST["password"]);
      }

      if(empty($username_err) && empty($password_err))
      {
        $sql = "SELECT id, username, password FROM users WHERE username = ? ";
        if($stmt = mysqli_prepare($link, $sql))
        {
          mysqli_stmt_bind_param($stmt, "s", $param_username);
          $param_username = $username;

          if(mysqli_stmt_execute($stmt))
          {
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) ==1)
            {
              mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
              if(mysqli_stmt_fetch($stmt))
              {
                if(password_verify($password, $hashed_password))
                {
                  $_SESSION['logged_in'] = true;
                  $_SESSION['id'] = $id;
                  $_SESSION['username'] = $username;

                  header("location: index.php");
                }
                else
                {
                  $login_err = "Invalid username or password";
                }
              }
            }
            else
            {
              $login_err = "Invalid username or password";
            }  
          }
          else
          {
            echo "Oops! Something went wrong.";
          }

          mysqli_stmt_close($stmt);
        }
      }
     mysqli_close($link); 
    }//main end if  
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
            <h2>Login</h2>

            <?php
              if(!empty($login_err))
              {
                echo '<div class="alert alert-danger">'.$login_err.'</div>';
              }

            ?>

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
                <hr>
                <input type="submit" class="btn btn-primary" value="Login">
            </form>
            <p>
              <a href="register.php">Signup here</a>
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
