<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'test_db'); // Adjust password if needed

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Step 1: Check if the username exists
  $sqlCheckUser = "SELECT * FROM users WHERE username = '$username'";
  $resultCheckUser = $conn->query($sqlCheckUser);

  if ($resultCheckUser->num_rows === 0) {
    // Username does NOT exist
    echo "<p style='color: red;'>No user found.</p>";
  } else {
    // Step 2: Check if username AND password match
    $sqlCheckPassword = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $resultCheckPassword = $conn->query($sqlCheckPassword);

    if ($resultCheckPassword->num_rows > 0) {
      // Login success: Redirect to dashboard
      $_SESSION['logged_in'] = true;
      $_SESSION['username'] = $username;
      header("Location: dashboard.php");
      exit();
    } else {
      // Password is incorrect
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