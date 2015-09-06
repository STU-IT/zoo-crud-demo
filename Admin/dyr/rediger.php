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
    'chipid'    =>  array(  'filter'    => FILTER_SANITIZE_STRING,
                            'flags'     => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_AMP
                ),
    
    'dyreart'   =>  array(  'filter'    =>  FILTER_SANITIZE_NUMBER_INT,
                            'options'   =>  array('min_range' => 1)
                ),
    'action'    =>  array(  'filter'    => FILTER_SANITIZE_STRING,
                            'flags'     => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_AMP
                )
);
$post = filter_input_array(INPUT_POST, $formArgs);
    
/*****************************************************************************
 * 
 * Håndtér billede til upload
 * 
 ****************************************************************************/
$billedeFil = '';
//var_dump($_FILES);
if(isset($_FILES['billede']['tmp_name']) && $_FILES['billede']['tmp_name'] != null)
{
   $billedeNavn = uniqid($_FILES['billede']['name'], TRUE).'.png'; // et unikt id med filnavnet som prefix, og extra entropi

   require_once '../../includes/wideimage/lib/WideImage.php';
   $img = WideImage::loadFromUpload('billede');
   $thumb = $img->resize(100, 75, 'outside')->crop('center', 'center', 100, 75);
   $thumb->saveToFile('../../images/thumbs/'.$billedeNavn, 7, PNG_NO_FILTER);

   $billede = $img->resize(800, 600, 'outside')->crop('center', 'center', 800, 600);
   $billede->saveToFile('../../images/'.$billedeNavn, 7, PNG_NO_FILTER);

   $billedeFil = $billedeNavn;
}
//var_dump($billedeFil);
    
/*****************************************************************************
 * 
 * Håndtér form
 * 
 ****************************************************************************/
if (isset($post['action']) && $post['action'] == 'Gem')
{
    $sql = "UPDATE dyr "
            . "SET navn = '$post[navn]', "
                . "chipid = '$post[chipid]', " 
                
                // hvis der ikke er nogen fil, så ikke noget, ELLERS opdater billede-feltet
                . (empty($billedeFil) ? '' : "billede = '$billedeFil', " ) 
                
                . "fk_dyreart_id = '$post[dyreart]' "
            . "WHERE id = '$post[id]'";
    //var_dump($sql);
    $query = mysqli_query($con, $sql) OR die($sql . mysqli_error($con));
    
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

    $sql = "SELECT * FROM dyr WHERE id = $sanitizeId";
    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($query);
}

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../adminStyle.css">
    <title>Rediger - Dyr - AdminZoo</title>
</head>

<html>
<body>
    
<div class="header">
    <h1>Zoo Admin</h1>
    <h2>Dyr</h2>
</div>
<div class="content">
    <p>
        <strong>Rediger dyret</strong>
    </p>

    <form action="" method="post" enctype="multipart/form-data">
        <p>
            <span class="label">Kaldenavn:</span> 
            <input type="text" name="navn" value="<?php echo $row['navn'] ?>" >
        </p>
        <p>
            <span class="label">Chipid:</span> 
            <input type="text" name="chipid" value="<?php echo $row['chipid'] ?>" >
        </p>
        <p>
            <span class="label">Billede:</span> 
            <input type="file" name="billede" > <img src="../../images/thumbs/<?php echo $row['billede'] ?>" >
        </p>
         <p>
            <span class="label">Dyreart:</span> 
            <select name="dyreart" >
            <?php

           /*****************************************************************************
            * 
            * Håndtér fremmednøgle tabeller
            * 
            ****************************************************************************/

            $valgtId = $row['fk_dyreart_id'];
            
            $sql = "SELECT * FROM dyreart ORDER BY id ASC";
            $query = mysqli_query($con, $sql);
            
            while ($dyreArtRow = mysqli_fetch_assoc($query)) 
            {
                if ($dyreArtRow['id'] == $valgtId)
                {
                    $selected = ' selected';
                }
                else
                {
                    $selected = '';
                }
                ?>
                <option value="<?php echo $dyreArtRow['id'] ?>" <?php echo $selected ?> >
                    <?php echo $dyreArtRow['navn'] ?>
                </option>
                <?php
            }
            ?>        
            </select>
        </p>
        <input type="hidden" name="id" value="<?php echo $row['id'] ?>" >
      
        <input type="reset" onclick="document.location = './'" value='Fortryd'>
        <input type="submit" name="action" value="Gem" >
    </form>
    
</div>
    
</body>
</html>    
    