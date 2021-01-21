<?php 
  session_start();
  require '../config/common.php';
  if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
  }
  if ($_SESSION['role']!=1) {
  header('Location: login.php');
  }
  require '../config/config.php';

  if ($_POST) {
    if (empty($_POST['title']) || empty($_POST['content'])) {
        if (empty($_POST['title'])) {
          $titleError='Title cannot null';
        }
        if (empty($_POST['content'])) {
          $contentError=' Content cannot null';
        }
     
    }
    else{
      $id=$_POST['id'];
      $title=$_POST['title'];
      $content=$_POST['content'];
      if($_FILES['image']['name'] != null) {
        $file='images/'.($_FILES['image']['name']);
        $imageType=pathinfo($file,PATHINFO_EXTENSION );
          if ($imageType!='jpg' && $imageType!='png' && $imageType!='jpeg')  {
            echo "<script> alert('Image must be jpg or jpeg or png')";
          }
          else
          {
            $image=$_FILES['image']['name'];
            $v=move_uploaded_file($_FILES['image']['tmp_name'],$file);
            $stmt=$pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
            $result=$stmt->execute();
            if ($result) {
              echo "<script> alert('Update Successsfully');window.location.href='index.php'; </script>";
            }
          }

      }
      else
      {
        $stmt=$pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
        $result=$stmt->execute();
        if ($result) {
          echo "<script> alert('Update Successsfully');window.location.href='index.php'; </script>";
        }
      }
    }
  }
   $stmt=$pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $stmt->execute();
  $result=$stmt->fetchALL();


?>
<?php
      require 'header.php';

?>
   <div class="content">
      <div class="container-fluid">
        <div class="row">
         <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Bordered Table</h3>
                <a href="index.php" class="float-right btn btn-success">Back</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
               <form action="" method="POST" enctype='multipart/form-data'>
                 <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                 <div class="form-group">
                  <label>Title</label><p style="color: red;"><?php echo empty($titleError)?'':'*'.$titleError; ?></p>
                  <input type="text" name="title" class="form-control" value="<?php echo escape( $result[0]['title']); ?>">
                </div>
                <div class="form-group">
                  <label>Content</label><p style="color: red;"><?php echo empty($contentError)?'':'*'.$contentError; ?></p>
                  <textarea class="form-control" name="content"><?php echo escape($result[0]['content']); ?></textarea>
                </div>
                <div class="form-group">
                  <label>Image</label>
                  <img src="images/<?php echo $result[0]['image'];  ?>" width="100px" height="100px" class="img-fluid pt-3 pb-3">
                  <input type="file" name="image" class="form-control-file" value="" >
                </div>
                <div class="form-group">
                  <input type="submit" name="" value="UPDATE" class="btn btn-success">
                </div>
               </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>




<?php 

  require 'footer.php';

?>
