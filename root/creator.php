<?php
include "connect.php";
include "functies.php";
session_start();

// Kijkt of de gebruiker is ingelogd
if(!isset($_SESSION["logged_in"]))
{
    $_SESSION["logged_in"] = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1 ">
    <title>Login</title>
</head>
<body>
    <a href="index.php">Home</a> <br>
    <?php
    if($_SESSION["logged_in"] == true)
    {
        echo "Ingelogd als: ";
        echo get_gebruikersnaam($_SESSION["gebruiker"]);
        
        echo "<form action='login.php' method='post'>
                <input type='submit' name='loguit' value='Loguit'>
            </form>";
        
        echo "<form action='creator.php' method='post'>
                Peilingnaam: <input type='text' name='titel'> <br>
                Openbaar: <input type='checkbox' name='openbaar' value='1'> <br>
                <input type='submit' name='maakpeiling' value='Maak een nieuwe peiling'>
            <form>
            <br>";
        
        $mysql = mysqli_connect($server,$user,$pass,$db) 
            or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

        $resultaat = mysqli_query($mysql,"SELECT peilingnr, titel 
                                          FROM peilingen
                                          WHERE gebruikersnr = $_SESSION[gebruiker]") 
            or die("De query 1 op de database is mislukt!");
        
        mysqli_close($mysql) 
            or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
        
        echo "Uw peilngen: <br>";
        while(list($peilingnr, $peilingtitel) = mysqli_fetch_row($resultaat))  
        {
            echo"<a href='creator.php?nr=$peilingnr'>$peilingtitel<a/><br />"; 
        }
    }
    else 
    {
        echo "<a href='login.php'>Je moet eerst inloggen voordat je een peiling kan maken</a>";
    }
    ?>
</body>
</html>

<?php
if(isset($_POST["maakpeiling"]))
{
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $gebruikersnr = $_SESSION["gebruiker"];
    $titel = mysqli_real_escape_string($mysql, $_POST["titel"]);
    $openbaar = $_POST["openbaar"];

    mysqli_query($mysql,"INSERT INTO peilingen(gebruikersnr,titel,openbaar) 
                         VALUES('$gebruikersnr','$titel','$openbaar')") 
        or die("De insertquery op de database is mislukt!"); 
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
    
    
}
?>
