<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: url('images/bg.jpg') no-repeat center center fixed; /* 🔥 match global bg */
    background-size: cover;

    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* 🔥 FORM BOX (GLASS STYLE) */
form {
    background: rgba(255,255,255,0.9);
    padding: 30px;
    width: 320px;

    border-radius: 14px;
    text-align: center;

    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    backdrop-filter: blur(10px);
}

/* TITLE */
form h2 {
    margin-bottom: 20px;
    color: #2e7d32;
    text-shadow: 1px 1px 5px rgba(0,0,0,0.2);
}

/* INPUTS */
form input[type="text"],
form input[type="email"],
form input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0;

    border: none;
    border-radius: 8px;
    font-size: 14px;

    background: rgba(255,255,255,0.95);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);

    box-sizing: border-box;
    transition: 0.3s;
}

form input:focus {
    outline: none;
    box-shadow: 0 0 8px rgba(46,125,50,0.5);
}

/* CHECKBOX */
.show-password {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 5px 0 15px 0;
    font-size: 14px;
}

.show-password input {
    width: auto;
}

/* 🔥 BUTTON */
button {
    width: 100%;
    padding: 10px;

    background: linear-gradient(45deg,#43a047,#66bb6a);
    color: white;

    border: none;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;

    transition: 0.3s;
}

button:hover {
    transform: scale(1.03);
}

/* TEXT */
p {
    margin-top: 15px;
    font-size: 14px;
}

/* LINKS */
a {
    color: #2e7d32;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}
</style>
</head>


<body>

<form method="post" action="register_process.php">
<h2>Register</h2>

<input type="text" name="name" placeholder="Name" required>

<input type="email" name="email" placeholder="Email"
pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$"
title="Email must be a valid Gmail address"
required>


<input type="password" id="password" name="password"
placeholder="Password"
pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$"
title="Minimum 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character"
required>


<input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>

<div class="show-password">
    <input type="checkbox" onclick="togglePassword()">
    <label>Show Password</label>
</div>

<button type="submit">Register</button>

<p>
Already have an account?
<a href="login.php">Login</a>
</p>

</form>

<!-- ✅ SHOW PASSWORD FUNCTION -->
<script>
function togglePassword() {
    var pass = document.getElementById("password");
    var confirm = document.getElementById("confirm_password");

    if (pass.type === "password") {
        pass.type = "text";
        confirm.type = "text";
    } else {
        pass.type = "password";
        confirm.type = "password";
    }
}
</script>

<!-- ✅ ERROR POPUPS -->
<?php if (isset($_GET['error']) && $_GET['error'] == 'email_exists'): ?>
<script>
alert("❌ This email is already registered.");
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'password_mismatch'): ?>
<script>
alert("❌ Passwords do not match.");
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'failed'): ?>
<script>
alert("❌ Registration failed. Try again.");
</script>
<?php endif; ?>
<?php if (isset($_GET['error']) && $_GET['error'] == 'weak_password'): ?>
<script>
alert("❌ Password must contain 8 characters, uppercase, lowercase, number and special character.");
</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'invalid_email'): ?>
<script>
alert("❌ Email must be a valid Gmail address.");
</script>
<?php endif; ?>

</body>
</html>
