<?php
include "connect.php";
include "functies.php";
session_start();
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
    <?php
    if($_SESSION["logged_in"] == true)
    {
        $gebruikersnr = $_SESSION["gebruiker"];
        $mysql = mysqli_connect($server,$user,$pass,$db) 
            or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

        $resultaat = mysqli_query($mysql, "SELECT email, gebruikersnaam
                                           FROM gebruikers
                                           WHERE gebruikersnr = '$gebruikersnr'")
            or die("De query 1 op de database is mislukt!");

        mysqli_close($mysql) 
            or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

        list($email, $gebruikersnaam) = mysqli_fetch_row($resultaat);

        echo "<form action='account.php' method='post'>
                <input type='
        <input type='submit' name='bewerk' value='Bewerk'>";
        echo $gebruikersnaam;
        
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
                <input type='submit' name='login' value='Login'>
	        </form>";
    }
    ?>
</body>
</html>
