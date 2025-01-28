<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'test_db');

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Step 1: Check if the username exists (using prepared statements)
  $sqlCheckUser = "SELECT * FROM users WHERE username = ?";
  $stmt = $conn->prepare($sqlCheckUser);
  $stmt->bind_param("s", $username); // "s" = string type
  $stmt->execute();
  $resultCheckUser = $stmt->get_result();

  if ($resultCheckUser->num_rows === 0) {
    echo "<p style='color: red;'>No user found.</p>";
  } else {
    // Step 2: Check password (using prepared statements)
    $sqlCheckPassword = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sqlCheckPassword);
    $stmt->bind_param("ss", $username, $password); // "ss" = two strings
    $stmt->execute();
    $resultCheckPassword = $stmt->get_result();

    if ($resultCheckPassword->num_rows > 0) {
      $_SESSION['logged_in'] = true;
      $_SESSION['username'] = $username;
      header("Location: dashboard.php");
      exit();
    } else {
      echo "<p style='color: red;'>Incorrect credentials.</p>";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <h1>Login</h1>
  <form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    
    <div class="password-container">
      <input type="password" name="password" id="password" placeholder="Password" required>
      <i class="fa-solid fa-eye" id="togglePassword"></i>
    </div>
    
    <button type="submit">Login</button>
  </form>
  <script>
    // JavaScript to toggle password visibility
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
      // Toggle the type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      
      // Toggle the eye icon
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>