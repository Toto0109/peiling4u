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
    <title>Login</title>
</head>
<body>
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
        echo "U bent niet ingelogd<br>";
    }
    ?>
    <a href="aanmelden.php">Aanmelden</a><br>
    <a href="login.php">Login</a><br>
    <a href="peilingen.php">Peilingen</a><br>
    <a href="creator.php">Peilingcreator</a><br>
</body>
</html>
