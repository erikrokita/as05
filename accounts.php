<?php
/* ---------------------------------------------------------------------------
 * filename    : accounts.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays a list of volunteers (table: accounts)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["accounts_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
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

<body style="background-color: lightblue !important";>
    <div class="container">
		<?php 
			//gets logo
			require 'functions.php';
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3>Users</h3>
		</div>
		<div class="row">
			<p>sampletext</p>
			<p>
				<?php if($_SESSION['admin']=='Administrator')
					echo '<a href="accounts_create.php" class="btn btn-primary">Create a new Account</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<a href="accounts.php">Accounts</a> &nbsp;
				<a href="categorys.php">Categories</a> &nbsp;
				<a href="posts.php">All Posts</a>&nbsp;
				<a href="posts.php?id=<?php echo $sessionid; ?>">My Posts</a>&nbsp;
			</p>
				
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Username</th>
						<th>Password</th>
						<th>Email</th>
						<th>Bio</th>
						<th>Followers</th>
						<th>Following</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database.php';
						$pdo = Database::connect();
						$sql = 'SELECT `accounts`.*, COUNT(`posts`.accountsID) AS countPosts FROM `accounts` LEFT OUTER JOIN `posts` ON (`accounts`.id=`posts`.accountsID) GROUP BY `accounts`.id ORDER BY `accounts`.username ASC';
						//$sql = 'SELECT * FROM accounts ORDER BY `accounts`.lname ASC, `accounts`.fname ASC';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							if ($row['countAssigns'] == 0)
								echo '<td>'. trim($row['username']) . ' (' . substr($row['admin'], 0, 1) . ') '.' - UNASSIGNED</td>';
							else
								echo '<td>'. trim($row['username']) . ' (' . substr($row['admin'], 0, 1) . ') - '.$row['countAssigns']. ' categorys</td>';
							echo '<td>'. $row['passwords'] . '</td>';
							echo '<td>'. $row['email'] . '</td>';
							echo '<td>'. $row['description'] . '</td>';
							echo '<td width=225>';
							# always allow read
							echo '<a class="btn" href="accounts_read.php?id='.$row['id'].'">View</a>&nbsp;';
							# person can update own record
							if ($_SESSION['accounts_admin']=='Administrator'
								|| $_SESSION['accounts_id']==$row['id'])
								echo '<a class="btn btn-success" href="accounts_update.php?id='.$row['id'].'">Edit</a>&nbsp;';
							# only admins can delete
							if ($_SESSION['accounts_admin']=='Administrator' 
								&& $row['countAssigns']==0)
								echo '<a class="btn btn-danger" href="accounts_delete.php?id='.$row['id'].'">Delete</a>';
							if($_SESSION["accounts_id"] == $row['id']) 
								echo " &nbsp;&nbsp;Me";
							echo '</td>';
							echo '</tr>';
						}
						Database::disconnect();
					?>
				</tbody>
			</table>
			
    	</div>
    </div> <!-- /container -->
  </body>
</html>