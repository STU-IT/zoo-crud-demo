<?php
    session_start();
    
    $msg = "";
    
    if (@$_POST[action] == "Login")
    {
        
        $host = "localhost";
        $user = "root";
        $password = '';
        $database = 'zoo';

        $con = mysqli_connect($host, $user, $password, $database);
        
        $sql = "SELECT id FROM dyrepasser WHERE username = '$_POST[username]' AND pw = '$_POST[pw]'";
        $result = mysqli_query($con, $sql);
        if ($row = mysqli_fetch_assoc($result))
        {
            $_SESSION['userid'] = $row['id'];
            header("Location: Admin/");
        }
        else 
        {
            $msg = "Vi kan ikke logge dig ind. Prøv igen.";
        }
    }
    elseif (@$_POST['action'] == 'Log ud') {
        session_unset(); // eller session_abort() eller session_reset()
        header("Location: ./");
    }
    

    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login - Zoo</title>
    </head>
    <body>
        <form action="" method="post">
            <p>
                <?php echo $msg ?>
            </p>
            <p>
                <input type="text" name="username" placeholder="Username" >
                <input type="password" name="pw" placeholder="pw" >
                <input type="submit" name="action" value="Login">
            </p>
       
        <?php
            if ( !empty($_SESSION['userid']) )
            {
                ?>
        <input type="submit" name="action" value="Log ud">
                <?php
            }
        ?>
        
         </form>
        
    </body>
</html>
