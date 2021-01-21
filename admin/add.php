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
 	if ($_POST) {
 		if(empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image'])){
        if (empty($_POST['title'])) {
          $titleError='Title cannot null';
        }
        if (empty($_POST['content'])) {
          $contentError=' Content cannot null';
        }

        if (empty($_POST['image'])) {
          $imageError='Image cannot null';
        }

    }
    else
    {
      $file='images/'.($_FILES['image']['name']);
    $imageType=pathinfo($file, PATHINFO_EXTENSION);
    if ($imageType!='png' && $imageType!='jpg' && $imageType!='jpeg') {
      echo "<script> alert('Image must be png or jpg or jpeg') </script>";
    }
    else
    {
      $title=$_POST['title'];
      $content=$_POST['content'];
      $image=$_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'],$file);
      $stmt=$pdo->prepare("INSERT INTO posts(title,content,author_id,image) VALUES (:title,:content,:author_id,:image)");

      $result=$stmt->execute(
        array(":title"=>$title,":content"=>$content,":image"=>$image,":author_id"=>$_SESSION['user_id'],)
      );
      if ($result) {
        echo "<script> alert('Successfully Added');window.location.href='index.php';</script>";
      }
    
    }
    }
 	}

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
               	 <div class="form-group">
                	<label>Title</label><p style="color: red;"><?php echo empty($titleError)?'':'*'.$titleError; ?></p>
                	<input type="text" name="title" class="form-control" value="">
                </div>
                <div class="form-group">
                	<label>Content</label><p style="color: red;"><?php echo empty($contentError)?'':'*'.$contentError; ?></p>
                	<textarea class="form-control" name="content"></textarea>
                </div>
                <div class="form-group">
                	<label>Image</label><p style="color: red;"><?php echo empty($imageError)?'':'*'.$imageError; ?></p>
                	<input type="file" name="image" class="form-control-file" value="">
                </div>
                <div class="form-group">
                	<input type="submit" name="" value="SUBMIT" class="btn btn-success">
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
