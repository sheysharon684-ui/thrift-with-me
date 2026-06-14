<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'customer';

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0) {
        $error = "Email already registered!";
    } else {
        mysqli_query($conn, "INSERT INTO users (fullname, email, password, role) VALUES ('$fullname','$email','$password','$role')");
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Thrift With Me</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script>
        // Form validation (Fig 1)
        function validateForm() {
            let fullname = document.getElementById('fullname').value;
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            
            if (fullname.trim() === "") {
                alert("Full name is required.");
                return false;
            }
            if (email.trim() === "") {
                alert("Email address is required.");
                return false;
            }
            if (!email.includes("@")) {
                alert("Please enter a valid email address (must contain @).");
                return false;
            }
            if (password.trim() === "") {
                alert("Password is required.");
                return false;
            }
            if (password.length < 4) {
                alert("Password must be at least 4 characters long.");
                return false;
            }
            return true;
        }
        
        // Password strength checker (Fig 2)
        function checkStrength() {
            let pwd = document.getElementById('password').value;
            let strengthSpan = document.getElementById('strength');
            if (pwd.length === 0) {
                strengthSpan.innerHTML = "";
                strengthSpan.className = "";
            } else if (pwd.length < 4) {
                strengthSpan.innerHTML = "Weak";
                strengthSpan.className = "text-danger";
            } else if (pwd.length < 8) {
                strengthSpan.innerHTML = "Medium";
                strengthSpan.className = "text-warning";
            } else {
                strengthSpan.innerHTML = "Strong";
                strengthSpan.className = "text-success";
            }
        }
    </script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Create Account</h3>
                    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST" onsubmit="return validateForm()">
                        <input type="text" name="fullname" id="fullname" class="form-control mb-3" placeholder="Full Name" required>
                        <input type="email" name="email" id="email" class="form-control mb-3" placeholder="Email" required>
                        <input type="password" name="password" id="password" class="form-control mb-2" placeholder="Password" required onkeyup="checkStrength()">
                        <span id="strength" class="small"></span>
                        <button type="submit" class="btn btn-dark w-100 mt-3">Register</button>
                        <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>