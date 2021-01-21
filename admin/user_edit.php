<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_SESSION['role']!=1) {
  header('Location: login.php');
}
 if($_POST)
  {
    if(empty($_POST['name']) || empty($_POST['email'])) {
        if (empty($_POST['name'])) {
          $nameError='Name cannot null';
        }
        if (empty($_POST['email'])) {
          $emailError='Email cannot null';
        }

    }
    elseif($_POST['password'] && strlen($_POST['password'])<4) {
       $passwordError='Password should at least 4 character';
    }
    else{
          $id=$_POST['id'];
          $name=$_POST['name'];
          $email=$_POST['email'];
          $password=password_hash($_POST['password'], PASSWORD_DEFAULT);
          if(empty($_POST['role']))
          {
            $role=0;
          }
          else
          {
             $role=1;
          }
          $stmt=$pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
          $stmt->execute(array(':email'=>$email,':id'=>$id));
          $user=$stmt->fetch(PDO::FETCH_ASSOC);
          if ($user) {
            echo "<script> alert('Email is dupliate')</script>";
          }
          else{
             if ($password!=null) {
                $stmt=$pdo->prepare("UPDATE users SET name='$name',email='$email',password='$password',role='$role' WHERE id='$id'");
             }
             else{
               $stmt=$pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
             }
              $result=$stmt->execute();
              if ($result) {
                echo "<script> alert('Update users');window.location.href='user.php'; </script>";
              }
          }
        
        }
  }
  $stmt=$pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
  $stmt->execute();
  $result=$stmt->fetchALL();

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
                   <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                 <div class="form-group">
                   <label for="name">Name</label><p style="color: red;"><?php echo empty($nameError)?'':'*'.$nameError; ?></p>
                   <input type="text" name="name" id="name" class="form-control" value="<?php echo escape( $result[0]['name']); ?>">
                 </div> 
                 <div class="form-group">
                   <label for="email">Email</label><p style="color: red;"><?php echo empty($emailError)?'':'*'.$emailError; ?></p>
                   <input type="email" name="email" id="email" class="form-control" value="<?php echo escape($result[0]['email']); ?>">
                 </div>
                 <div class="form-group">
                   <label>Password</label><p style="color: red;"><?php echo empty($passwordError)?'':'*'.$passwordError; ?></p><br>
                   <span style="font-size: 10px;">The password has been exit</span>
                   <input type="password" name="password" class="form-control">
                 </div> 
                 <div class="form-group form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" name="role" value="1" <?php echo $result[0]['role']==1? 'checked':''; ?> type="checkbox">Admin
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