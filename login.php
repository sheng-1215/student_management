<?php 
include_once("db.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST['user'];
    $pwd  = $_POST['pwd'];

    $qry = $conn->prepare("SELECT * FROM `teacher` WHERE `username` = ?");
    $qry->bind_param("s",$user);
    $qry->execute();
    $result = $qry->get_result();
    if ($result->num_rows == 1) {
    	$row = $result->fetch_assoc();
    	if (password_verify($pwd, $row['password'])) {
    		$_SESSION['name'] = $user;
    		$_SESSION['id'] = $row['id'];
        	header("Location:index.php");
        	exit();
    	} else {
        	$error_message = "Error: Invalid Username or Password";
    	}
    } else {
    	$error_message = "Error: Invalid Username or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Login</title>
	<!-- Bootstrap CSS -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

	<h1 class="d-flex justify-content-center mt-5">Student Management </h1>
	<div class="container mt-5">
		<div class="row justify-content-center mt-5">
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center mb-4">Login</h4>

						<?php if (isset($error_message)) { ?>
							<div class="alert alert-danger" role="alert">
								<?php echo $error_message; ?>
							</div>
						<?php } ?>

						<form action="login.php" method="post">
							<div class="form-group">
								<label for="user">Username</label>
								<input type="text" name="user" class="form-control" id="user" autocomplete="off" required>
							</div>
							<div class="form-group">
								<label for="pwd">Password</label>
								<input type="password" name="pwd" class="form-control" id="pwd" required>
							</div>
							<button type="submit" class="btn btn-primary btn-block">Login</button>
							<div class="text-center mt-3">
								<a href="register.php">Register</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Bootstrap JS, Popper.js, and jQuery -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
