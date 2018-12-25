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
        $numComments = $this->video->getNumberOfComments();
        $postedBy = $this->userLoggedInObj->getUsername();
        $videoId = $this->video->getId();

        $profileButton = ButtonProvider::createUserProfileButton($this->con, $postedBy);
        $commentAction = "postComment(this, \"$postedBy\", $videoId, null, \"comments\")";
        $commentButton = ButtonProvider::createButton("COMMENT", null, $commentAction, "postComment");

        // Get comments html

        return "<div class='commentSection'>
                    <div class='header'>
                        <span class='commentCount'>$numComments comments</span>
                        <div class='commentForm'>
                            $profileButton
                            <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
                            $commentButton
                        </div>
                    </div>
                    
                    <div class='comments'>
                    
                    </div>
                </div>";
    }
}
?>