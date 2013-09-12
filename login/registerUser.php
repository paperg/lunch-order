<?php
include_once 'redirectHome.php';
include_once '../mysql.php';

$salt1 = "*qba";
$salt2 = "cl@&";
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$token = md5("$salt1$password$salt2");

try {
    if(sqlUsernameExists($username)) {
        $alert = '<div id="username-warning" class="alert alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        This username has already been taken. Please choose a different one.
                    </div>';
        echo json_encode($alert);
    } else {
        $statement = $pdo->prepare("INSERT INTO users (username,password,first_name,last_name,email) VALUES (?,?,?,?,?)");
        $statement->execute(array($username,$token,$first_name,$last_name,$email));
        $_SESSION['username'] = $username;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['user_id'] = $pdo->lastInsertID();
        $_SESSION['display_quote'] = true;
        $alert = '<div class="alert alert-dismissable alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Welcome! You have been added to the list of registered users in the PaperG lunch ordering system.
            Visit the <a href="../index.php" class="alert-link">LunchMaster homepage</a> to vote on the restaurant of the day or place your order.</div>';
        echo json_encode($alert);
    }
} catch (PDOException $exception) {
    $error = "PDO error :" . $exception->getMessage();
    echo json_encode($error);
}
