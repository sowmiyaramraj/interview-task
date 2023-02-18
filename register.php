<?php
include 'config.php';
if(isset($_port['submit'])){
$name=mysqli_real_escape_string($conn,$_port['name']);
$email=mysqli_real_escape_string($conn,$_port['email']);
$password=mysqli_real_escape_string($conn, md5($_port['password']));
$cpassword=mysqli_real_escape_string($conn,md5($_port['cpassword']));
$image=$_FILES['image']['name'];
$image_size=$_FILES['image']['size'];
$image_tmp_name=$_FILES['image']['tmp_name'];
$image_folder='uploaded_img/'.$image;

$select= mysqli_query($conn,"SELECT * FROM `user_form` WHERE email='$email' AND password='$password'") or die('query failed');

if(mysql_nums_rows($select) > 0) 
{
    $message[]='user already exist';
}
else{
    if($password != $cpassword){
        $message[]='password doesnot match';
    }
    elseif($image_size > 2000000){
        $message[]= 'image size is too large';
    }
    else
    {
        $insert[]=mysqli_query($conn,"INSERT INTO `user_form` (name,email,password,avatar) 
        VALUES ('$name','$email','$password','$image')") or die('query failed');

        if($insert){
            move_uploaded_file($image_tmp_name,$image_folder);
            $message[]='register successfully';
            header('location:login.php');
        }
        else{
            $message[]='register unsuccessfully';
            }
        }
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container">        
        <form action="" method="post" enctype="multipart/form-data">
            <h2>Signup Here</h2>
            <?php
            if(isset($message)){
                foreach($message as $message)
              {  echo '<div class="message">'.$message.'</div>';}
            }
            ?>
            <input type="text" name="name" placeholder="Enter username" class="box" required> <br/><br/>
            <input type="text" name="email" placeholder="Enter Email" class="box" required><br/><br/>
            <input type="text" name="password" placeholder="Enter password" class="box" required><br/><br/>
            <input type="text" name="cpassword" placeholder="Confirm password" class="box" required><br/><br/>
            <input type="file" class="box" name="image" accept="image/jpg,img/jpeg,img/png"><br/><br/>
            <input type="submit" name="submit" value="register now" class="btn"><br/><br/>
            <p>Already have an account <a href="login.php" >Login</a></p>
        </form>
    </div>
    
</body>
</html>