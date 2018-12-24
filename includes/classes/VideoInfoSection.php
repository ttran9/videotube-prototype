<?php
class VideoInfoSection {
    private $video, $con, $userLoggedInObj;
    public function __construct($video, $con, $userLoggedInObj) {
        $this->video = $video;
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {
        return $this->createPrimaryInfo() . $this->createSecondaryInfo();
    }

    private function createPrimaryInfo() {
        $title = $this->video->getTitle();
        $views = $this->video->getViews();

        return "<div class='videoInfo'>
                    <h1>$title</h1> 
                    <div class='bottomSection'>
                        <span class='viewCount'>$views</span>
                    </div>
                </div>";
    }

    private function createSecondaryInfo() {

    }
}

?>