<?php
session_start();
require 'config/config.php';


if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
  $stmt=$pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $stmt->execute();
  $result=$stmt->fetchAll();

  $blogId=$_GET['id'];

   $stmtcm=$pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
   $stmtcm->execute();
   $Cmresult=$stmtcm->fetchAll();

  $authRresult=[];
  if ($Cmresult) {
     foreach ($Cmresult as $key => $value) {
       $author_id=$Cmresult[$key]['author_id'];
       $authstmt=$pdo->prepare("SELECT * FROM users WHERE id=$author_id");
       $authstmt->execute();
       $authRresult[]=$authstmt->fetchAll();
     }
  }

  if ($_POST) {
    $comments=$_POST['comment'];
    $stmt=$pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES(:content,:author_id,:post_id)");
    $result=$stmt->execute(
      array('content' =>$comments,'author_id'=>$_SESSION['user_id'],'post_id'=>$blogId)
    );
    if($result)
    {
      header("location:blogdetail.php?id=".$blogId);
    }
  }
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
    <h4 class="p-4 text-center">My Blog</h4>
    <div class="row">
    

          <div class="col-md-12 col-lg-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <h4><?php echo $result[0]['title']; ?></h4>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img src="admin/images/<?php echo  $result[0]['image'];  ?>" style="height:250px !important;width:100% !important;" class="img-fluid">

                <p><?php echo  $result[0]['content']; ?></p>
                <h3>Comments</h3>
                <hr>
              </div>
              <!-- /.card-body -->
              <div class="card-footer card-comments">
                <?php
                if($Cmresult){
                ?>
                <div class="card-comment">
                  <?php
                  foreach ($Cmresult as $key => $value) {
                  ?>
                  <div class="comment-text">
                    <span class="username">
                      <?php echo $authRresult[$key][0]['name']; ?>
                      <span class="text-muted float-right"><?php echo $value['created_at']; ?></span>
                    </span><!-- /.username -->
                   <?php echo $value['content']; ?>
                  </div>
                <?php } ?>
                  <!-- /.comment-text -->
                </div>
              <?php } ?>
              </div>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="post">
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push">
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
      
          <!-- /.col -->

          <!-- /.col -->
        </div>
  </div>


<div class="card-footer mt-4">
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
</div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

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
