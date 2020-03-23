<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_event_read.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays one event's details (table: fr_events)
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
$sql = "SELECT * FROM category where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);
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
    
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Category Details</h3>
			</div>
			
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
				
				<div class="form-actions">
					<a class="btn btn-primary" href="posts_create.php?event_id=<?php echo $id; ?>">Make a post in this category</a>
					<a class="btn" href="categorys.php">Back</a>
				</div>
				
			<div class="row">
				<h4>Users who made posts in this category</h4>
			</div>
			
			<?php
				$pdo = Database::connect();
				$sql = "SELECT * FROM posts, accounts WHERE accountsID = accounts.id AND categorysID = " . $data['id'] . ' ORDER BY username ASC';
				$countrows = 0;
				if($_SESSION['fr_person_title']=='Administrator') {
					foreach ($pdo->query($sql) as $row) {
						echo $row['username'] . ' - ' . $row['email'] . '<br />';
					$countrows++;
					}
				}
				else {
					foreach ($pdo->query($sql) as $row) {
						echo $row['username'] . ' - ' . '<br />';
					$countrows++;
					}
				}
				if ($countrows == 0) echo 'none.';
			?>
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
	
</body>
</html>