<?php
require_once("../includes/config.php");
if(isset($_POST['userTo']) && isset($_POST['userFrom'])) {
    echo "good!!";
}
else {
    echo "One or more parametsr are not passed into the subscribe.php file.";
}
?>