<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_assign_read.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays one assignment's details (table: posts)
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

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

# get assignment details
$sql = "SELECT * FROM posts where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);

# get volunteer details
$sql = "SELECT * FROM accounts where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($data['accountsID']));
$perdata = $q->fetch(PDO::FETCH_ASSOC);

# get categorys details
$sql = "SELECT * FROM fr_events where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($data['categoryID']));
$eventdata = $q->fetch(PDO::FETCH_ASSOC);

Database::disconnect();
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
				<h3>Assignment Details</h3>
			</div>
			
			<div class="form-horizontal" >
			
				<div class="control-group">
					<label class="control-label">User</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $perdata['username'] ;?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Category</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo trim($eventdata['tag']) . " (" . trim($eventdata['description']) . ") ";?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Date, Time</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo Functions::dayMonthDate($eventdata['date']) . ", " . Functions::timeAmPm($eventdata['time']);?>
						</label>
					</div>
				</div>
				
				<div class="form-actions">
					<a class="btn" href="posts.php">Back</a>
				</div>
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
	
</body>
</html>