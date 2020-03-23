<?php 
/* ---------------------------------------------------------------------------
 * filename    : posts.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays a list of assignments (table: posts)
 * definition  : An assignment is a task for a volunteer at an event (shift). 
 * ---------------------------------------------------------------------------
 */

session_start();
if(!isset($_SESSION["accounts_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');   // go to login page
	exit;
}
$id = $_GET['id']; // for MyAssignments
$sessionid = $_SESSION['accounts_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body style="background-color: rgb(200, 200, 230) !important";>
    <div class="container">
	
		
		<?php 
		//gets logo
			include 'functions.php';
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3><?php if($id) echo 'My'; ?>Posts</h3>
		</div>
		
		<div class="row">
			<p>View all posts</p>
			<p>
				<?php if($_SESSION['accounts_admin']=='Administrator')
					echo '<a href="posts_create.php" class="btn btn-primary">Create Post</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['accounts_admin']=='Administrator')
					echo '<a href="accounts.php">Users</a> &nbsp;';
				?>
				<a href="categorys.php">Categories</a> &nbsp;
				<?php if($_SESSION['accounts_admin']=='Administrator')
					echo '<a href="categorys.php">All Categories</a>&nbsp;';
				?>
				<a href="posts.php?id=<?php echo $sessionid; ?>">My Categories</a>&nbsp;
				<?php if($_SESSION['accounts_admin']=='User')
					echo '<a href="categorys.php" class="btn btn-primary">Categories</a>';
				?>
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Subject</th>
						<th>Description</th>
						<th>Tags</th>
						<th>User</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					include 'database.php';
					//include 'functions.php';
					$pdo = Database::connect();
					
					if($id) 
						$sql = "SELECT * FROM posts 
						LEFT JOIN accounts ON accounts.id = posts.accountsID 
						LEFT JOIN categorys ON categorys.id = posts.categorysID
						WHERE accounts.id = $id 
						ORDER BY date ASC, time ASC, username ASC;";
					else
						$sql = "SELECT * FROM posts 
						LEFT JOIN accounts ON accounts.id = posts.accountsID 
						LEFT JOIN categorys ON categorys.id = posts.categorysID
						ORDER BY date ASC, time ASC, username ASC;";

					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>'. Functions::dayMonthDate($row['date']) . '</td>';
						echo '<td>'. Functions::timeAmPm($row['time']) . '</td>';
						echo '<td>'. $row['subject'] . '</td>';
						echo '<td>'. $row['description'] . '</td>';
						echo '<td>'. $row['tags'] . '</td>';
						echo '<td>'. $row['username'] . '</td>';
						echo '<td width=250>';
						# use $row[0] because there are 3 fields called "id"
						echo '<a class="btn" href="posts_read.php?id='.$row[0].'">Details</a>';
						if ($_SESSION['accounts_admin']=='Administrator' )
							echo '&nbsp;<a class="btn btn-success" href="posts_update.php?id='.$row[0].'">Update</a>';
						if ($_SESSION['accounts_admin']=='Administrator' 
							|| $_SESSION['accounts_id']==$row['accountsID'])
							echo '&nbsp;<a class="btn btn-danger" href="posts_delete.php?id='.$row[0].'">Delete</a>';
						if($_SESSION["accounts_id"] == $row['accountsID']) 		echo " &nbsp;&nbsp;Me";
						echo '</td>';
						echo '</tr>';
					}
					Database::disconnect();
				?>
				</tbody>
			</table>
    	</div>

    </div> <!-- end div: class="container" -->
	
</body>
</html>