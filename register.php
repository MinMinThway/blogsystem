<?php
  session_start();
  require 'config/config.php';  
  if ($_POST) {

    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password'])<4){
        if (empty($_POST['name'])) {
          $nameError='Name cannot null';
        }
        if (empty($_POST['email'])) {
          $emailError='Email cannot null';
        }

        if (empty($_POST['password'])) {
          $passwordError='Password cannot null';
        }
        if (strlen($_POST['password'])<4) {
          $passwordError='Password should at least 4 character';
        }

    } 
    else
    {
      $name=$_POST['name'];
      $email=$_POST['email'];
      $password=password_hash($_POST['password'], PASSWORD_DEFAULT);
      $stmt=$pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stmt->bindValue('email',$email);
      $stmt->execute();
      $users=$stmt->fetch(PDO::FETCH_ASSOC);

      if ($users) {
        echo "<script> alert('User email is duplicate!') </script>";
      }
      else
      {
        $stmt=$pdo->prepare("INSERT INTO users(name,email,password,role) VALUES (:name,:email,:password,:role)");
        $result=$stmt->execute(
          array(
            'name'=>$name,
            'email'=>$email,
            'password'=>$password,
            'role'=>0,
          )
        );
        if ($result) {
         echo "<script> alert('Successfully Register,You can now login');window.location.href='login.php';</script>";
        }
      }
   } 
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index2.html"><b>Blog User</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">

      <form action="register.php" method="post">
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
        <p style="color: red;"><?php echo empty($nameError)?'':'*'.$nameError; ?></p>
         <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Name" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <p style="color: red;"><?php echo empty($emailError)?'':'*'.$emailError; ?></p>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <p style="color: red;"><?php echo empty($passwordError)?'':'*'.$passwordError; ?></p>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary d-inline-block" style="width: 100%;">Register</button>
            <a href="login.php" class="d-inline-block btn btn-light" style="width: 100%;">Login</a>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- /.social-auth-links -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
