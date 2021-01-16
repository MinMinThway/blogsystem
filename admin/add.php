 <?php
 	require '../config/config.php';
 	session_start();
 	if ($_POST) {
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
               	 <div class="form-group">
                	<label>Title</label>
                	<input type="text" name="title" class="form-control" value="" required>
                </div>
                <div class="form-group">
                	<label>Content</label>
                	<textarea class="form-control" name="content"></textarea>
                </div>
                <div class="form-group">
                	<label>Image</label>
                	<input type="file" name="image" class="form-control-file" value="" required>
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
