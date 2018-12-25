<?php
class Video {

    private $con, $sqlData, $userLoggedInObj;

    public function __construct($con, $input, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;

        if(is_array($input)) {
            $this->sqlData = $input;
        } else {
            $query = $this->con->prepare("SELECT * FROM videos WHERE id = :id");
            $query->bindParam(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

    }

    public function getId() {
        return $this->sqlData["id"];
    }

    public function getUploadedBy() {
        return $this->sqlData["uploadedBy"];
    }

    public function getTitle() {
        return $this->sqlData["title"];
    }

    public function getDescription() {
        return $this->sqlData["description"];
    }

    public function getPrivacy() {
        return $this->sqlData["privacy"];
    }

    public function getCategory() {
        return $this->sqlData["category"];
    }

    public function getFilePath() {
        return $this->sqlData["filePath"];
    }

    public function getUploadDate() {
        $date = $this->sqlData["uploadDate"];
        return date("M j, Y", strtotime($date));
    }

    public function getViews() {
        return $this->sqlData["views"];
    }

    public function getDuration() {
        return $this->sqlData["duration"];
    }

    public function incrementViews() {
        $query = $this->con->prepare("UPDATE videos SET views=views + 1 WHERE id=:id");
        $videoId = $this->getId();
        $query->bindParam(":id", $videoId);
        $query->execute();

        $this->sqlData["views"] = $this->sqlData["views"] + 1;
    }

    public function getLikes() {
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE videoId=:videoId");
        $videoId = $this->getId();
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data["count"];
    }

    public function getDislikes() {
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE videoId=:videoId");
        $videoId = $this->getId();
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data["count"];
    }

    public function like() {
        $videoId = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
        if($this->wasLikedBy()) {
            // user has already liked.
            $query = $this->con->prepare("DELETE FROM likes WHERE username=:username and videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $videoId);
            $query->execute();

            $result = array (
              "likes" => -1,
              "dislikes" => 0
            );
            return json_encode($result);
        } else {
            // if we like a video we must also delete our dislike if there is one.
            $query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username and videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $videoId);
            $query->execute();
            $count = $query->rowCount();

            // user has not liked.
            $query = $this->con->prepare("INSERT INTO likes(username, videoId) VALUES(:username, :videoId)");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $videoId);
            $query->execute();
            $result = array (
                "likes" => 1,
                "dislikes" => 0 - $count
            );
            return json_encode($result);
        }
    }

    public function dislike() {
        $videoId = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
        if($this->wasDislikedBy()) {
            // user has already disliked.
            $query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username and videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $videoId);
            $query->execute();

            $result = array (
                "likes" => 0,
                "dislikes" => -1
            );
            return json_encode($result);
        } else {
            // if we dislike a video we must also delete our like if there is one.
            $query = $this->con->prepare("DELETE FROM likes WHERE username=:username and videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $videoId);
            $query->execute();
            $count = $query->rowCount();

            // user has not disliked.
            $query = $this->con->prepare("INSERT INTO dislikes(username, videoId) VALUES(:username, :videoId)");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $videoId);
            $query->execute();
            $result = array (
                "likes" => 0 - $count,
                "dislikes" => 1
            );
            return json_encode($result);
        }
    }

    public function wasLikedBy() {
        $videoId = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
        $query = $this->con->prepare("SELECT * FROM likes WHERE username=:username and videoId=:videoId");
        $query->bindParam(":username", $username);
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function wasDislikedBy() {
        $videoId = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
        $query = $this->con->prepare("SELECT * FROM dislikes WHERE username=:username and videoId=:videoId");
        $query->bindParam(":username", $username);
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function getNumberOfComments() {
        $videoId = $this->getId();
        $query = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId");
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        return $query->rowCount();
    }

    public function getComments() {
        $videoId = $this->getId();
        // get comments in response to this video (not other comments).
        $query = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId and responseTo=0 ORDER BY datePosted DESC");
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        $comments = array();

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($this->con, $row, $this->userLoggedInObj, $videoId);
            array_push($comments, $comment);
        }

        return $comments;
    }
}
?>