<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Kantorek</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="icon.ico" type="image/x-icon">
</head>

<body>
    <?php
    if (isset($_POST['czek'])) {
        echo '<div id="wrapper">';
    } else {
        echo '<div id="wrapper_calc">';
    }


    ?>
        <div id="banner">
            <img src="logo.png" alt="logo" style='width:100px; padding:10px'>
            <h1 style="flex-grow: 3;"><a href="./index.php">Witamy na stronie kantorka!</a></h1>
            <ul style="flex-grow: 2;">
                <li>
                    <a class='link' href="./index.php">Historia kursów
                    <img class='svg' id='calendar' src="calendar.svg">
                    </a>
                </li>

            </ul>
        </div>
        <?php
        if (!isset($_POST['czek'])) {
            echo "<div id='window' style='height: 100%'>";
        } else {
            echo "<div id='window'>";
        }
            $waluty = array(
                    "THB" => 1,
                    "USD" => 1,
                    "AUD" => 1,
                    "HKD" => 1,
                    "CAD" => 1,
                    "NZD" => 1,
                    "SGD" => 1,
                    "EUR" => 1,
                    "HUF" => 100,
                    "CHF" => 1,
                    "GBP" => 1,
                    "UAH" => 1,
                    "JPY" => 100,
                    "CZK" => 1,
                    "DKK" => 1,
                    "ISK" => 100,
                    "NOK" => 1,
                    "SEK" => 1,
                    "HRK" => 1,
                    "RON" => 1,
                    "BGN" => 1,
                    "TRY" => 1,
                    "ILS" => 1,
                    "CLP" => 100,
                    "PHP" => 1,
                    "MXN" => 1,
                    "ZAR" => 1,
                    "BRL" => 1,
                    "MYR" => 1,
                    "RUB" => 1,
                    "IDR" => 10000,
                    "INR" => 100,
                    "KRW" => 100,
                    "CNY" => 1,
                    "XDR" => 1
                );

            $kody = array(
                "THB" => 'bat tajlandzki',
                "USD" => 'dolar amerykański',
                "AUD" => 'dolar australijski',
                "HKD" => 'dolar Hongkongu',
                "CAD" => 'dolar kanadyjski',
                "NZD" => 'dolar nowozelandzki',
                "SGD" => 'dolar singapurski',
                "EUR" => 'euro',
                "HUF" => 'forint węgierski',
                "CHF" => 'frank szwajcarski',
                "GBP" => 'funt brytyjski',
                "UAH" => 'hrywna ukraińska',
                "JPY" => 'jen',
                "CZK" => 'korona czeska',
                "DKK" => 'korona duńska',
                "ISK" => 'korona islandzka',
                "NOK" => 'korona norweska',
                "SEK" => 'korona szwedzka',
                "HRK" => 'kuna chorwacka',
                "RON" => 'lej rumuński',
                "BGN" => 'lew bułgarski',
                "TRY" => 'lira turecka',
                "ILS" => 'nowy izraelski szekel',
                "CLP" => 'peso chilijskie',
                "PHP" => 'peso filipińskie',
                "MXN" => 'peso meksykańskie',
                "ZAR" => 'rand (RPA)',
                "BRL" => 'real brazylijski',
                "MYR" => 'ringgit malezyjski',
                "RUB" => 'rubel rosyjski',
                "IDR" => 'rupia indonezyjska',
                "INR" => 'rupia indyjska',
                "KRW" => 'won południowokoreański',
                "CNY" => 'yuan chiński',
                "XDR" => 'SDR'
            );
                error_reporting(0);
                ini_set('display_errors', 0);
                if (!isset($_POST['currSelect'])) {
                    $currSelect = 'USD';
                } else {
                    $currSelect = $_POST['currSelect'];
                }
                $polaczenie = mysqli_connect('localhost','root','','kursy');
                if (mysqli_connect_errno()) {
                    echo "<p>Nie znaleziono bazy danych! Upewnij się, że uruchomiony został plik <i><a href='kantorek.py'>kantorek.py</a></i> lub została zaimportowana baza danych <i><a href='kursy.sql'>kursy.sql</a></i>.</p>";
                    return;
                }
                if ($currSelect == 'RUB') {
                    $NrTabeli = "SELECT DISTINCT Data FROM kursy WHERE Data <= '2022-03-08' ORDER BY Data DESC;";
                } else {
                    $NrTabeli = "SELECT DISTINCT Data FROM kursy ORDER BY Data DESC;";
                }
                $QNrTabeli = mysqli_query($polaczenie, $NrTabeli);
                echo "<form id='form' method='POST'><div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-bottom: 0.5rem'>";
                echo "<label for='currSelect'>Wybierz walutę: </label>";
                echo "<select id='currSelect' name='currSelect' style='font-size: 16px' onchange='this.form.submit()'>";
                foreach ($waluty as $currency => $prefix) {
                    if (isset($_POST['selectDate']) and $_POST['selectDate'] > '2022-03-08' and $currency == 'RUB') {
                        continue;
                    }
                    if ($currSelect == $currency) {
                        echo "<option value='".$currency."' selected>".$currency."</option>";
                    } else {
                        echo "<option value='".$currency."'>".$currency."</option>";
                    }
                }
                echo "</select></div>";
                echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-bottom: 0.5rem'><label for='czek'>Pokazać wykres waluty?</label>";
                if (!isset($_POST['czek'])) {
                    echo "<input type='checkbox' name='czek' id='czek' onchange='this.form.submit()'></div>";
                } else {
                    echo "<input type='checkbox' name='czek' id='czek' onchange='this.form.submit()' checked></div>";
                }
                if (!isset($_POST['czek'])) {
                    echo "<form method='POST'><div style='font-size: 20px; color: rgb(29,45,79); text-align: center'>Wybierz dzień: <select name='selectDate' onchange='this.form.submit()' style='font-size: 16px;' id='selectTable'></form>";
                    while ($row = mysqli_fetch_row($QNrTabeli)) {
                        if (!isset($doSelecta)) {
                        $doSelecta = $row[0];
                        }
                        if ($row[0] == $_POST['selectDate']) {
                            echo "<option value=".$row[0]." selected>".$row[0]."</option>";
                        } else {
                        echo "<option value=".$row[0].">".$row[0]."</option>";
                        }
                    }
                    echo "</select><br>";
                }
                if (mysqli_connect_errno()) {
                    echo "<p>Nie znaleziono bazy danych! Upewnij się, że uruchomiony został plik <i><a href='kantorek.py'>kantorek.py</a></i>.</p>";
                } else {
                    if (isset($_POST['czek']) AND $_POST['czek'] == 'on') {
                        $selectAll = "SELECT * FROM kursy WHERE KodWaluty = '".$currSelect."' ORDER BY Data DESC LIMIT 25;";
                    } elseif (!isset($_POST['selectDate'])) {
                        $selectAll = "SELECT * FROM kursy WHERE Data = '".$doSelecta."' AND KodWaluty = '".$currSelect."' ORDER BY Data DESC LIMIT 25;";
                    } else {
                        $selectAll = "SELECT * FROM kursy WHERE Data = '".$_POST['selectDate']."' AND KodWaluty = '".$currSelect."' ORDER BY Data DESC LIMIT 25;";
                    }
                    echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center'>";
                    if (isset($_POST['czek']) AND $_POST['czek'] == 'on') {
                        $selectAllTitle = "SELECT * FROM kursy WHERE KodWaluty = '".$currSelect."' ORDER BY Data ASC;";
                        $QselectAll = mysqli_query($polaczenie, $selectAllTitle);
                        $rowTemp = mysqli_fetch_row($QselectAll);
                        if ($currSelect == 'RUB') {
                            echo "<h2>Najnowsze kursy ".$waluty[$currSelect]." ".$currSelect."</h2>";   
                        } else {
                            echo "<h2>Kursy ".$waluty[$currSelect]." ".$currSelect." z ostatniego miesiąca</h2>";
                        }
                    } else {
                        if (!isset($_POST['selectDate'])) {
                            $selectAllTitle = "SELECT Data FROM kursy WHERE KodWaluty = '".$currSelect."' ORDER BY Data DESC;";
                            $QselectAll = mysqli_query($polaczenie, $selectAllTitle);
                            $rowTemp = mysqli_fetch_row($QselectAll);
                            echo "<h2>Aktualny kurs ".$currSelect." z dnia: ".$rowTemp[0]."</h2>";    
                        } else {
                            echo "<h2>Aktualny kurs ".$currSelect." z dnia: ".$_POST['selectDate']."</h2>";
                        }
                    }
                    echo "<table>";
                    echo "<tr>";
                    echo "<th><span class='teha'>Numer tabeli</span></th>";
                    echo "<th><span class='teha'>Data</span></th>";
                    echo "<th><span class='teha'>Kurs średni</span></th>";
                    echo "</tr>";
                    $kursy = [];
                    $QselectAll = mysqli_query($polaczenie, $selectAll);
                    while ($row2 = mysqli_fetch_row($QselectAll)) {
                        echo "<tr>";
                        echo "<td style='text-align: center; background-color: rgb(232,232,232)'>".$row2[0]."</td>";
                        echo "<td style='text-align: center; background-color: rgb(232,232,232)'>".$row2[1]."</td>";
                        echo "<td style='text-align: right; background-color: rgb(232,232,232)'>".number_format($row2[3]*$waluty[$row2[2]], 4)."</td>";
                        array_push($kursy, number_format($row2[3]*$waluty[$row2[2]], 4));
                        echo "</tr>";
                    }
                    echo "</table></div>";
                    if (!isset($_POST['czek'])) {
                    $QselectAll = mysqli_query($polaczenie, $selectAll);
                    $rowTemp = mysqli_fetch_row($QselectAll);
                    $kurs = $rowTemp[3];
                    if (!isset($_POST['sourceCurr1']) or !isset($_POST['sourceCurr2'])) {
                        echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-top: 1rem; display: flex; flex-direction: row; justify-content: center; align-items: stretch;'><input name='sourceCurr1' id='sourceCurr1' type='number' min = 0 step= 0.01 value=1 oninput='calculateTo(this.value)'><b>&nbspPLN →&nbsp</b><input type='number' step=0.0001 disabled id='targetCurr1' value = ".round((1/$kurs),4)."><b>&nbsp".$currSelect."</b></div>";
                        echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-top: 1rem; display: flex; flex-direction: row; justify-content: center; align-items: stretch;'><input name='sourceCurr2' id='sourceCurr2' type='number' min = 0 step= 0.01 value=1 oninput='calculateFrom(this.value)'><b>&nbsp".$currSelect."→&nbsp</b><input type='number' step=0.0001 disabled id='targetCurr2' value = ".number_format($kurs, 4)."><b>&nbspPLN</b></div>";
                    } else {
                        echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-top: 1rem; display: flex; flex-direction: row; justify-content: center; align-items: stretch;'><input name='sourceCurr1' id='sourceCurr1' type='number' min = 0 step= 0.01 value=".$_POST['sourceCurr1']." oninput='calculateTo(this.value)'><b>&nbspPLN →&nbsp</b><input type='number' step=0.0001 disabled id='targetCurr1' value = ".round((1/$kurs),4)."><b>&nbsp".$currSelect."</b></div>";
                        echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-top: 1rem; display: flex; flex-direction: row; justify-content: center; align-items: stretch;'><input name='sourceCurr2' id='sourceCurr2' type='number' min = 0 step= 0.01 value=".$_POST['sourceCurr2']." oninput='calculateFrom(this.value)'><b>&nbsp".$currSelect." →&nbsp</b><input type='number' step=0.0001 disabled id='targetCurr2' value = ".number_format($kurs, 4)."><b>&nbspPLN</b></div>";
                    }
                    if ($currSelect == 'RUB') {
                        echo "<p>NBP przestało aktualizować kurs rubla od dnia 9 marca 2022</p>";
                    }
                    } else {
                        echo '
                        <div id="wykres">
                            <canvas id="kanwaWykres" width="400" height="200"></canvas>
                        </div>
                        ';
                        if ($currSelect == 'RUB' or $currSelect == 'UAH') {
                            echo "<p>NBP przestało aktualizować kurs rubla od dnia 9 marca 2022</p>";
                            echo "<p>Kurs hrywny ukraińskiej również jest niemiarodajny</p>";
                        }
            }

            }
            mysqli_close($polaczenie);
            ?>

        </div>
    </div>
    <script src="kantor.js"></script>
    <script src="sort.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php
        $daty = [];
        $polaczenie = mysqli_connect('localhost','root','','kursy');
        if ($currSelect == 'RUB') {
            $kw = 'SELECT DISTINCT Data FROM kursy ORDER BY Data DESC;';    
        } else {
            $kw = 'SELECT DISTINCT Data FROM kursy ORDER BY Data DESC LIMIT 25;';
        }
        $query = mysqli_query($polaczenie, $kw);
        while ($row = mysqli_fetch_row($query)) {
            array_push($daty, $row[0]);
        }
        mysqli_close($polaczenie);
    ?>
    <script>
            
        const labels = [
            <?php
            foreach (array_reverse($daty) as $data) {
                if ($currSelect == 'RUB' and $data == '2022-03-09') {
                    break;
                }
                echo "'".$data."', ";
            }            
            ?>
        ]; 

        const data = {
            labels: labels,
            datasets: [{
                <?php
                echo "label: 'Wykres ".$currSelect."',";
                echo "
                backgroundColor: 'rgb(198,178,123)',
                borderColor: 'rgb(29, 45, 79)',
                ";
                echo "data: [";
                foreach (array_reverse($kursy) as $kurs) {
                    echo "'".$kurs."', ";
                }
                echo "]";
                ?>
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                animation: {
                    duration: 0
            }
}
        };
    </script>
    <script>
        const myChart = new Chart(
            document.getElementById('kanwaWykres'),
            config
        );
    </script>
</body>

</html>