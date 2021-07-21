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
   $college_code= "";
   $description = "";
   $name_of_dean ="";
   $other_notes ="";

   $college_code_err= "";
   $description_err = "";
   $name_of_dean_err ="";
   $other_notes_err ="";

   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
      //COLLEGE CODE
      $input_college_code = trim($_POST["college_code"]);
      if(empty($input_college_code))
      {
        $college_code_err = "Please input a college code.";
      }
      else
      {
          $college_code = $input_college_code;
      }
      //end COLLEGE CODE

      //DESCRIPTION
      $input_description = trim($_POST["description"]);
      if(empty($input_description))
      {
        $description_err = "Please input a DESCRIPTION.";
      }
      else
      {
          $description = $input_description;
      }
      //END DESCRIPTION

      //NAME OF DEAN
      $input_name_of_dean = trim($_POST["name_of_dean"]);
      if(empty($input_name_of_dean))
      {
        $name_of_dean_err = "Please input dean's name.";
      }
      else
      {
          $name_of_dean = $input_name_of_dean;
      }
      //END NAME OF DEAN

      //OTHER NOTES
      $input_other_notes = trim($_POST["other_notes"]);
      if(empty($input_other_notes))
      {
        $other_notes_err = "Pls input a note.";
      }
      else
      {
          $other_notes = $input_other_notes;
      }
      //END OTHER NOTES

      if(empty($college_code_err) && empty($description_err) && empty($name_of_dean_err)&& empty($other_notes_err))
      {
        $sql = "INSERT INTO colleges (college_code, description, name_of_dean, other_notes) VALUE (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql))
        {
          mysqli_stmt_bind_param($stmt, "ssss", $param_college_code, $param_description,$param_name_of_dean, $param_other_notes);

          $param_college_code = $college_code;
          $param_description = $description;
          $param_name_of_dean = $name_of_dean;
          $param_other_notes = $other_notes;

          if(mysqli_stmt_execute($stmt))
          {
            header("location: index.php");
            exit();
          }
          else
          {
            echo "Oops! Something went wrong.";
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
            <h1>Creating New Member of Dean Information</h1>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <div class="form-group">
                    <label>College Code</label>
                    <input type="text" name="college_code" class="form-control <?php echo(!empty($college_code_err)) ? 'is-invalid': ''?>" value="<?=$college_code?>">
                    <span class="invalid-feedback"><?=$college_code_err ?></span>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control <?php echo(!empty($description_err)) ? 'is-invalid': ''?>" value="<?=$description?>">
                    <span class="invalid-feedback"><?=$description_err ?></span>
                </div>
                <div class="form-group">
                    <label>Name of Dean</label>
                    <input type="text" name="name_of_dean" class="form-control <?php echo(!empty($name_of_dean_err)) ? 'is-invalid': ''?>" value="<?=$name_of_dean?>">
                    <span class="invalid-feedback"><?=$name_of_dean_err ?></span>
                </div>
                <div class="form-group">
                    <label>Other Notes</label>
                    <input type="text" name="other_notes" class="form-control <?php echo(!empty($other_notes_err)) ? 'is-invalid': ''?>" value="<?=$other_notes?>">
                    <span class="invalid-feedback"><?=$other_notes_err ?></span>
                </div>
                <hr>
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
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
