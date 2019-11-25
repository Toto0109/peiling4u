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
    }
    else 
    {
        echo "<form action='login.php' method='post'>
                Gebruikersnaam: <br>
                <input type='text' name='gebruikersnaam'/> <br>
                Wachtwoord: <br>
                <input type='password' name='wachtwoord'/> <br>
                <input type='submit' name='login' value='Login'> <br>
                <a href='aanmelden.php'>Heb je geen account? Meldt je aan via deze link</a>
	        </form>";
    }
    ?>
</body>
</html>

<?php
if(isset($_POST["loguit"]))
{
    header("Refresh:0"); // Ververst de pagina
    session_destroy();
}

if(isset($_POST["login"]))
{
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $gebruikersnaam = mysqli_real_escape_string($mysql, $_POST["gebruikersnaam"]);

    // Query voor gebruikersnr, gebruikersnaam, wachtwoord
    $resultaat = mysqli_query($mysql,"SELECT gebruikersnr, gebruikersnaam, wachtwoord 
                                      FROM gebruikers 
                                      WHERE gebruikersnaam = '$gebruikersnaam'") 
        or die("De query 1 op de database is mislukt!");

    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($gebruikersnr, $gebruiker, $hash) =  mysqli_fetch_row($resultaat);

    // Verifieert het wachtwoord
    if(password_verify($_POST["wachtwoord"], $hash))
    {
        header("Refresh:0");
        echo "Ingelogd als: ".$gebruiker;
        $_SESSION["logged_in"] = true;
        $_SESSION["gebruiker"] = $gebruikersnr;
    }
    else
    {
        echo "De gebruikersnaam of het wachtwoord klopt niet";
    }
}
?>
