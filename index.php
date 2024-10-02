<?php 
include_once('db.php');

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student_name'];
    $student_gender = $_POST['student_gender'];
    $t_id = $_SESSION['id'];

    $insert_student = $conn->prepare("INSERT INTO `datachart` (`name`, `gender`, `t_id`) VALUES (?, ?, ?)");
    $insert_student->bind_param("ssi", $student_name, $student_gender, $t_id);

    if ($insert_student->execute()) {
        $success_message = "Student added successfully!";
    } else {
        $error_message = "Failed to add student!";
    }
}

$t_id = $_SESSION['id'];

$qry = $conn->prepare("SELECT `gender`, COUNT(`gender`) FROM `datachart` WHERE `t_id` = ? GROUP BY `gender`");
$qry->bind_param("i", $t_id);
$qry->execute();
$result = $qry->get_result();

$data = array();
while ($rows = $result->fetch_assoc()) {
    $data[] = array($rows['gender'], (INT)$rows['COUNT(`gender`)']);
}

$student_qry = $conn->prepare("SELECT * FROM `datachart` WHERE `t_id` = ?");
$student_qry->bind_param("i", $t_id);
$student_qry->execute();
$student_result = $student_qry->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Class Data</title>
    <style>
      tr {
        text-align: center;
      }
    </style>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Gender', 'Count'],
          <?php 
            foreach ($data as $row) {
              echo "['" . $row[0] . "', " . $row[1] . "],";
            }
          ?>
        ]);

        var options = {
          title: 'Class of <?php echo $_SESSION['name']?> teacher'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>
</head>
<body>
  <!-- Navbar with Logout Button -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Student Management</a>
    <div class="ml-auto">
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </nav>

  <div class="container mt-4">
    <div class="row">
      <div class="col-md-6">
        <div id="piechart" style="width: 100%; height: 500px;"></div>
      </div>

      <div class="col-md-6 mt-5">
        <table class="table table-bordered table-hover">
          <thead class="thead-light">
            <tr>
              <th>Name</th>
              <th>Gender</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($student_result->num_rows > 0) { ?>
                <?php while ($student = $student_result->fetch_assoc()) { ?>
                <tr>
                  <td><?= $student['name']; ?></td>
                  <td><?= $student['gender']; ?></td>
                  <td><a href="delete.php?id=<?=$student['id']?>" style="text-decoration: none;">Delete</a></td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                  <td colspan="3" class="text-center">No data available</td>
                </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="container mt-5 d-flex justify-content-center align-items-center" style="min-height: 400px;">
    <div class="card shadow p-4" style="width: 100%; max-width: 500px;">
        <h4 class="text-center mb-4">Add a New Student</h4>
        
        <?php if (isset($success_message)) { ?>
          <div class="alert alert-success">
            <?php echo $success_message; ?>
          </div>
        <?php } ?>
        <?php if (isset($error_message)) { ?>
          <div class="alert alert-danger">
            <?php echo $error_message; ?>
          </div>
        <?php } ?>

        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="student_name">Student Name</label>
                <input type="text" class="form-control" id="student_name" name="student_name" required>
            </div>
            
            <div class="form-group">
                <label for="student_gender">Gender</label>
                <select class="form-control" id="student_gender" name="student_gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Add Student</button>
        </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
