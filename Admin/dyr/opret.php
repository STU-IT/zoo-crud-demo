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
//var_dump($_POST);
//var_dump($post); 
/*****************************************************************************
 * 
 * Håndtér form
 * 
 ****************************************************************************/
if (isset($post['action']) && $post['action'] == 'Opret')
{
    $billedeFil = '';
    
/*****************************************************************************
 * 
 * Håndtér billede til upload
 * 
 ****************************************************************************/
    
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
    
    $sql = "INSERT INTO dyr (navn, chipid, billede, fk_dyreart_id) "
            . "VALUES ('$post[navn]', '$post[chipid]', '$billedeFil', '$post[dyreart]')";
    $query = mysqli_query($con, $sql) OR die($sql ."<br>\n" .mysqli_error($con));
     
    header('Location: ./');
}

/*****************************************************************************
 * 
 * Håndtér fremmednøgle tabeller
 * 
 ****************************************************************************/


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
    <h2>Dyr</h2>
</div>
<div class="content">
    <p>
        <strong>Opret dyr</strong>
    </p>

    <form action="" method="post" enctype="multipart/form-data"> 
        <p>
            <span class="label">Navn:</span> 
            <input type="text" name="navn"  >
        </p>
        <p>
            <span class="label">ChipId:</span> 
            <input type="text" name="chipid" value="<?php echo uniqid(); ?>" >
        </p>
        <p>
            <span class="label">Billede:</span> 
            <input type="file" name="billede">
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

            $sql = "SELECT * FROM dyreart ORDER BY id ASC";
            $query = mysqli_query($con, $sql);

            while ($dyreArtRow = mysqli_fetch_assoc($query)) 
            {
                ?>
                <option value="<?php echo $dyreArtRow['id'] ?>">
                    <?php echo $dyreArtRow['navn'] ?>
                </option>
                <?php
            }
            ?>        
            </select>
        </p>
        <input type="reset" onclick="document.location = './'" value='Fortryd'>
        <input type="submit" name="action" value="Opret" >
    </form>

</div>
    
</body>
</html>    
    