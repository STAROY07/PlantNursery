<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account Help</title>

<style>
body{
    font-family: "Segoe UI", Arial, sans-serif;
    background:#eef6ee;
    margin:0;
    padding:0;
}

.account-wrapper{
    max-width:900px;
    margin:40px auto;
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
}

.account-wrapper h2{
    text-align:center;
    color:#2e7d32;
    margin-bottom:25px;
}

/* Section Card */
.section{
    background:#f9fdf9;
    border-left:5px solid #2e7d32;
    padding:20px;
    border-radius:8px;
    margin-bottom:25px;
}

.section h3{
    margin-top:0;
    color:#2e7d32;
}

/* Highlight Card */
.highlight{
    background:#f3f8ff;
    border-left:5px solid #1976d2;
    padding:20px;
    border-radius:8px;
    margin-bottom:25px;
}

.highlight h3{
    color:#1976d2;
}

ul, ol{
    padding-left:20px;
}

ul li, ol li{
    margin-bottom:8px;
    color:#333;
}

/* Back link */
.back-link{
    display:block;
    text-align:center;
    margin-top:30px;
    text-decoration:none;
    color:#2e7d32;
    font-weight:600;
}

.back-link:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="account-wrapper">

    <h2><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; margin-right: 5px; vertical-align: text-bottom;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Account Help</h2>

    <!-- Registration -->
    <div class="section">
        <h3>How to Create an Account</h3>
        <show> <!-- accidental tag; fix below -->
    </div>

    <div class="section">
        <h3>How to Create an Account</h3>
        <ol>
            <li>Click on the <b>Register</b> option.</li>
            <li>Enter your name, email, and password.</li>
            <li>Verify your email if required.</li>
            <li>Click on <b>Create Account</b>.</li>
        </ol>
    </div>

    <!-- Login -->
    <div class="section">
        <h3>Login to Your Account</h3>
        <ol>
            <li>Click on the <b>Login</b> button.</li>
            <li>Enter your registered email and password.</li>
            <li>Click <b>Sign In</b>.</li>
        </ol>
    </div>

    <!-- Forgot Password -->
    <div class="highlight">
        <h3>Forgot Password</h3>
        <ul>
            <li>Click on <b>Forgot Password</b> on the login page.</li>
            <li>Enter your registered email address.</li>
            <li>Reset link or OTP will be sent to your email.</li>
        </ul>
    </div>

    <!-- Profile Management -->
    <div class="section">
        <h3>Manage Profile</h3>
        <ul>
            <li>Update personal details like name and address.</li>
            <li>Change password securely.</li>
            <li>Manage delivery addresses.</li>
        </ul>
    </div>

    <!-- Account Security -->
    <div class="highlight">
        <h3>Account Security</h3>
        <ul>
            <li>Do not share your password with anyone.</li>
            <li>Use a strong password.</li>
            <li>Always log out from shared devices.</li>
        </ul>
    </div>

    <!-- Deactivate Account -->
    <div class="section">
        <h3>Deactivate Account</h3>
        <ul>
            <li>Account can be deactivated by contacting support.</li>
            <li>Order history will remain saved.</li>
        </ul>
    </div>

    <a href="help_center.php" class="back-link">← Back to Help Center</a>

</div>

</body>
</html>
