<?php
session_start();
require '../config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_SESSION['role']!=1) {
  header('Location: login.php');
}
if(!empty($_POST['search']))
{
  setcookie('search',$_POST['search'], time() + (86400 * 30), "/"); 
}
else
{
  if (empty($_GET['pageno'])) {
    unset($_COOKIE['search']); 
    setcookie('search', null, -1, '/'); 
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
              	<?php
                  if(!empty($_GET['pageno']))
                  {
                    $pageno=$_GET['pageno'];
                  }
                  else
                  {
                    $pageno=1;
                  }
                  $noface=5;
                  $numoffset=($pageno-1)*$noface;
              		$i=1;
              		if(empty($_POST['search'])&& empty($_COOKIE['search']))
                  {
                    $stmt=$pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                    $stmt->execute();
                    $rawResult=$stmt->fetchAll();
                    $total_pages=ceil(count($rawResult)/$noface);
                    $stmt=$pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $numoffset,$noface");
                    $stmt->execute();
                    $result=$stmt->fetchAll();
                  }
                  else
                  {
                    $searchKey=!empty ($_POST['search']) ? $_POST['search'] :$_COOKIE['search'];
                    $stmt=$pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                    $stmt->execute();
                    $rawResult=$stmt->fetchAll();
                    $total_pages=ceil(count($rawResult)/$noface);
                    $stmt=$pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%'  ORDER BY id DESC LIMIT $numoffset,$noface");
                    $stmt->execute();
                    $result=$stmt->fetchAll();
                  }
              		
              	?>
                <div>
                  <a href="adduser.php" class="btn btn-success">New User</a>
                </div><br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
               	<?php foreach ($result as $value){ ?>
               		<tr>
               			<td><?php echo $i++; ?></td>
               			<td><?php echo $value['name']; ?></td>
               			<td><?php echo $value['email']; ?></td>
               			<td><?php echo $value['role'] == 1 ? 'admin': 'user';?></td>
               			<td>
               				<a href="user_edit.php?id=<?php echo $value['id']; ?>" class="btn btn-warning">Edit</a>
                      <a href="user_del.php?id=<?php echo $value['id']; ?>" onclick="return confirm('Are you sure you want to delete this item')" class="btn btn-danger">Delete</a>
               			</td>
               		</tr>

               	<?php } ?>
                  </tbody>
                </table><br>
                  <nav aria-label="Page navigation example" style="float: right;">
                    <ul class="pagination">
                      <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                      <li class="page-item <?php if($pageno <=1) { echo 'disabled'; }?>">
                        <a class="page-link" href="<?php if($pageno <=1) { echo '#'; } else{ echo '?pageno='.($pageno-1);}?>">Previous</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                      <li class="page-item <?php if($pageno >=$total_pages) { echo 'disabled'; }?>">
                        <a class="page-link" href="<?php if($pageno >= $total_pages) { echo '#'; } else { echo '?pageno='.($pageno+1); } ?>">Next</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                    </ul>
                  </nav>
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