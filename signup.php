<?php
$fname = "";
$lname = "";
$email = "";
$sex = "";
$pass1 = "";
$pass2 = "";
$err = array();
$congra = "";

$conn = mysqli_connect("localhost", "root", "", "db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['Signup'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sex = mysqli_real_escape_string($conn, $_POST['Sex']);
    $pass1 = mysqli_real_escape_string($conn, $_POST['pass1']);
    $pass2 = mysqli_real_escape_string($conn, $_POST['pass2']); 
    
    if($pass1 != $pass2) {
        array_push($err, "The two passwords do not match");
    }
    $user_check_query = "SELECT * FROM user WHERE Fname='$fname' OR Email='$email' LIMIT 1"; 
    $result = mysqli_query($conn, $user_check_query);
    
    if($result) {
        $user = mysqli_fetch_assoc($result);
        if($user) {
            if($user['Fname'] === $fname) {
                array_push($err, "Username already exists!"); 
            }
            if($user['Email'] === $email) {
                array_push($err, "Email is already registered!");  
            }
        }
    }
    
    if(count($err) === 0) { 
        $query = "INSERT INTO user (Fname, Lname, Sex, Email, password) 
                 VALUES ('$fname', '$lname', '$sex', '$email', '$pass1')";
        if(mysqli_query($conn, $query)) {
            $congra = "You are successfully registered! Please login";
        } else {
            array_push($err, "Registration failed: " . mysqli_error($conn));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup with PHP</title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<body style="background-image: url(images/bbb.jpg);">
   <div class="container">
    <h1>SIGNUP Here</h1>
    <div class="err">
        <?php include "err.php"; ?>
    </div>
    <?php if($congra != "") { echo "<p style='color:green;'>$congra</p>"; } ?>
    <form action="signup.php" method="POST">
        <input type="text" name="fname" value="<?php echo $fname; ?>" placeholder="Enter firstname" required><br>
        <input type="text" name="lname" value="<?php echo $lname; ?>" placeholder="Enter lastname" required><br>
        <input type="email" name="email" value="<?php echo $email; ?>" placeholder="Enter email" required><br>
        <label for="">Sex</label>
        <input type="radio" name="Sex" required>Male
        <input type="radio" name="Sex" required>Female<br>
        <input type="password" name="pass1" placeholder="Enter password" required><br>
        <input type="password" name="pass2" placeholder="Confirm password" required><br>
        <input type="submit" value="Signup" name="Signup"><br>
        Already a member? <a href="login.php" style="background-color: #ffc102; text-decoration:none">LOGIN</a>
    </form>
   </div> 
</body>
</html>