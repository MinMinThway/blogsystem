<?php
session_start();
require 'config/config.php';


if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
  
            if (!empty($_GET['pageno'])) {
                  $pageno=$_GET['pageno'];
                }
                else
                {
                  $pageno=1;
                }
                $numOfrecs=6;
                $numOfset=($pageno-1)*$numOfrecs;

                if (empty($_POST['search'])) {
                  $stmt=$pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult=$stmt->fetchAll();

                  $total_pages=ceil(count($rawResult)/$numOfrecs);

                  $stmt=$pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $numOfset,$numOfrecs");
                  $stmt->execute();
                  $result=$stmt->fetchAll();
                }
                else
                {
                  $searchKey=$_POST['search'];

                  $stmt=$pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult=$stmt->fetchAll();

                  $total_pages=ceil(count($rawResult)/$numOfrecs);

                  $stmt=$pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $numOfset,$numOfrecs");
                  $stmt->execute();
                  $result=$stmt->fetchAll();
                  }


// $stmt=$pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
// $stmt->execute();
// $result=$stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">

  <div class="container-fluid bg-light">
    <h3 class="text-center my-3 p-2 bg-light pt-3">Blog Site</h3>

   <form class="form-inline ml-3" action="index.php" method="post" style="position: absolute;right: 20px;top: 20px;">
      <div class="input-group input-group-sm">
        <input name="search" class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

   <div class="row">
     <?php foreach ($result as $value){ ?>
     <div class="col-sm-12 col-md-4 col-lg-4">
        <div class="card">
          <div class="card-header">
            <h4 class="text-center"><?php echo $value['title']; ?></h4>
          </div>
          <div class="card-body">
            <a href="blogdetail.php?id=<?php echo $value['id']; ?>"><img src="admin/images/<?php echo $value['image'];  ?>" style="height:250px !important;width:100% !important;" class="img-fluid"></a>
          </div>
        </div>
     </div>
   <?php } ?>
   <nav aria-label="Page navigation example"  style="margin-left:1060px;">
           <ul class="pagination">
            <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
            <li class="page-item <?php if($pageno<=1) { echo "disable";}?> ">
              <a class="page-link" href="<?php if($pageno<=1){echo '#';} else{ echo "?pageno=".($pageno-1); } ?>">Previous</a>
            </li>
            <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
            <li class="page-item <?php if($pageno >= $total_pages) { echo 'disable'; } ?>">
              <a class="page-link" href="<?php if($pageno >= $total_pages) { echo '#'; } else{ echo "?pageno=".($pageno+1); } ?>">Next</a>
            </li>
            <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
          </ul>
      </nav>
   </div>
  </div>
  <aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <footer class="main-footer" style="margin-left: 0px !important;">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
    <a href="logout.php" class="btn btn-light">Logout</a>
    </div>
    <!-- Default to the left -->
    <strong >Copyright &copy; 2020-2021 <a href="minminthway.me">Min Min Thway</a>.</strong> All rights reserved.
  </footer>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
