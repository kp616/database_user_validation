<?php
$errors = 0;
$email_present = 0;

if (isset($_POST['submit'])) {

    //validating fields
    if(empty($_POST['firstname'])){
        echo 'ERROR: Required field [First name] is missing. <br/><br/>';
        $errors += 1;
    } else {
        $FIRST_NAME = $_POST['firstname'];
        if(!preg_match('/^[a-zA-Z\s]+$/', $FIRST_NAME) || strlen($FIRST_NAME) > 20) {
            echo 'ERROR: First name must only contain a-z letters and spaces only. Must be between 1-20 characters long.<br/><br/>';
            $errors += 1;
        }
    }
    if(empty($_POST['lastname'])){
        echo 'ERROR: Required field [Last name] is missing. <br/><br/>';
        $errors += 1;
    }
    else{
        $LAST_NAME = $_POST['lastname'];
        if(!preg_match('/^[a-zA-Z\s]+$/', $LAST_NAME) || strlen($LAST_NAME) > 20) {
            echo 'ERROR: Last name must only contain a-z letters and spaces only. Must be between 1-20 characters long.<br/><br/>';
            $errors += 1;
        }
    }
    if(empty($_POST['phonenumber'])){
        echo 'ERROR: Required field [Phone number] is missing. <br/><br/>';
        $errors += 1;
    } else{
        $PH_NUM = $_POST['phonenumber'];
        if(!preg_match('/^[0-9]+$/', $PH_NUM) || strlen($PH_NUM) > 10) {
            echo 'ERROR: Phone number must be less than or equal to 10 digits, containing only numbers. No area code required.<br/><br/>';
            $errors += 1;
        }
        
    }
    if(empty($_POST['address'])){
        echo 'ERROR: Required field [Address] is missing. <br/><br/>';
        $errors += 1;
    } else {
        $ADDRESS = $_POST['address'];
        if (!preg_match('/^[a-zA-Z0-9 \/,-]+$/', $ADDRESS)) {
            echo 'ERROR: Address must only contain letters, numbers or "/ , and -" characters and spaces only.<br/><br/>';
            $errors += 1;
        } elseif (strlen($ADDRESS) > 50)
        {
            $errors += 1;
            echo 'ERROR: Address inputted is too long. Must be between 1-50 characters long.';
        }
    }
    if(!empty($_POST['email']))
    {
        $email_present += 1;
        $EMAIL = $_POST['email'];
        if(!filter_var($EMAIL, FILTER_VALIDATE_EMAIL) || strlen($EMAIL) > 30){
            echo 'ERROR: Email must be a valid email address. Must also be between 1-30 characters long.<br/><br/>';
            $errors += 1;
        }
    }
}

if ($errors == 0) {
    
    $MEMBER_STATUS = 'ACTIVE';

    //DATABASE CONNECTION
    $conn = new mysqli('localhost', 'root', 'cooking1', 'db_connect');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    if ($email_present == 0) {
        $sql = "INSERT INTO tbl_contact (FIRST_NAME, LAST_NAME, PH_NUM, ADDRESS, MEMBER_STATUS) VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die(mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $FIRST_NAME,
            $LAST_NAME,
            $PH_NUM,
            $ADDRESS,
            $MEMBER_STATUS
        );
        } else {
        $sql = "INSERT INTO tbl_contact (FIRST_NAME, LAST_NAME, PH_NUM, EMAIL, ADDRESS, MEMBER_STATUS) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die(mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $FIRST_NAME,
            $LAST_NAME,
            $PH_NUM,
            $EMAIL,
            $ADDRESS,
            $MEMBER_STATUS
        );
    }
    mysqli_stmt_execute($stmt);
    echo "Record saved. Insertion of record has been successful";
}
else {
    echo '<b>Please try and fill out all fields correctly and try again.</b>';
}
?>