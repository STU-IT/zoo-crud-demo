<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../adminStyle.css">
    <title>Dyr - AdminZoo</title>
</head>

<html>
<body>
    
<div class="header">
    <h1>Zoo Admin</h1>
    <h2>Dyr</h2>
</div>
<div class="content">
    <div class="row">
        <a href="opret.php" class="button">Opret nyt dyr</a>
        <div class='search'>
            <input type="text" name="searchString" disabled >
            <input type="submit" name="search" value="Søg" disabled>
        </div>
    </div>
    <div class="scroller">
        <ul>
            <?php
                $host = "localhost";
                $user = "root";
                $password = '';
                $database = 'zoo';
                
                $con = mysqli_connect($host, $user, $password, $database);
                
                $sql = "SELECT * FROM dyr ORDER BY id ASC";
                $query = mysqli_query($con, $sql);
                
                while ($row = mysqli_fetch_assoc($query)) 
                {
                
            ?>
           <li class="row">
                <a href="slet.php?id=<?php echo $row['id'] ?>" class="button">Slet</a> 
                <a href="rediger.php?id=<?php echo $row['id'] ?>">
                    <?php echo $row['navn'] ?>
                </a>
                <img src="../../images/thumbs/<?php echo $row['billede'] ?>"
            </li>   
            
            <?php
                }
            ?>

        </ul>
    </div>
</div>
    
</body>
</html>    
    