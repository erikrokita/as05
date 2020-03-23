<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_event_delete.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program deletes one event's details (table: fr_events)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["accounts_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}

require 'database.php';
require 'functions.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if user clicks "yes" (sure to delete), delete record

	$id = $_POST['id'];
	
	// delete data
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM category  WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: categorys.php");
	
} 
else { // otherwise, pre-populate fields to show data to be deleted
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM category where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	Database::disconnect();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body>
    <div class="container">

		<div class="span10 offset1">
		
			<div class="row">
				<h3>Delete Shift</h3>
			</div>
			
			<form class="form-horizontal" action="categorys_delete.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id;?>"/>
				<p class="alert alert-error">Are you sure you want to yeet this categoryeet?</p>
				<div class="form-actions">
					<button type="submit" class="btn btn-danger">Yes</button>
					<a class="btn" href="categorys.php">No</a>
				</div>
			</form>
			
			<!-- Display same information as in file: fr_event_read.php -->
			
			<div class="form-horizontal" >
				
				<div class="control-group">
					<label class="control-label">Subject</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['subject'];?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Content</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['description'];?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Tags</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['tags'];?>
						</label>
					</div>
				</div>
				
			<div class="row">
				<h4>Users who posted into this category</h4>
			</div>
			
			<?php
				$pdo = Database::connect();
				$sql = "SELECT * FROM posts, accounts WHERE accountID = accounts.id AND categoryID = " . $data['id'] . ' ORDER BY username ASC';
				$countrows = 0;
				foreach ($pdo->query($sql) as $row) {
					echo $row['username'] . ' - ' . $row['email'] . '<br />';
					$countrows++;
				}
				if ($countrows == 0) echo 'none.';
			?>
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
  </body>
</html>