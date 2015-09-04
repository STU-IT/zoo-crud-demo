<?php


/*****************************************************************************
 * 
 * Database connection
 * 
 ****************************************************************************/
$host = "localhost";
$user = "root";
$password = '';
$database = 'zoo';

$con = mysqli_connect($host, $user, $password, $database);

/*****************************************************************************
 * 
 * Filtrering af form input, for en sikkerheds skyld
 * 
 ****************************************************************************/
$formArgs = array(
//    'id'        =>  array(  'filter'    =>  FILTER_SANITIZE_NUMBER_INT,
//                        'options'   =>  array('min_range' => 1)
//                ),
    'navn'      =>  array(  'filter'    => FILTER_SANITIZE_STRING,
                            'flags'     => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_AMP
                ),
    'action'    =>  array(  'filter'    => FILTER_SANITIZE_STRING,
                            'flags'     => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_AMP
                )
);
$post = filter_input_array(INPUT_POST, $formArgs);

/*****************************************************************************
 * 
 * Håndtér form
 * 
 ****************************************************************************/
if (isset($post['action']) && $post['action'] == 'Opret')
{
    $sql = "INSERT INTO dyreracer (navn) VALUES ('$post[navn]')";
    $query = mysqli_query($con, $sql) OR die($sql ."<br>\n" .mysqli_error($con));
    
    header('Location: ./');
}

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../adminStyle.css">
    <title>Opret - Dyreracer - AdminZoo</title>
</head>

<html>
<body>
    
<div class="header">
    <h1>Zoo Admin</h1>
    <h2>Dyreracer</h2>
</div>
<div class="content">
    <p>
        <strong>Opret dyrearten</strong>
    </p>

    <form action="" method="post">
        <span class="label">Artsnavn:</span> 
        <input type="text" name="navn"  >
        
        <input type="reset" onclick="document.location = './'" value='Fortryd'>
        <input type="submit" name="action" value="Opret" >
    </form>

</div>
    
</body>
</html>    
    