<?php
class CommentSection {
    private $video, $con, $userLoggedInObj;
    public function __construct($video, $con, $userLoggedInObj) {
        $this->video = $video;
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {
        return $this->createCommentSection();
    }

    private function createCommentSection() {

    }
}
?>