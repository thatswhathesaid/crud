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
  
  $college_code = "";
  $description = "";
  $name_of_dean = "";
  $other_notes = "";

  $college_code_err = "";
  $description_err = "";
  $name_of_dean_err = "";
  $other_notes = "";

  if(isset($_POST['id']) && !empty($_POST['id']))
  {
      $id = $_POST['id'];

      //College Code
      $input_college_code = trim($_POST["college_code"]);
      if(empty($input_college_code))
      {
        $college_code_err = "Please input a name.";
      }
      else{
        $college_code = $input_college_code;
      }

      //Description
      $input_description = trim($_POST["description"]);
      if(empty($input_description))
      {
        $description_err = "Please input a description.";
      }
      else{
        $description = $input_description;
      }

      //Name of Dean
      $input_name_of_dean = trim($_POST["name_of_dean"]);
      if(empty($input_name_of_dean))
      {
        $name_of_dean_err = "Please input a name  dean.";
      }
      else
      {
        $name_of_dean = $input_name_of_dean;
      }

      //Other Notes
      $input_other_notes = trim($_POST["other_notes"]);
      if(empty($input_other_notes))
      {
        $other_notes_err = "Please input a value other notes.";
      }
      else
      {
        $other_notes = $input_other_notes;
      }

      if(empty($college_code_err) && empty($description_err)
        && empty($name_of_dean_err) && empty($other_notes_err))
      {
        $sql = "UPDATE colleges SET college_code = ?, description = ?, name_of_dean = ?, other_notes = ?
          WHERE college_id = ?";
        if($stmt = mysqli_prepare($link, $sql))
        {
          mysqli_stmt_bind_param($stmt, "ssssi", $param_college_code, $param_description, 
          $param_name_of_dean, $param_other_notes, $param_id);

          $param_id = $id;
          $param_college_code = $college_code;
          $param_description = $description;
          $param_name_of_dean = $name_of_dean;
          $param_other_notes = $other_notes;

          $param_id = trim($_POST['id']);
          if(mysqli_stmt_execute($stmt))
          {
            header("location: index.php");
            exit();
          }
          else
          {
            echo "Oops! Something went wrong. Try again later.";
          }           
        }
        mysqli_stmt_close($stmt);
      }
      mysqli_close($link);
  }
  else
  {
    if(isset($_GET['id']) && !empty(trim($_GET['id'])))
    {
      $id = trim($_GET['id']);
      $sql = "SELECT * FROM colleges WHERE college_id = ?";
      if($stmt = mysqli_prepare($link, $sql))
      {
          mysqli_stmt_bind_param($stmt, "i", $param_id);

          $param_id = trim($_GET['id']);

          if(mysqli_stmt_execute($stmt))
          {
              $result = mysqli_stmt_get_result($stmt);
              if(mysqli_num_rows($result) == 1)
              {
                  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                  $college_code = $row['college_code'];
                  $description = $row['description'];
                  $name_of_dean = $row['name_of_dean'];
                  $other_notes = $row['other_notes'];
                  $id = $row['college_id'];
              }
              else{
                header("location: error.php");
                exit();
              }
          }
          else
          {
              echo "Oops! Something went wrong. Please try again later.";
          }
      }
      mysqli_stmt_close($stmt);

      mysqli_close($link);
    }
    else
    {
        header("location: error.php");
        exit();
    }
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
    <title>Activity CRUD</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <h1>Modify College Information</h1>

          <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">
            <div class="form-group">
                <label>College Code</label>
                <input type="text" name="college_code" class="form-control
                <?php echo (!empty($college_code_err)) ? 'is-invalid': ''?>" value="<?= $college_code ?>">
                <span class="invalid-feedback"><?= $college_code_err ?></span>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control
                <?php echo (!empty($description_err)) ? 'is-invalid': ''?>"> <?= $description ?></textarea>
                <span class="invalid-feedback"><?= $description_err ?></span>
            </div>
            <div class="form-group">
                <label>Name of Dean</label>
                <textarea name="name_of_dean" class="form-control
                <?php echo (!empty($name_of_dean_err)) ? 'is-invalid': ''?>"> <?= $name_of_dean ?></textarea>
                <span class="invalid-feedback"><?= $name_of_dean_err ?></span>
            </div>
            <div class="form-group">
                <label>Other Notes</label>
                <textarea name="other_notes" class="form-control
                <?php echo (!empty($other_notes_err)) ? 'is-invalid': ''?>"> <?= $other_notes ?></textarea>
                <span class="invalid-feedback"><?= $other_notes_err ?></span>
            </div>
            <hr>
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>

        </div>
      </div>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="js/bootstrap.bundle.min.js"></script>

  </body>
</html>