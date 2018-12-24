<?php
require_once("includes/header.php");
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/VideoInfoSection.php");
if(!isset($_GET["id"])) {
    echo "No url passed into page!";
    exit();
}

$video = new Video($con, $_GET["id"], $userLoggedInObj);
$video->incrementViews();
?>
<script src="assets/jss/videoPlayerActions.js" type="text/javascript"></script>

<div class="watchLeftColumn">

<?php
    $videoPlayer = new VideoPlayer($video);
    echo $videoPlayer->create(true);

    $videoInfoSection = new VideoInfoSection($video, $con, $userLoggedInObj);
    echo $videoInfoSection->create();
?>

</div>

<div class="suggestions">

</div>

<?php require_once("includes/footer.php"); ?>
