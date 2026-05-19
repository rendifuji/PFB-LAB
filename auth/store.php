<?php
    include "../connection.php";

    $Credit = 0;
    $Role = "User";

    $result = mysqli_query($conn,"SELECT * FROM msuser");
    $numrows = mysqli_num_rows($result);
    $nextnumber = $numrows + 1;
    $formattednumber = str_pad($nextnumber, 3,'0',STR_PAD_LEFT);
    $userid = "US".$formattednumber;

    // echo "the new Userid is : ",$userid;

    



    if(isset($_POST["save"])){
        $Username = $_POST['username'];
        $Email = $_POST['email'];
        $UserPassword = $_POST['password'];
    }

    if($Username == '' || $Email == ''|| $UserPassword ==''){
        echo "The form cannot be empty";
        header("location: register.php");
    }
    else{
        $query= "Insert into msuser values ('$userid','$Username','$UserPassword','$Email','$Credit','$Role')";

        mysqli_query($conn,$query);
        echo "data enterred succesfully";
    }




?>