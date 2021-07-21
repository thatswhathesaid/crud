<?php
  session_start();

  if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true)
  {
    header("location: login.php");
    exit();
  }
  include("config.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style type="text/css">
     .container
     {
      padding-top: 65px;
     }
    </style>

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <title>Activity CRUD</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-5 offset-md-7 pull-right" >
        <p>
          Hi <b><?= htmlspecialchars($_SESSION['username'])?></b>. Welcome to our site. <br>
          <a href="reset-password.php" class="btn btn-warning">Reset Password</a>
          <a href="logout.php" class="btn btn-danger">Logout</a>
        </p>
      </div>
      </div>
      <div class="row">
        <div class="col-md-10 offset-md-1">
            <h1>Activity College Dean CRUD</h1>
            <a href="add.php" class="btn btn-primary float-end">
              <i class="fas fa-plus"></i>Add Record
            </a>

            <?php $qry = "SELECT * FROM colleges"; ?>
            <?php if($result = mysqli_query($link, $qry)): ?>
              <?php if(mysqli_num_rows($result) > 0): ?>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">College ID</th>
                      <th scope="col">College Code</th>
                      <th scope="col">Description</th>
                      <th scope="col">Name of Dean</th>
                      <th scope="col">Other Notes</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while($rows = mysqli_fetch_array($result)): ?>
                      <tr>
                        <td scope="row"><?php echo $rows['college_id']; ?></td>
                        <td><?php echo $rows['college_code']; ?></td>
                        <td><?php echo $rows['description']; ?></td>
                        <td><?php echo $rows['name_of_dean']; ?></td>
                        <td><?php echo $rows['other_notes']; ?></td>                        

                        <td>
                          <a class="btn btn-secondary btn-sm" href="show.php?id=<?= $rows['college_id']; ?>">
                          <i class="far fa-eye"></i></a>
                          <a class="btn btn-warning btn-sm" href="update.php?id=<?= $rows['college_id']; ?>">
                          <i class="fas fa-pencil-alt"></i></a>
                          <a class="btn btn-danger btn-sm" href="delete.php?id=<?= $rows['college_id']; ?>">
                          <i class="fas fa-trash"></i></a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              <?php else: ?>
                <br><br>
                <div class="alert alert-danger"><em>No Records Found</em></div>
              <?php endif; ?>
            <?php else: ?>
              <br><br>
              <div class="alert alert-danger"><em>Something Went Wrong. Query Related Error</em></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html>