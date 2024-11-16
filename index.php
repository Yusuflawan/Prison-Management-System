<?php

include 'Classes/Database.php';
include 'Classes/Admin.php';

$db_conn = new Database();
$db = $db_conn->connect();

$admin = new Admin($db);


if (isset($_POST['submitBtn'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("location: index.php?error=emptyfields&username=".$email);
        exit();
    }
    else{
        $result = $admin->getAdmin();

        $loginSuccessful = false;

        if ($result->num_rows > 0) {        
            while ($row = $result->fetch_assoc()) {
                if ($row['email'] === $email && $row['password']  === $password) {
                    session_start();
                    $_SESSION['adminId'] = $row['id'];
                    $_SESSION['firstName'] = $row['firstName'];
                    $_SESSION['lastName'] = $row['lastName'];
                    $_SESSION['role'] = $row['role'];

                    $loginSuccessful = true;
                    break;
                }
                
            }
            if (!$loginSuccessful) {
                header("location: index.php?error=unauthoriseduser");
            }
            else{
                header("location: dashboard.php");
            }
        }
        else{
            header("location: index.php?error=emptyadmintable");
        }

    }
    // mysqli_stmt_close($stmt);
    mysqli_close($db);

}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prison</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <div class="login-form-container">
        <form action="" method="post">
            <h2>Admin Login</h2>
            <?php 
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == "emptyfields") {
                        echo "<p class='error-message'>Fill in all fields</p>";
                    }
                    elseif ($_GET['error'] == "unauthoriseduser"){
                        echo "<p class='error-message'>This user does not exist</p>";
                    }
                    elseif ($_GET['error'] == "emptyadmintable"){
                        echo "<p class='error-message'>Empty admin table</p>";
                    }
                }
            ?>
            <div class="errorMsg"></div>
            <div class="username">
                <label for="username">email</label>
                <input type="email" name="email" id="username">
            </div>
            <div class="password">
                <label for="password">password</label>
                <input type="password" name="password" id="password">
            </div>
            <button type="submit" name="submitBtn">login</button>
        </form>
    </div>

</body>

<script type="text/javascript">
    function preventBack() {
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    }

    window.onload = preventBack;
</script>


</html>
