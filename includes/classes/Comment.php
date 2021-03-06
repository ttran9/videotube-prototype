<?php
require_once("ButtonProvider.php");
require_once("CommentControls.php");
class Comment {

    private $con, $sqlData, $userLoggedInObj, $videoId;

    public function __construct($con, $input, $userLoggedInObj, $videoId) {

        if(!is_array($input)) {
            $query = $con->prepare("SELECT * FROM comments where id=:id");
            $query->bindParam(":id", $input);
            $query->execute();

            $input = $query->fetch(PDO::FETCH_ASSOC);
        }

        $this->con = $con;
        $this->videoId = $videoId;
        $this->sqlData = $input;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {
        $id = $this->sqlData['id'];
        $videoId = $this->getVideoId();
        $body = $this->sqlData["body"];
        $postedBy = $this->sqlData["postedBy"];
        $profileButton = ButtonProvider::createUserProfileButton($this->con, $postedBy);
        $timespan = $this->time_elapsed_string($this->sqlData["datePosted"]); // get timespan.

        $commentControlsObj = new CommentControls($this->con, $this, $this->userLoggedInObj);
        $commentControls = $commentControlsObj->create();

        $numResponses = $this->getNumberOfReplies();

        if($numResponses > 0) {
            $viewRepliesText = "<span class='repliesSection viewReplies' onclick='getReplies($id, this, $videoId)'>
                                   View all $numResponses replies</span>";
        } else {
            $viewRepliesText = "<div class='repliesSection'></div>";
        }

        return "<div class='itemContainer'>
                    <div class='comment'>
                        $profileButton
                        
                        <div class='mainContainer'>
                            
                            <div class='commentHeader'>
                                <a href='profile.php?username=$postedBy'>
                                    <span class='username'>$postedBy</span>
                                </a>
                                <span class='timestamp'>$timespan</span>
                            </div>
                            
                            <div class='body'>
                                $body
                            </div>
                        </div>
                    </div>
                    $commentControls
                    $viewRepliesText
                </div>";
    }

    public function getNumberOfReplies() {
        $id = $this->sqlData["id"];
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM comments WHERE responseTo=:responseTo");
        $query->bindParam(":responseTo", $id);
        $query->execute();

        return $query->fetchColumn(); // return the first row from the query set.
    }

    private function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $ago->diff($now); // TODO: weird issue (refer to notes.md.. section 9 lecture 128).

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function getId() {
        return $this->sqlData['id'];
    }

    public function getVideoId() {
        return $this->videoId;
    }

    public function wasLikedBy() {
        $id = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
        $query = $this->con->prepare("SELECT * FROM likes WHERE username=:username and commentId=:commentId");
        $query->bindParam(":username", $username);
        $query->bindParam(":commentId", $id);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function wasDislikedBy() {
        $id = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
        $query = $this->con->prepare("SELECT * FROM dislikes WHERE username=:username and commentId=:commentId");
        $query->bindParam(":username", $username);
        $query->bindParam(":commentId", $id);
        $query->execute();

        return $query->rowCount() > 0;
    }


    public function getLikes() {
        $commentId = $this->getId();
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE commentId=:commentId");
        $query->bindParam(":commentId", $commentId);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        $numLikes = $data['count'];

        $commentId = $this->getId();
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE commentId=:commentId");
        $query->bindParam(":commentId", $commentId);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        $numDislikes = $data['count'];

        return $numLikes - $numDislikes;
    }

    public function like() {
        $commentId = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
        if($this->wasLikedBy()) {
            // user has already liked.
            $query = $this->con->prepare("DELETE FROM likes WHERE username=:username and commentId=:commentId");
            $query->bindParam(":username", $username);
            $query->bindParam(":commentId", $commentId);
            $query->execute();

            // we just dislike so just have one less like.
            return -1;
        } else {
            // if we like a comment we must also delete our dislike if there is one.
            $query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username and commentId=:commentId");
            $query->bindParam(":username", $username);
            $query->bindParam(":commentId", $commentId);
            $query->execute();
            $count = $query->rowCount();

            // user has not liked.
            $query = $this->con->prepare("INSERT INTO likes(username, commentId) VALUES(:username, :commentId)");
            $query->bindParam(":username", $username);
            $query->bindParam(":commentId", $commentId);
            $query->execute();

            // we remove a dislike and add a like.
            return 1 + $count;
        }
    }

    public function dislike() {
        $commentId = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
        if($this->wasDislikedBy()) {
            // user has already disliked.
            $query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username and commentId=:commentId");
            $query->bindParam(":username", $username);
            $query->bindParam(":commentId", $commentId);
            $query->execute();

            return 1;
        } else {
            // if we dislike a comment we must also delete our like if there is one.
            $query = $this->con->prepare("DELETE FROM likes WHERE username=:username and commentId=:commentId");
            $query->bindParam(":username", $username);
            $query->bindParam(":commentId", $commentId);
            $query->execute();
            $count = $query->rowCount();

            // user has not disliked.
            $query = $this->con->prepare("INSERT INTO dislikes(username, commentId) VALUES(:username, :commentId)");
            $query->bindParam(":username", $username);
            $query->bindParam(":commentId", $commentId);
            $query->execute();
            // when we remove a like and add a dislike.
            return -1 - $count;
        }
    }

    public function getReplies() {
        $commentId = $this->getId();
        $videoId = $this->getVideoId();
        // get comments in response to this video (not other comments).
        $query = $this->con->prepare("SELECT * FROM comments WHERE responseTo=:commentId ORDER BY datePosted ASC");
        $query->bindParam(":commentId", $commentId);
        $query->execute();

        $comments = "";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($this->con, $row, $this->userLoggedInObj, $videoId);
            $comments .= $comment->create();
        }

        return $comments;
    }
}
?>