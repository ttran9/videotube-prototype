<?php
class VideoPlayer {

    private $video;
    public function __construct($video) {
        $this->video = $video;
    }

    public function create($autoPlay) {
        if($autoPlay) {
            $autoPlay = "autoplay";
        } else {
            $autoPlay = "";
        }
        $filePzth = $this->video->getFilePath();
        return "<video class='videoPlayer' controls $autoPlay>
                    <source src='$filePzth' type='video/mp4'>
                    Your browser does not support the video tag
                </video>";
    }

}
?>