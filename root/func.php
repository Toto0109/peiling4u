<?php
include "connect.php";
session_start();

function get_gebruikersnaam(int $gebruikersnr)
{
    // maakt de variabelen global zodat ze in de scope van de functie zijn te gebruiken
    global $server,$user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT gebruikersnaam
                                      FROM gebruikers 
                                      WHERE gebruikersnr = '$gebruikersnr'") 
        or die("De query 1 op de database is mislukt!");
     
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($gebruikersnaam) = mysqli_fetch_row($resultaat);
    return $gebruikersnaam;
}
?>
