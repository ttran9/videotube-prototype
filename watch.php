<?php
require_once("includes/header.php");
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/VideoInfoSection.php");
require_once("includes/classes/CommentSection.php");
require_once("includes/classes/Comment.php");
if(!isset($_GET["id"])) {
    echo "No url passed into page!";
    exit();
}

$video = new Video($con, $_GET["id"], $userLoggedInObj);
$video->incrementViews();
?>
<script src="assets/js/videoPlayerActions.js" type="text/javascript"></script>
<script src="assets/js/commentActions.js" type="text/javascript"></script>

<div class="watchLeftColumn">

<?php
    $videoPlayer = new VideoPlayer($video);
    echo $videoPlayer->create(true);

    $videoInfoSection = new VideoInfoSection($video, $con, $userLoggedInObj);
    echo $videoInfoSection->create();

    $commentSection = new CommentSection($video, $con, $userLoggedInObj);
    echo $commentSection->create();
?>

</div>

<div class="suggestions">

</div>

<?php require_once("includes/footer.php"); ?>
