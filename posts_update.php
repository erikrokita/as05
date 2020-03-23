<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_assign_update.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program updates an assignment (table: posts)
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

if ( !empty($_POST)) { // if $_POST filled then process the form
	
	# same as create

	// initialize user input validation variables
	$accountsError = null;
	$categorysError = null;
	
	// initialize $_POST variables
	$accounts = $_POST['accounts_id'];    // same as HTML name= attribute in put box
	$categorys = $_POST['categorys_id'];
	
	// validate user input
	$valid = true;
	if (empty($accounts)) {
		$accountsError = 'Please choose a user';
		$valid = false;
	}
	if (empty($categorys)) {
		$categorysError = 'Please choose a categorys';
		$valid = false;
	} 
		
	if ($valid) { // if valid user input update the database
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE posts set accountsID = ?, categoryID = ? WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($accounts,$categorys,$id));
		Database::disconnect();
		header("Location: posts.php");
	}
} else { // if $_POST NOT filled then pre-populate the form
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM posts where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$accounts = $data['accountsID'];
	$categorys = $data['categoryID'];
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
        <?php 
			//gets logo
			functions::logoDisplay();
		?>
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Edit Post</h3>
			</div>
	
			<form class="form-horizontal" action="posts_update.php?id=<?php echo $id?>" method="post">
		
				<div class="control-group">
					<label class="control-label">User</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM accounts ORDER BY username ASC';
							echo "<select class='form-control' name='person_id' id='person_id'>";
							foreach ($pdo->query($sql) as $row) {
								if($row['id']==$accounts)
									echo "<option selected value='" . $row['id'] . " '> " . $row['username'] . "</option>";
								else
									echo "<option value='" . $row['id'] . " '> " . $row['username'] . "</option>";
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
			  
				<div class="control-group">
					<label class="control-label">Category</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM category ORDER BY subject ASC';
							echo "<select class='form-control' name='categorys_id' id='categorys_id'>";
							foreach ($pdo->query($sql) as $row) {
								if($row['id']==$categorys) {
									echo "<option selected value='" . $row['id'] . " '> " . Functions::dayMonthDate($row['date']) . " (" . Functions::timeAmPm($row['time']) . ") - " . trim($row['tags']) . " (" . trim($row['subject']) . ") " . "</option>";
								}
								else {
									echo "<option value='" . $row['id'] . " '> " . Functions::dayMonthDate($row['date']) . " (" . Functions::timeAmPm($row['time']) . ") - " . trim($row['tags']) . " (" . trim($row['subject']) . ") " . "</option>";
								}
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Update</button>
					<a class="btn" href="posts.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->

  </body>
</html>