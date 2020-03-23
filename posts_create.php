<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_assign_create.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program adds/inserts a new assignment (table: posts)
 * ---------------------------------------------------------------------------
 */
 
session_start();
if(!isset($_SESSION["accounts_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$accountid = $_SESSION["accounts_id"];
$categoryid = $_GET['categorys_id'];

require 'database.php';
require 'functions.php';

if ( !empty($_POST)) {

	// initialize user input validation variables
	$accountsError = null;
	$categorysError = null;
	$contentError = null;
	
	// initialize $_POST variables
	$accounts = $_POST['accounts'];    // same as HTML name= attribute in put box
	$categorys = $_POST['category'];
	$content = $_POST['content'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	
	// validate user input
	$valid = true;
	if (empty($accounts)) {
		$accountsError = 'Please choose a user';
		$valid = false;
	}
	if (empty($categorys)) {
		$categorysError = 'Please choose a category';
		$valid = false;
	}
	if (empty($content)) {
		$contentError = 'Please enter content';
		$valid = false;
	}
		
	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO posts 
			(accountsID,categorysID,content) 
			values(?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($accountID,$categoryID,$content));
		Database::disconnect();
		header("Location: posts.php");
	}
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
				<h3>Create a post for this category</h3>
			</div>
	
			<form class="form-horizontal" action="posts_create.php" method="posts">
		
				<div class="control-group">
					<label class="control-label">Account</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM accounts ORDER BY username ASC';
							echo "<select class='form-control' name='accounts' id='accounts_id'>";
							if($eventid) // if $_GET exists restrict accounts options to logged in user
								foreach ($pdo->query($sql) as $row) {
									if($accountsid==$row['id'])
										echo "<option value='" . $row['id'] . " '> " . $row['username'] . "</option>";
								}
							else
								foreach ($pdo->query($sql) as $row) {
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
							echo "<select class='form-control' name='categorys' id='categorysID'>";
							if($eventid) // if $_GET exists restrict categorys options to selected categorys (from $_GET)
								foreach ($pdo->query($sql) as $row) {
									if($categoryid==$row['id'])
									echo "<option value='" . $row['id'] . " '> " . Functions::dayMonthDate($row['date']) . " (" . Functions::timeAmPm($row['time']) . ") - " .
									trim($row['tags']) . " (" . 
									trim($row['description']) . ") " .
									"</option>";
								}
							else
								foreach ($pdo->query($sql) as $row) {
									echo "<option value='" . $row['id'] . " '> " .
									trim($row['tags']) .
									//trim($row['description']) . ") " .
									"</option>";
								}
								
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div><!-- end div class="control-group" -->

				<div class="control-group <?php echo !empty($tagsError)?'error':'';?>">
					<label class="control-label">Content</label>
					<div class="controls" value="<?php echo !empty($tags)?$tags:'';?>">
						<?php if (!empty($tagsError)): ?>
							<span class="help-inline"><?php echo $tagsError;?></span>
						<?php endif;?>
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM posts ORDER BY content ASC';
							echo "<textarea class='form-control' name='posts' id='postsID'></textarea>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div><!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
						<a class="btn" href="posts.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
		<?php 
			//gets logo
			functions::logoDisplay();
		?>	
    </div> <!-- end div: class="container" -->

  </body>
</html>