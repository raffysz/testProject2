<?php
session_start();
include("connection.php"); //Establishing connection with our database

$msg = ""; //Variable for storing our errors.
if(isset($_POST["submit"]))
{
    $title = $_POST["title"];
    $title = mysqli_real_escape_string ($db, $title);

    $desc = $_POST["desc"];
    $desc = mysqli_real_escape_string ($db, $desc);

    $url = "test";

    $name = $_SESSION["username"];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $uploadOk = 1;

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false){
        $msg = "File is an image -" .$check["mime"]. ".";
        $uploadOk = 1;
    }else{
        $msg = "File is not an image.";
        $uploadOk = 0;
    }

   if(file_exists($target_file)){
       $msg = "Sorry, file already exists.";
       $uploadOk = 0;
   }

    if($_FILE["fileToUpload"]["size"] > 500000){
        $msg = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if($imageFileType !="jpg" &&
        $imageFileType !="png" &&
        $imageFileType !="jpeg" &&
        $imageFileType !="gif"){
        $msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0){
        $msg = "Sorry, your file was not uploaded.";
    }else {
        $sql = "SELECT userID FROM users WHERE username='$name'";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if (mysqli_num_rows($result) == 1) {
            //$timestamp = time();
            //$target_file = $target_file.$timestamp;
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $id = $row['userID'];
                $addsql = "INSERT INTO photos (title, description, postDate, url, userID) VALUES ('$title','$desc',now(),'$target_file','$id')";
                $query = mysqli_query($db, $addsql) or die(mysqli_error($db));
                if ($query) {
                    $msg = "Thank You! The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded. click <a href='photos.php'>here</a> to go back";
                }

            } else {
                $msg = "Sorry, there was an error uploading your file.";
            }
        } //echo $name." ".$email." ".$password;


        else {
            $msg = "You need to login first";
        }
    }
}

?>