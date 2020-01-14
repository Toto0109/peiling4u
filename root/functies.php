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

function get_vraag($peilingnr, $vraagnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT vraag
                                      FROM vragen
                                      WHERE peilingnr = '$peilingnr' AND vraagnr = '$vraagnr'") 
        or die("De query 1 op de database is mislukt!");
     
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($vraag) = mysqli_fetch_row($resultaat);
    return $vraag;
}

function get_m_antwoorden($peilingnr, $vraagnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT meerdere_antwoorden
                                      FROM vragen
                                      WHERE peilingnr = '$peilingnr' AND vraagnr = '$vraagnr'") 
        or die("De query 1 op de database is mislukt!");
     
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($m_antwoorden) = mysqli_fetch_row($resultaat);
    return $m_antwoorden;

}
function get_antwoord($peilingnr, $vraagnr, $antwoordnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT antwoord
                                      FROM antwoorden
                                      WHERE peilingnr = '$peilingnr' AND vraagnr = '$vraagnr' AND antwoordnr = '$antwoordnr'") 
        or die("De query 1 op de database is mislukt!");
     
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($antwoord) = mysqli_fetch_row($resultaat);
    return $antwoord;

}

function get_peilingtitel($peilingnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT titel
                                      FROM peilingen
                                      WHERE peilingnr = '$peilingnr'")
        or die("De query 1 op de database is mislukt!");
     
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($peilingtitel) = mysqli_fetch_row($resultaat);
    return $peilingtitel;

   
}

function get_openbaar($peilingnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $resultaat = mysqli_query($mysql,"SELECT openbaar
                                      FROM peilingen
                                      WHERE peilingnr = '$peilingnr'")
        or die("De query 1 op de database is mislukt!");
     
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($openbaar) = mysqli_fetch_row($resultaat);
    return $openbaar;

}
function save_peiling()
{
    global $server, $user, $pass, $db;
    $peilingnr = $_GET["nr"];
    
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $titel = mysqli_real_escape_string($mysql, $_POST["titel"]);
    if(isset($_POST["openbaar"]))
    {
        $openbaar = 1;
    }
    else 
    {
        $openbaar = 0;
    }

    mysqli_query($mysql,"UPDATE peilingen
                         SET titel = '$titel', openbaar = '$openbaar'
                         WHERE peilingnr = '$peilingnr'") 
        or die("De insertquery op de database is mislukt!"); 

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
        if(isset($_POST["piechart$i"])) {
            $piechart = 1;
        }
        else {
            $piechart = 0;
        }

        mysqli_query($mysql,"UPDATE vragen
                             SET vraag='$vraag', meerdere_antwoorden='$m_antwoorden', cirkeldiagram='$piechart'
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
}

function max_vraagnr($peilingnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $resultaat = mysqli_query($mysql,"SELECT MAX(vraagnr)
                                      FROM vragen
                                      WHERE peilingnr = '$peilingnr'")
        or die("De selectquery op de database is mislukt!"); 

    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
    
    list($max_vraagnr) = mysqli_fetch_row($resultaat);
    return $max_vraagnr;
}

function max_antwoordnr($peilingnr, $vraagnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $resultaat = mysqli_query($mysql,"SELECT MAX(antwoordnr)
                                      FROM antwoorden
                                      WHERE peilingnr = '$peilingnr' AND vraagnr = '$vraagnr'")
        or die("De selectquery op de database is mislukt!"); 

    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
    
    list($max_antwoordnr) = mysqli_fetch_row($resultaat);
    return $max_antwoordnr;
}

function is_piechart($peilingnr, $vraagnr)
{
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $resultaat = mysqli_query($mysql,"
        SELECT cirkeldiagram
        FROM vragen
        WHERE peilingnr = '$peilingnr' AND vraagnr = '$vraagnr'")
        or die("De selectquery op de database is mislukt!"); 

    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($is_piechart) = mysqli_fetch_row($resultaat);

    return $is_piechart;
}

function resultaat_vraag($peilingnr, $vraagnr)
{
    $aantal_array = array();
    $pie_chart = is_piechart($peilingnr, $vraagnr);
    global $server, $user, $pass, $db;
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $resultaat = mysqli_query($mysql,"
        SELECT antwoord, COUNT(antwoord)
        FROM resultaten 
        WHERE peilingnr = '$peilingnr' AND vraagnr = '$vraagnr'
        GROUP BY antwoord")
        or die("De selectquery op de database is mislukt!"); 

    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
    
    if($pie_chart) {
        echo "<div class='ct-chart' id='chart$vraagnr'></div>
        <script>
            var data = {
                labels: [],
                series: []
            };
            var options = {
                width: 200,
                height: 200
            };";
    }
    else {
        echo "<div class='ct-chart' id='chart$vraagnr'></div>
        <script>
            var data = {
                labels: [],
                series: [[]]
            };
            var options = {
                axisY: {onlyInteger: true},
                width: 200,
                height: 200
            };";
    }
    
    while(list($antwoord, $aantal) = mysqli_fetch_row($resultaat)) {
        echo "data.labels.push(".$antwoord.");";
        if($pie_chart)
            echo "data.series.push(".$aantal.");";
        else 
            echo "data.series[0].push(".$aantal.");";
        $aantal_array[$antwoord] = $aantal;
    }
    
    if($pie_chart)
        echo "new Chartist.Pie('#chart$vraagnr', data, options);</script>";
    else
        echo "new Chartist.Bar('#chart$vraagnr', data, options);</script>";

    echo "
        <table>
            <tr>
                <th>Nr</th>
                <th>Antwoord</th>
                <th>Aantal</th>
            </tr>";
    for ($i = 1; $i <= max_antwoordnr($peilingnr, $vraagnr); $i++) {
        echo "<tr>";
        echo "<td>".$i."</td>";
        echo "<td>".get_antwoord($peilingnr, $vraagnr, $i)."</td>";
        if($aantal_array[$i] != NULL) 
            echo "<td>".$aantal_array[$i]."</td>";
        else 
            echo "<td>0</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<br>";
}
?>
