<?php
require_once("includes/config.php");

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}

?>

<!doctype html>
<html>
<head>
    <title>VideoTube</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>

    <div class="signinContainer">
        <div class="column">
            <div class="header">
                <img src="assets/images/icons/VideoTubeLogo.png" title="logo" alt="Site logo" />
                <h3>Sign In</h3>
                <span>to continue to VideoTube</span>
            </div>

            <div class="loginForm">
                <form action="signIn.php" method="POST">

                    <input type="text" name="username" placeholder="User name"
                           value="<?php getInputValue('username') ?>" required autocomplete="off" />
                    <input type="password" name="password" placeholder="Password" required />
                    <input type="submit" name="submitButton" value="Submit" />


                </form>

            </div>

            <a href="signUp.php" class="signInMessage">Need an account? Sign Up Here!</a>
        </div>

    </div>


</body>
</html>