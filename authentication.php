<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['code'])) {
    $unique_code = $_GET['code'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "esp32_database";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the unique code exists in the database
    $sql = "SELECT * FROM users WHERE unique_code = '$unique_code'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Valid code, start session and return success
        $_SESSION['authenticated'] = true;
        echo json_encode(["success" => true]);
    } else {
        // Invalid code
        echo json_encode(["success" => false]);
    }

    $conn->close();
    exit();  // Stop execution after the validation response
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Straw-Freelament PID Dashboard</title>
  <style>
    /* reset */
    * { margin:0; padding:0; box-sizing:border-box }
    html, body { height:100%; font-family:Arial,sans-serif; background:#f4f4f4 }

    /* navbar */
    .navbar {
      background:#0033cc;
      color:white;
      padding:10px 20px;
      display:flex;
      align-items:center;
    }
    .navbar img {
      width:30px; height:30px;
      margin-right:10px;
    }
    .navbar .brand {
      font-size:16px;
    }
    .navbar .brand b {
      font-weight:bold;
    }

    /* two-col layout */
    .outer {
      display:flex;
      max-width:1000px;
      margin:20px auto;
      gap:40px;
      align-items:center;
      min-height:calc(100vh - 52px);
    }

    /* left promo */
    .left-panel {
      flex:1;
      padding:0 20px;
    }
    .left-panel .promo-title {
      font-size:48px;
      font-weight:bold;
      color:#0033cc;
      margin-bottom:20px;
    }
    .left-panel .promo-sub {
      font-size:20px;
      color:#333;
      line-height:1.4;
    }

    /* right login */
    .right-panel {
      flex:1;
      display:flex;
      flex-direction:column;
      align-items:center;
    }
    .welcome-banner {
      font-size:28px;
      font-weight:600;
      color:#333;
      margin-bottom:20px;
      text-align:center;
    }
    .login-container {
      background:#fff;
      border-radius:8px;
      box-shadow:0 4px 10px rgba(0,0,0,0.1);
      padding:30px 20px;
      width:100%;
      max-width:320px;
      text-align:center;
    }
    .login-container h1 {
      font-size:22px;
      margin-bottom:20px;
      color:#333;
      text-align:center;    /* center the heading */
    }
    .login-container input[type="text"] {
      width:100%;
      padding:10px;
      font-size:14px;
      margin-bottom:15px;
      border:1px solid #ccc;
      border-radius:4px;
    }
    .login-container input[type="submit"] {
      width:100%;
      padding:10px;
      font-size:16px;
      background:#0033cc;
      color:#fff;
      border:none;
      border-radius:4px;
      cursor:pointer;
    }
    .login-container input[type="submit"]:hover {
      background:#002299;
    }
  </style>
</head>
<body>

  <div class="navbar">
    <img src="/capstone/images/abra.png" alt="Abra Logo">
    <div class="brand">
      <b>Straw-Freelament:</b> Recycling Plastic Straws into Eco-Innovative 3D Printing Filament
    </div>
  </div>

  <div class="outer">
    <!-- LEFT -->
    <div class="left-panel">
      <div class="promo-title">Straw-Freelament</div>
      <div class="promo-sub">
        Live Video Monitoring and Control your Straw-Freelament Machine
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">
      <div class="welcome-banner">Welcome to MoniFila</div>
      <div class="login-container">
        <h1>Enter Unique Code</h1>
        <form id="loginForm">
          <input type="text" id="uniqueCode" placeholder="Enter Unique Code" required>
          <input type="submit" value="Connect">
        </form>
      </div>
    </div>
  </div>
  <script>
    const form = document.getElementById("loginForm");

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const uniqueCode = document.getElementById("uniqueCode").value;

      // Send the unique code to the server for validation

      fetch(`/capstone/authentication.php?code=${uniqueCode}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            window.location.href = "http://172.20.10.3/dashboard"; // Redirect to the dashboard page
          } else {
            alert("Invalid code. Please try again.");
          }
        })
        .catch(err => {
          alert("Error: " + err.message);
        });
    });
  </script>

</body>
</html>
