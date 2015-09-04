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
    'id'        =>  array(  'filter'    =>  FILTER_SANITIZE_NUMBER_INT,
                            'options'   =>  array('min_range' => 1)
                ),
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
if (isset($post['action']) && $post['action'] == 'Gem')
{
    $sql = "UPDATE dyreracer SET navn = '$post[navn]' WHERE id = '$post[id]'";
    $query = mysqli_query($con, $sql);
    header('Location: ./');
}

/*****************************************************************************
 * 
 * Håndtér load m. get og id i querystring (dvs ?id=X )
 * 
 ****************************************************************************/
$sanitizeId =  filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if( filter_var($sanitizeId, FILTER_VALIDATE_INT) )
{

    $sql = "SELECT * FROM dyreracer WHERE id = $sanitizeId";
    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($query);
}

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../adminStyle.css">
    <title>Rediger - Dyreracer - AdminZoo</title>
</head>

<html>
<body>
    
<div class="header">
    <h1>Zoo Admin</h1>
    <h2>Dyreracer</h2>
</div>
<div class="content">
    <p>
        <strong>Rediger dyrearten</strong>
    </p>

    <form action="" method="post">
        <span class="label">Artsnavn:</span> 
        <input type="text" name="navn" value="<?php echo $row['navn'] ?>" >
        <input type="hidden" name="id" value="<?php echo $row['id'] ?>" >
      
        <input type="reset" onclick="document.location = './'" value='Fortryd'>
        <input type="submit" name="action" value="Gem" >
    </form>
    
</div>
    
</body>
</html>    
    