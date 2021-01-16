<?php
session_start();
require '../config/config.php';


if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_SESSION['role']!=1) {
  header('Location: login.php');
}

if($_POST)
  {
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    if(empty($_POST['role']))
    {
      $role=0;
    }
    else
    {
       $role=1;
    }
    $stmt=$pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $user=$stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
      echo "<script> alert('Email is dupliate')</script>";
    }
    else{
        $stmt=$pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(:name,:email,:password,:role)");
      $result=$stmt->execute(
      array('name'=>$name,':email'=>$email,':password'=>$password,'role'=>$role)
      );
      if ($result) {
        echo "<script> alert('Add new users');window.location.href='user.php'; </script>";
      }
    }
  

  }
?>
<?php require 'header.php'; ?>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">User Table</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="" method="POST">
                 <div class="form-group">
                   <label for="name">Name</label>
                   <input type="text" name="name" id="name" class="form-control">
                 </div> 
                 <div class="form-group">
                   <label for="email">Email</label>
                   <input type="email" name="email" id="email" class="form-control">
                 </div> 
                  <div class="form-group">
                   <label for="email">Password</label>
                   <input type="password" name="password" id="password" class="form-control">
                 </div> 
                 <div class="form-group form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" name="role" value="1" type="checkbox">Admin
                  </label>
                </div>
                <div>
                  <input type="submit" name="submint" class="btn btn-success" value="SUBMIT">
                </div>
                </form>
                 
              </div>
              <!-- /.card-body -->
          
            </div>
            <!-- /.card -->

          
            <!-- /.card -->
          </div>
          <!-- /.col -->
       
          <!-- /.col -->
        </div>
        <!-- /.row -->
       
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

<?php

  require 'footer.php';

?>