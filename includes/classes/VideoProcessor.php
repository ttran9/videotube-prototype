<?php
class VideoProcessor {

    private $con;
    private $sizeLimit = 83886080;
    private $allowedTypes = array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg");
//    private $ffmpegPath = "ffmpeg/bin/ffmpeg"; // MAC (USING REGFULAR XAMPP)
    private $ffmpegPath; // windows..
    private $ffprobePath;

    public function __construct($con) {
        $this->con = $con;
        $this->ffmpegPath = realpath("ffmpeg/bin/ffmpeg.exe");
        $this->ffprobePath = realpath("ffmpeg/bin/ffprobe.exe");
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

            if(!$this->convertVideoToMp4($tempFilePath, $finalFilePath)) {
                echo 'Upload failed';
                return false;
            }

            if(!$this->deleteFile($tempFilePath)) {
                echo 'Delete failed\n';
                return false;
            }

            if(!$this->generateThumbnails($finalFilePath)) {
                echo 'Upload failed - could not generate thumbnails\n';
                return false;
            }

            return true;
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

    private function convertVideoToMp4($tempFilePath, $finalFilePath) {
        $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1"; // 2>&1 (outputs errors).
        $outputLog = array(); // contains the output the script gives us for running ffmpeg.
        exec($cmd,$outputLog, $returnCode); // $returnCode is created.

        if($returnCode != 0) {
            // command failed.
            foreach ($outputLog as $line) {
                echo $line . "<br/>";
            }
            return false;
        }

        return true;
    }

    private function deleteFile($filePath) {
        if(!unlink($filePath)) {
            echo 'Could not delete file\n';
            return false;
        }
        return true;
    }

    public function generateThumbnails($filePath) {
        $thumbnailSize = "210x118";
        $numThumbnails = 3;
        $pathToThumbnail = "uploads/videos/thumbnails";

        $duration = $this->getVideoDuration($filePath);

        $videoId = $this->con->lastInsertId();
        $this->updateDuration($duration, $videoId);

        for($num = 1; $num <= $numThumbnails; $num++) {
            $imageName = uniqid() . ".jpg";
            $interval = ($duration * 0.8) / $numThumbnails * $num;
            $fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";

            $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1"; // 2>&1 (outputs errors).
            $outputLog = array(); // contains the output the script gives us for running ffmpeg.
            exec($cmd,$outputLog, $returnCode); // $returnCode is created.

            if($returnCode != 0) {
                // command failed.
                foreach ($outputLog as $line) {
                    echo $line . "<br/>";
                }
            }

            $query = $this->con->prepare("INSERT INTO thumbnails(videoId, filePath, selected) 
                                          VALUES(:videoId, :filePath, :selected)");
            $query->bindParam(":videoId", $videoId);
            $query->bindParam(":filePath", $filePath);
            $query->bindParam(":selected", $selected);

            $selected = $num == 1 ? 1 : 0;

            $success = $query->execute();
            if(!$success) {
                echo "Error inserting thumbnail\n";
                return false;
            }
        }
        return true;
    }

    private function getVideoDuration($filePath) {
        return (int) shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath"); // shell_exec returns the output but is identical to exec otherwise.
    }

    private function updateDuration($duration, $videoId) {
        $hours = floor($duration / 3600);
        $mins = floor(($duration - ($hours  * 3600)) / 60);
        $secs = floor($duration % 60);

        $hours = ($hours < 1) ? "" : $hours . ":";
        $mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
        $secs = ($secs < 10) ? "0" . $secs : $secs;

        $duration = $hours.$mins.$secs;

        $query = $this->con->prepare("UPDATE videos SET duration=:duration WHERE id=:videoId");
        $query->bindParam(":duration", $duration);
        $query->bindParam(":videoId", $videoId);
        $query->execute();
    }
 }
?>