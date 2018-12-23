<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");

$account = new Account($con);

if(isset($_POST["submitButton"])) {
    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);

    $userName = FormSanitizer::sanitizeFormUsername($_POST["username"]);

    $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
    $email2 = FormSanitizer::sanitizeFormEmail($_POST["email2"]);

    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
    $password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);

    $account->register($firstName, $lastName, $userName, $email, $email2, $password, $password2);

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
            <h3>Sign Up</h3>
            <span>to continue to VideoTube</span>
        </div>

        <div class="loginForm">
            <form action="signUp.php" method="POST">

                <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                <input type="text" name="firstName" placeholder="First name" autocomplete="off" required />
                <input type="text" name="lastName" placeholder="Last name" autocomplete="off" required />
                <input type="text" name="username" placeholder="User name" autocomplete="off" required />

                <input type="email" name="email" placeholder="Email" autocomplete="off" required />
                <input type="email" name="email2" placeholder="Confirm Email" autocomplete="off" required />

                <input type="password" name="password" placeholder="Password" autocomplete="off" required />
                <input type="password" name="password2" placeholder="Confirm Password" autocomplete="off" required />

                <input type="submit" name="submitButton" value="Submit" />

            </form>

        </div>

        <a href="signIn.php" class="signInMessage">Already have an account? Sign In Here!</a>
    </div>

</div>


</body>
</html>