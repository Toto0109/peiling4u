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
    <title>Peilingen</title>
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

        if (get_openbaar($_GET["nr"]) == 1)
        {
            $peilingnr = $_GET["nr"];
            echo get_peilingtitel($peilingnr);
            echo "<form action='peilingen.php?nr=$peilingnr' method='post'>";
            for ($i = 1; $i <= count_vragen($peilingnr); $i++)
            {
               echo "Vraag $i: ".get_vraag($peilingnr, $i)."<br>";
                
               for ($j = 1; $j <= count_antwoorden($peilingnr, $i, $j); $j++)
               {
                   if(get_m_antwoorden($peilingnr, $i) == 1)
                    {
                        echo "<input type='checkbox' name='vraag$i"."[]' value='$j'>";
                    }
                    else
                    {
                        echo "<input type='radio' name='vraag$i' value='$j'>";
                    }
                    echo get_antwoord($peilingnr, $i, $j)."<br>";
               }
            } 
            $mysql = mysqli_connect($server,$user,$pass,$db) 
                or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");

            $resultaat = mysqli_query($mysql,"SELECT DISTINCT gebruikersnr
                                              FROM resultaten
                                              WHERE peilingnr = '$peilingnr'") 
                or die("De query 1 op de database is mislukt!");
        
            mysqli_close($mysql) 
                or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

            while(list($gebruikers) = mysqli_fetch_row($resultaat))
            {
                if($gebruikersnr == $gebruikers)
                {
                    $ingevuld = true;
                }
            }

            if ($ingevuld)
            {
                echo "je hebt de peiling al ingevuld";
            }
            else 
            {
                echo "<input type='submit' name='verzend' value='Verzend'>";
            }
        echo "</form>";
        }
        else
        {
            echo "Deze peiling is niet openbaar";
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
            echo"<a href='peilingen.php?nr=$peilingnr'>$peilingtitel<a/><br />"; 
        }
    }
    ?>
</body>
</html>

<?php
if(isset($_POST["verzend"]))
{
    $peilingnr = $_GET["nr"];
    $gebruikersnr = $_SESSION["gebruiker"];

    $mysql = mysqli_connect($server,$user,$pass,$db) 
        or die("Fout: Er is geen verbinding met de MySQL-server tot stand gebracht!");
    
    for ($i = 1; $i <= count_vragen($peilingnr); $i++)
    {
        if(get_m_antwoorden($peilingnr, $i) == 1)
        {
            $antwoord = $_POST["vraag$i"];
            $N = count($antwoord);
            
            for($j = 0; $j < $N; $j++)
            {
                mysqli_query($mysql, "INSERT INTO resultaten(peilingnr, vraagnr, gebruikersnr, antwoord)
                                      VALUES ($peilingnr, $i, $gebruikersnr, $antwoord[$j])")
                    or die("De insertquery op de database is mislukt!");
            }
        }
        else
        {
            $antwoord = $_POST["vraag$i"];
            mysqli_query($mysql, "INSERT INTO resultaten(peilingnr, vraagnr, gebruikersnr, antwoord)
                                  VALUES ($peilingnr, $i, $gebruikersnr, $antwoord)")
                or die("De insertquery op de database is mislukt!");
        }
    }

    mysqli_close($mysql)
        or die("Het verbreken van de verbinding met de MySQL-server is mislukt!");

    header("Refresh:0");
}
?>
