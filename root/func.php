<?php
include "connect.php";
session_start();

function hey()
{
    echo "hey";
}

function get_usrname($gebruikersnr)
{
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
echo $gebruikersnr;
$gebruiker = mysqli_real_escape_string($mysql, $gebruikersnr);
echo $gebruiker;
    
    $resultaat = mysqli_query($mysql,"SELECT gebruikersnaam
                                      FROM gebruikers 
                                      WHERE gebruikersnr = 8")//"'$gebruiker'") 
        or die("De query 1 op de database is mislukt!");
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($gebruikersnaam) = mysqli_fetch_row($resultaat);
    
    return $gebruikersnaam;
}
?>
