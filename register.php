<?php 
include_once("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['user'];
    $password = $_POST['pwd'];
    $confirmPassword = $_POST['confirm_pwd'];

    if ($password === $confirmPassword) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $qry = $conn->prepare("INSERT INTO `teacher` (`username`, `password`) VALUES (?, ?)");
        $qry->bind_param("ss", $username, $hashedPassword);

        if ($qry->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Error: Registration failed. Please try again.";
        }
    } else {
        $error_message = "Error: Passwords do not match.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Register</title>
	<!-- Bootstrap CSS -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container mt-5">
		<div class="row justify-content-center mt-5">
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center mb-4">Register</h4>

						<?php if (isset($error_message)) { ?>
							<div class="alert alert-danger" role="alert">
								<?php echo $error_message; ?>
							</div>
						<?php } ?>

						<!-- Registration Form -->
						<form action="register.php" method="post">
							<div class="form-group">
								<label for="user">Username</label>
								<input type="text" name="user" class="form-control" id="user" autocomplete="off" required>
							</div>
							<div class="form-group">
								<label for="pwd">Password</label>
								<input type="password" name="pwd" class="form-control" id="pwd" required>
							</div>
							<div class="form-group">
								<label for="confirm_pwd">Confirm Password</label>
								<input type="password" name="confirm_pwd" class="form-control" id="confirm_pwd" required>
							</div>
							<button type="submit" class="btn btn-primary btn-block">Register</button>
							<div class="text-center mt-3">
								<a href="index.php">Already have an account? Login</a>
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
