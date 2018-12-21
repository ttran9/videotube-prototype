<?php
class VideoProcessor {

    private $con;
    private $sizeLimit = 83886080;
    private $allowedTypes = array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg");

    public function __construct($con) {
        $this->con = $con;
    }

    public function upload($videoUploadData) {
        $targetDir = "uploads/videos/";
        $videoData = $videoUploadData->getVideoDataArray();
        $tempFilePath = $targetDir . uniqid() . basename($videoData['name']);
//        $tempFilePath = str_replace("\\s+", "_", $tempFilePath);
        $tempFilePath = str_replace(" ", "_", $tempFilePath);

        $isValidData = $this->processData($videoData, $tempFilePath);

        if(!$isValidData) {
            return false;
        }

        if(move_uploaded_file($videoData["tmp_name"], $tempFilePath)) {
            // if it finds the temporary stored file
            $finalFilePath = $targetDir . uniqid() . ".mp4";

            // $videoUploadData holds the information from the <input type=file ... /> tag"
            if(!$this->insertVideoData($videoUploadData, $finalFilePath)) {
                echo 'Insert query failed';
                return false;
            }
        }
    }

    private function processData($videoData, $filePath) {
        $videoType = pathinfo($filePath, PATHINFO_EXTENSION);

        if(!$this->isValidSize($videoData)) {
            echo "File too large. Can't be more than " . $this->sizeLimit . + " bytes";
            return false;
        } else if(!$this->isValidType($videoType)) {
            echo "Invalid file type";
            return false;
        } else if($this->hasError($videoData)) {
            echo "Error code: " . $videoData["error"];
            return false;
        }

        return true;
    }

    private function isValidSize($data) {
        return $data["size"] <= $this->sizeLimit;
    }

    private function isValidType($type) {
        $lowercased = strtolower($type);
        return in_array($lowercased, $this->allowedTypes);
    }

    private function hasError($data) {
        return $data["error"] != 0;
    }

    private function insertVideoData($uploadData, $filePath) {
        $query = $this->con->prepare("INSERT INTO videos(title, uploadedBy, description, privacy, category, filePath) 
                                        VALUES(:title, :uploadedBy, :description, :privacy, :category, :filePath)");
        $title = $uploadData->getTitle();
        $uploadedBy = $uploadData->getUploadedBy();
        $description = $uploadData->getDescription();
        $privacy = $uploadData->getPrivacy();
        $category = $uploadData->getCategory();
        $query->bindParam(":title", $title);
        $query->bindParam(":uploadedBy", $uploadedBy);
        $query->bindParam(":description", $description);
        $query->bindParam(":privacy", $privacy);
        $query->bindParam(":category", $category);
        $query->bindParam(":filePath", $filePath);

        // want to return the boolean value which indicates if the query worked or not.
        return $query->execute();
    }
}
?>