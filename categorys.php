<?php
/* ---------------------------------------------------------------------------
 * filename    : fr_events.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays a list of events (table: fr_events)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["accounts_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['accounts_id'];
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body style="background-color: rgb(200, 230, 200) !important";>
    <div class="container">
		  <?php 
			//gets logo
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3>Categories</h3>
		</div>
		
		<div class="row">
			<p>Pick a topic to make a post to</p>
			<p>
				<?php if($_SESSION['accounts_admin']=='Administrator')
					echo '<a href="categorys_create.php" class="btn btn-primary">Create a category</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['accounts_admin']=='Administrator')
					echo '<a href="accounts.php">Users</a> &nbsp;';
				?>
				<a href="posts.php">Posts</a> &nbsp;
				<?php if($_SESSION['accounts_admin']=='Administrator')
					echo '<a href="posts.php">All Posts</a>&nbsp;';
				?>
				<a href="posts.php?id=<?php echo $sessionid; ?>">My posts</a>&nbsp;
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Subject</th>
						<th>Description</th>
						<th>Tags</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database.php';
						$pdo = Database::connect();
						//$sql = 'SELECT `category`.*, SUM(case when categoryID ='. $_SESSION['accounts_id'] .' then 1 else 0 end) AS sumAssigns, COUNT(`posts`.categoryID) AS countAssigns FROM `category` LEFT OUTER JOIN `posts` ON (`categorys`.id=`posts`.categoryID) GROUP BY `categorys`.id ORDER BY `posts`.date ASC, `posts`.time ASC';
						$sql = 'SELECT * FROM category';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							echo '<td>'. $row['subject'] . '</td>';
							echo '<td>'. $row['description'] . '</td>';
							echo '<td>'. $row['tags'] . '</td>';
							if ($row['countAssigns']==0)
								echo '<td>'. $row['content'] . ' - UNSTAFFED </td>';
							else
								echo '<td>'. $row['content'] . ' (' . $row['countAssigns']. ' users)' . '</td>';
							//echo '<td width=250>';
							echo '<td>';
							echo '<a class="btn" href="categorys_read.php?id='.$row['id'].'">Details</a> &nbsp;';
							if ($_SESSION['accounts_admin']=='User' )
								echo '<a class="btn btn-primary" href="categorys_read.php?id='.$row['id'].'">View category</a> &nbsp;';
							if ($_SESSION['accounts_admin']=='Administrator' )
								echo '<a class="btn btn-success" href="categorys_update.php?id='.$row['id'].'">Update category info</a>&nbsp;';
							if ($_SESSION['accounts_admin']=='Administrator' 
								&& $row['countAssigns']==0)
								echo '<a class="btn btn-danger" href="categorys_delete.php?id='.$row['id'].'">Delete category</a>';
							if($row['sumAssigns']==1) 
								echo " &nbsp;&nbsp;Me";
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