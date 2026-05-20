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

    if (strlen($Username) < 8){
        echo"Username must atleast be 8 characters long";
        return;
    }

    if (strlen($UserPassword) < 8){
        echo"Password must atleast contain 8 characters";
        return;
    }


    else{
        $hash = password_hash($UserPassword,PASSWORD_BCRYPT);
        $query= "Insert into msuser values ('$userid','$Username','$hash','$Email','$Credit','$Role')";

        mysqli_query($conn,$query);
        echo "data enterred succesfully";
    }




?>