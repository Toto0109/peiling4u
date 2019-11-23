<?php
include "connect.php";
//include "test.php";
session_start();

function get_usrname(int $gebruikersnr)
{
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    echo $gebruikersnr;
    $gebruiker = mysqli_real_escape_string($mysql, $gebruikersnr);
    echo $gebruiker;
    
    $resultaat = mysqli_query($mysql,"SELECT gebruikersnaam
                                      FROM gebruikers 
                                      WHERE gebruikersnr = '$gebruikersnr'") 
        or die("De query 1 op de database is mislukt!");
     
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    //list($gebruikersnaam) = mysqli_fetch_row($resultaat);
    return $gebruikersnaam;
}

$asdf = $_SESSION["gebruiker"];
echo get_usrname($asdf);
//hey();
/*
$mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
$asdf = $_SESSION["gebruiker"];    
$resultaat = mysqli_query($mysql,"SELECT gebruikersnaam
                                  FROM gebruikers 
                                  WHERE gebruikersnr = '$asdf'")
    or die("De query 1 op de database is mislukt!");
    
mysqli_close($mysql) 
    or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

list($gebruikersnaam) = mysqli_fetch_row($resultaat);

echo $gebruikersnaam;*/
?>
