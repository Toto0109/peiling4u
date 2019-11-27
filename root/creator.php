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
    if(isset($_GET["nr"]))
    {

        if ($_SESSION["gebruiker"] == get_gebruikersnr($_GET["nr"]))
        {
            bewerk_peiling($_GET["nr"]);
        }
        else
        {
            echo "U heeft geen bewerkingsrechten voor deze peilingen";
        }
    }
    else if($_SESSION["logged_in"] == true)
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
        
        list_peilingen($_SESSION["gebruiker"]);
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
    if(isset($_POST["openbaar"]))
    {
        $openbaar = 1;
    }
    else
    {
        $openbaar = 0;
    }

    mysqli_query($mysql,"INSERT INTO peilingen(gebruikersnr,titel,openbaar) 
                         VALUES('$gebruikersnr','$titel','$openbaar')") 
        or die("De insertquery op de database is mislukt!"); 
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    header("Refresh:0");
}

if(isset($_POST["save"]))
{
    save_peiling();
    header("Refresh:0");
}

if(isset($_POST["add_vraag"]))
{
    $peilingnr = $_GET["nr"];
    save_peiling();

    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $resultaat = mysqli_query($mysql,"SELECT MAX(vraagnr)
                         FROM vragen
                         WHERE peilingnr = '$peilingnr'")
        or die("De insertquery op de database is mislukt!"); 
    
    list($max_vraagnr) = mysqli_fetch_row($resultaat);
    $nieuwe_vraagnr = $max_vraagnr + 1;
    
    mysqli_query($mysql,"INSERT INTO vragen(peilingnr, vraagnr)
                         VALUES('$peilingnr','$nieuwe_vraagnr')") 
        or die("De insertquery op de database is mislukt!"); 
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    header("Refresh:0");

}
?>
