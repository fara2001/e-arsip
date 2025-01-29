<?php
session_start();
include "koneksi.php";
if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $_SESSION["email"] = $email;
        header("Location: index.php");
    } else {
        echo "<script>alert('Login Gagal!'); window.location.href='login.php'</script>";
    }
}
?>
