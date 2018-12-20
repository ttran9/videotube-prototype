<?php
class VideoProcessor {

    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function upload($videoUploadData) {
        $targetDir = "uploads/videos/";
        $videoData = $videoUploadData->getVideoDataArray();
        $tempFilePath = $targetDir . uniqid() . basename($videoData['name']);
//        $tempFilePath = str_replace("\\s+", "_", $tempFilePath);
        $tempFilePath = str_replace(" ", "_", $tempFilePath);
        echo $tempFilePath;
    }
}
?>