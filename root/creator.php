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
    <title>Creator</title>
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
    if(isset($_GET["nr"]))
    {
        
        if ($_SESSION["gebruiker"] == get_gebruikersnr($_GET["nr"]))
        {
            $peilingnr = $_GET["nr"];
            $titel = get_peilingtitel($peilingnr);
            $openbaar = get_openbaar($peilingnr);

            echo "<form action='creator.php?nr=$peilingnr' method='post'>";
            echo "<input type='text' name='titel' value='$titel'>";
            echo "<input type='checkbox' name='openbaar' value='1'";
            if($openbaar == true)
            {
                echo " checked>";
            }
            else
            {
                echo ">";
            }
            echo "Openbaar<br>";
            for ($i = 1; $i <= count_vragen($peilingnr); $i++)
            {
                $vraag = get_vraag($peilingnr, $i);
                $m_antwoorden = get_m_antwoorden($peilingnr, $i);
                echo "Vraag ".$i.": ";
                echo "<input type='text' name='vraag$i' value='$vraag'>&nbsp;";
                echo "Meerdere antwoorden mogelijk: <input type='checkbox' name='m_antwoorden$i' value='1'";
                if($m_antwoorden == true)
                {
                    echo " checked>";
                }
                else
                {
                    echo ">";
                }
                echo "<input type='submit' name='del_vraag[$i]' value='x'><br>";

                for ($j = 1; $j <= count_antwoorden($peilingnr, $i); $j++)
                {
                    echo "Antwoord $j: ";
                    $antwoord = get_antwoord($peilingnr, $i, $j);
                    echo "<input type='text' name='antwoord$i$j' value='$antwoord'>
                          <input type='submit' name='del_antwoord[$i][$j]' value='x'><br>";
                }
                echo "<input type='submit' name='add_antwoord[$i]' value='+'><br>";
            }
            echo "<input type='submit' name='add_vraag' value='+'> <br>";
            echo "<input type='submit' name='save' value='Sla de peiling op'> <br>";
            echo "</form>";
        }
        else
        {
            echo "U heeft geen bewerkingsrechten voor deze peilingen";
        }
    }
    else if($_SESSION["logged_in"] == true)
    {
        $gebruikersnr = $_SESSION["gebruiker"];
    
        $mysql = mysqli_connect($server,$user,$pass,$db) 
            or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

        $resultaat = mysqli_query($mysql,"SELECT peilingnr, titel 
                                          FROM peilingen
                                          WHERE gebruikersnr = '$gebruikersnr'") 
            or die("De query 1 op de database is mislukt!");
        
        mysqli_close($mysql) 
            or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");
        
        echo "Uw peilngen: <br>";
        echo "<form action='creator.php' method='post'>";
        while(list($peilingnr, $peilingtitel) = mysqli_fetch_row($resultaat))  
        {
            echo "<input type='submit' name='del_peiling[$peilingnr]' value='x'>";
            echo"<a href='creator.php?nr=$peilingnr'>$peilingtitel<a/><br />"; 
        }
        echo "</form>";
        echo "Nieuwe peiling:<br>";
        echo "<form action='creator.php' method='post'>
               Peilingnaam: <input type='text' name='titel'><br>
               <input type='submit' name='maakpeiling' value='Maak een nieuwe peiling'>
            </form>
            <br>";
    }
    else 
    {
        echo "<a href='login.php'>Je moet eerst inloggen voordat je een peiling kan maken</a>";
    }
    ?>
</body>
</html>

<?php
if(isset($_POST["maakpeiling"]))
{
    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    $gebruikersnr = $_SESSION["gebruiker"];
    $titel = mysqli_real_escape_string($mysql, $_POST["titel"]);

    mysqli_query($mysql,"INSERT INTO peilingen(gebruikersnr,titel,openbaar) 
                         VALUES('$gebruikersnr','$titel',0)") 
        or die("De insertquery op de database is mislukt!"); 

    $resultaat = mysqli_query($mysql, "SELECT MAX(peilingnr)
                                      FROM peilingen")
        or die("De selectquery op de database is mislukt!"); 

    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    list($max_peilingnr) = mysqli_fetch_row($resultaat);
    header("Location: creator.php?nr=$max_peilingnr");
}

if(isset($_POST["save"]))
{
    save_peiling();
    header("Refresh:0");
}

if(isset($_POST["add_vraag"]))
{
    $peilingnr = $_GET["nr"];
    save_peiling();

    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $max_vraagnr = max_vraagnr($peilingnr);
    $nieuwe_vraagnr = $max_vraagnr + 1;
    
    mysqli_query($mysql,"INSERT INTO vragen(peilingnr, vraagnr)
                         VALUES('$peilingnr','$nieuwe_vraagnr')") 
        or die("De insertquery op de database is mislukt!"); 
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    header("Refresh:0");

}

if(isset($_POST["add_antwoord"]))
{
    $peilingnr = $_GET["nr"];
    $vraagnr= array_pop(array_keys($_REQUEST['add_antwoord']));
    save_peiling();

    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    $max_antwoordnr = max_antwoordnr($peilingnr, $vraagnr);
    $nieuwe_antwoordnr = $max_antwoordnr + 1;
    
    mysqli_query($mysql,"INSERT INTO antwoorden(peilingnr, vraagnr, antwoordnr)
                         VALUES('$peilingnr','$vraagnr','$nieuwe_antwoordnr')") 
        or die("De insertquery op de database is mislukt!"); 
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    header("Refresh:0");

}

if(isset($_POST["del_vraag"]))
{
    $peilingnr = $_GET["nr"];
    $vraagnr = array_pop(array_keys($_REQUEST["del_vraag"]));
    save_peiling();

    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    // Delete de vraag
    mysqli_query($mysql,"DELETE FROM vragen
                         WHERE peilingnr = '$peilingnr' AND vraagnr = '$vraagnr'")
        or die("De deletequery op de database is mislukt!"); 
    
    // Alle vraagnrs boven de verwijderde vraag -1
    for($i = $vraagnr + 1; $i <= max_vraagnr($peilingnr); $i++)
    {
        mysqli_query($mysql, "UPDATE vragen
                              SET vraagnr = vraagnr - 1
                              WHERE peilingnr = '$peilingnr' AND vraagnr = '$i'")
            or die("De updatequery op de databse is mislukt!");
    }
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    header("Refresh:0");
}

if(isset($_POST["del_antwoord"]))
{
    $peilingnr = $_GET["nr"];
    $vraagnr = array_pop(array_keys($_REQUEST["del_antwoord"]));
    $antwoordnr = array_pop(array_keys($_REQUEST["del_antwoord"][$vraagnr]));
    save_peiling();

    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    // Delete het antwoord
    mysqli_query($mysql,"DELETE FROM antwoorden
                         WHERE peilingnr = '$peilingnr' AND vraagnr = '$vraagnr' AND antwoordnr = '$antwoordnr'")
        or die("De deletequery op de database is mislukt!"); 
    
    // Alle antwoordnrs boven het verwijderde antwoord -1
    for($i = $antwoordnr + 1; $i <= max_antwoordnr($peilingnr, $vraagnr); $i++)
    {
        mysqli_query($mysql, "UPDATE antwoorden
                              SET antwoordnr = antwoordnr - 1
                              WHERE peilingnr = '$peilingnr' AND vraagnr = '$vraagnr' AND antwoordnr = '$i'")
            or die("De updatequery op de databse is mislukt!");
    }
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    header("Refresh:0");


}

if(isset($_POST["del_peiling"]))
{
    $peilingnr = array_pop(array_keys($_REQUEST["del_peiling"]));

    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

    // Delete de peiling 
    mysqli_query($mysql,"DELETE FROM peilingen
                         WHERE peilingnr = '$peilingnr'")
        or die("De deletequery op de database is mislukt!"); 
    
    mysqli_close($mysql) 
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    header("Refresh:0");

}
?>
