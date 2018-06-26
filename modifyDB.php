<?php
    $conn = mysqli_connect('localhost','root','LineLA','LineLA');
    mysqli_set_charset($conn, 'utf8');
    $ID = $_GET['id'];
    $sql = "SELECT visitRegisterID FROM visitRegister WHERE registrarID = '$ID'";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $visitRegisterID = $row['visitRegisterID'];

        $sql = "DELETE FROM visitRegister WHERE visitRegisterID = '$visitRegisterID'";
        $result = mysqli_query($conn,$sql);
    }
?> 