<?php
include "connect.php";
session_start();

function count_vragen($peilingnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT COUNT(*)
                                      FROM vragen
                                      WHERE peilingnr = '$peilingnr'") 
        or die("De query 1 op de database is mislukt!");
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($count) = mysqli_fetch_row($resultaat);
    return $count;
}

function count_antwoorden($peilingnr, $vraagnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT COUNT(*)
                                      FROM antwoorden
                                      WHERE peilingnr = '$peilingnr' AND vraagnr = '$vraagnr'") 
        or die("De query 1 op de database is mislukt!");
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($count) = mysqli_fetch_row($resultaat);
    return $count;
}   

function get_gebruikersnaam($gebruikersnr)
{
    // maakt de variabelen global zodat ze in de scope van de functie zijn te gebruiken
    global $server, $user, $pass, $db;
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

function list_peilingen($gebruikersnr)
{
    global $server, $user, $pass, $db;
    
    $mysql = mysqli_connect($server,$user,$pass,$db) 
            or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $resultaat = mysqli_query($mysql,"SELECT peilingnr, titel 
                                      FROM peilingen
                                      WHERE gebruikersnr = '$gebruikersnr'") 
        or die("De query 1 op de database is mislukt!");
        
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
        
    echo "Uw peilngen: <br>";
    while(list($peilingnr, $peilingtitel) = mysqli_fetch_row($resultaat))  
    {
        echo"<a href='creator.php?nr=$peilingnr'>$peilingtitel<a/><br />"; 
    }

}

function get_gebruikersnr($peilingnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT gebruikersnr
                                      FROM peilingen
                                      WHERE peilingnr = '$peilingnr'") 
        or die("De query 1 op de database is mislukt!");
     
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($gebruikersnr) = mysqli_fetch_row($resultaat);
    return $gebruikersnr;
}

function get_vraag($vraagnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT vraag
                                      FROM vragen
                                      WHERE vraagnr = '$vraagnr'") 
        or die("De query 1 op de database is mislukt!");
     
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($vraag) = mysqli_fetch_row($resultaat);
    return $vraag;
}

function bewerk_peiling($peilingnr)
{
    echo "<form action='creator.php?nr=$peilingnr' method='post'>";
    for ($i = 1; $i <= count_vragen($peilingnr); $i++)
    {
        echo "Vraag ".$i.": ";
        $vraag = get_vraag($i);
        echo "<input type='text' name='vraag$i' value='$vraag'>&nbsp;";
        echo "Openbaar: <input type='checkbox' name='openbaar$i' value='1'> <br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type='submit' name='add_antwoord' value='+'><br>";
    }
    echo "<input type='submit' name='add_vraag' value='+'> <br>";
    echo "<input type='submit' name='save' value='Sla de peiling op'> <br>";
    echo "</form>";
}
?>
