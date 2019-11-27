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

function get_antwoord($vraagnr, $antwoordnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT antwoord
                                      FROM antwoorden
                                      WHERE vraagnr = '$vraagnr' AND antwoordnr = '$antwoordnr'") 
        or die("De query 1 op de database is mislukt!");
     
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($antwoord) = mysqli_fetch_row($resultaat);
    return $antwoord;

}

function bewerk_peiling($peilingnr)
{
    echo "<form action='creator.php?nr=$peilingnr' method='post'>";
    for ($i = 1; $i <= count_vragen($peilingnr); $i++)
    {
        echo "Vraag ".$i.": ";
        $vraag = get_vraag($i);
        echo "<input type='text' name='vraag$i' value='$vraag'>&nbsp;";
        echo "Meerdere antwoorden mogelijk: <input type='checkbox' name='m_antwoorden$i' value='1'> <br>";
        for ($j = 1; $j <= count_antwoorden($peilingnr, $i); $j++)
        {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            echo "$j: ";
            $antwoord = get_antwoord($i, $j);
            echo "<input type='text' name='antwoord$i$j' value='$antwoord'><br>";
        }
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type='submit' name='add_antwoord$i' value='+'><br>";
    }
    echo "<input type='submit' name='add_vraag' value='+'> <br>";
    echo "<input type='submit' name='save' value='Sla de peiling op'> <br>";
    echo "</form>";
}

function save_peiling()
{
    global $server, $user, $pass, $db;
    $peilingnr = $_GET["nr"];
    
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    for ($i = 1; $i <= count_vragen($_GET["nr"]); $i++)
    {
        $vraag = mysqli_real_escape_string($mysql, $_POST["vraag$i"]);
        if(isset($_POST["m_antwoorden$i"]))
        {
            $m_antwoorden = 1;
        }
        else
        {
            $m_antwoorden = 0;
        }
                
        mysqli_query($mysql,"UPDATE vragen
                             SET vraag='$vraag', meerdere_antwoorden='$m_antwoorden'
                             WHERE peilingnr = '$peilingnr' AND vraagnr = '$i'") 
            or die("De insertquery op de database is mislukt!"); 

        for($j = 1; $j <= count_antwoorden($_GET["nr"], $i); $j++) 
        {
            $antwoord = mysqli_real_escape_string($mysql, $_POST["antwoord$i$j"]);

            mysqli_query($mysql, "UPDATE antwoorden
                                  SET antwoord = '$antwoord'
                                  WHERE peilingnr = '$peilingnr' AND vraagnr = '$i' AND antwoordnr = '$j'")
            or die("De insertquery op de database is mislukt!");
        }
    }

    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
    
    header("Refresh:0");

}
?>
