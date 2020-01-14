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
    <title>Resultaten</title>
    <link rel="stylesheet" href="styles/chartist.css">
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
</head>
<body>
    <a href="index.php">Home</a> <br>
    <?php
    if($_SESSION["logged_in"] == true)
    {
        echo "Ingelogd als: ";
        echo get_gebruikersnaam($_SESSION["gebruiker"])."<br>";
        echo "<form action='login.php' method='post'>
                  <input type='submit' name='loguit' value='Loguit'>
              </form>";
    }
    else 
    {
        echo "U bent niet ingelogd";
    }
    if(isset($_GET["nr"]))
    {
        $gebruikersnr = $_SESSION["gebruiker"];
        $peilingnr = $_GET["nr"];

        if (get_openbaar($_GET["nr"]) == 1)
        {
            for($i = 1; $i <= max_vraagnr($peilingnr); $i++) {
                echo "Vraag $i: ";
                echo get_vraag($peilingnr, $i)."<br>";
                $aantal_array = array();
                $pie_chart = is_piechart($peilingnr, $i);
                $mysql = mysqli_connect($server,$user,$pass,$db) 
                    or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
            
                $resultaat = mysqli_query($mysql,"
                    SELECT antwoord, COUNT(antwoord)
                    FROM resultaten 
                    WHERE peilingnr = '$peilingnr' AND vraagnr = '$i'
                    GROUP BY antwoord")
                    or die("De selectquery op de database is mislukt!"); 
            
                mysqli_close($mysql) 
                    or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
                
                if($pie_chart) {
                    echo "<div class='ct-chart' id='chart$i'></div>
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
                    echo "<div class='ct-chart' id='chart$i'></div>
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
                    echo "new Chartist.Pie('#chart$i', data, options);</script>";
                else
                    echo "new Chartist.Bar('#chart$i', data, options);</script>";
            
                echo "
                    <table>
                        <tr>
                            <th>Nr</th>
                            <th>Antwoord</th>
                            <th>Aantal</th>
                        </tr>";
                for ($j = 1; $j <= max_antwoordnr($peilingnr, $i); $j++) {
                    echo "<tr>";
                    echo "<td>".$j."</td>";
                    echo "<td>".get_antwoord($peilingnr, $i, $j)."</td>";
                    if($aantal_array[$j] != NULL) 
                        echo "<td>".$aantal_array[$j]."</td>";
                    else 
                        echo "<td>0</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<br>";
            }

        }
        else
        {
            echo "Deze peiling bestaat niet of is niet openbaar";
        }
    }
    else if($_SESSION["logged_in"] == true) 
    {
        $gebruikersnr = $_SESSION["gebruiker"];
        
        $mysql = mysqli_connect($server,$user,$pass,$db) 
            or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

        $resultaat = mysqli_query($mysql,"SELECT peilingnr, titel
                                          FROM peilingen
                                          WHERE openbaar = '1'") 
            or die("De query 1 op de database is mislukt!");
        
        mysqli_close($mysql) 
            or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
        
        echo "Openbare peilngen: <br>";
        while(list($peilingnr, $peilingtitel) = mysqli_fetch_row($resultaat))  
        {
            echo"<a href='resultaten.php?nr=$peilingnr'>$peilingtitel<a/><br />"; 
        }
    }
    ?>
</body>
</html>
