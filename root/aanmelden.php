<?php
include "connect.php";

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
    <title>Aanmelden</title>
</head>
<body>
    <a href="index.php">Home</a> <br>
    <?php
    if($_SESSION["logged_in"] == true)
    {
        echo "U bent al ingelogd met een account";
    }
    else 
    {
        echo "<form action='aanmelden.php' method='post'>
                E-mail adres: <br>
                <input type='text' name='email'/> <br>
                Gebruikersnaam: <br>
                <input type='text' name='gebruikersnaam'/> <br>
                Wachtwoord: <br>
                <input type='password' name='wachtwoord'/> <br>
                <input type='submit' name='aanmelden' value='Aanmelden'> <br>
                <a href='login.php'>Heb je al een acount? Log in via deze link</a>
	        </form>";
    }
    ?>
</body>
</html>

<?php
if(isset($_POST["aanmelden"]))
{
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $email = mysqli_real_escape_string($mysql, $_POST["email"]);
    $gebruikersnaam = mysqli_real_escape_string($mysql, $_POST["gebruikersnaam"]);
    $hash = password_hash($_POST["wachtwoord"], PASSWORD_DEFAULT); // Functie die het wachtwoord versleuteld

    // Insert query voor gebruikersgegevens
    mysqli_query($mysql,"INSERT INTO gebruikers(gebruikersnaam,wachtwoord,email) 
                         VALUES('$gebruikersnaam','$hash','$email')") 
        or die("De insertquery op de database is mislukt!"); 
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
    
    echo "U bent nu aangemeldt, <a href='login.php'>log in via deze link</a>";
}
?>
