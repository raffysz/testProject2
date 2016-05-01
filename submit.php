<?php
$msg = "";
if(isset($_POST["submit"]))
{
    $name = $_POST["username"];
    $name = mysqli_real_escape_string($db, $name);
    $name = htmlspecialchars($name);

    $email = $_POST["email"];
    $email = mysqli_real_escape_string($db, $email);
    $email = htmlspecialchars($email);

    $password = $_POST["password"];
    $password = mysqli_real_escape_string($db, $password);
    $password = htmlspecialchars($password);



    $sql="SELECT email FROM users WHERE email='$email'";
    $result=mysqli_query($db,$sql);
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    if(mysqli_num_rows($result) == 1)
    {
        $msg = "Sorry...This email already exists...";
    }
    else
    {
        //echo $name." ".$email." ".$password;
        $query = mysqli_query($db, "INSERT INTO users (username, email, password, status) VALUES ('$name', '$email', '$password')")or die(mysqli_error($db));
        if($query)
        {
            $msg = "Thank You! you are now registered. Please wait a confirmation mail of account activation before login";
        }

    }
}
?>