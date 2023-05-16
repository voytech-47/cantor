<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Kantorek</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="icon.ico" type="image/x-icon">
</head>

<body>
    <div id="wrapper">
        <div id="banner">
            <img src="logo.png" alt="logo" style='width:100px; padding:10px'>
            <h1 style="flex-grow: 3;"><a href="./index.php">Witamy na stronie kantorka!</a></h1>
            <ul style="flex-grow: 2;">
                <li>
                    <a class='link' href="./calc.php">Kurs wybranej waluty
                    <img id='stock' class='svg' src="stock.svg">
                    </a>
                </li>
            </ul>
        </div>
        <div id="window">
            <?php
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
                    "XDR" => 1,
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
                    "HUF" => 'forintów węgierskich',
                    "CHF" => 'frank szwajcarski',
                    "GBP" => 'funt brytyjski',
                    "UAH" => 'hrywna ukraińska',
                    "JPY" => 'jenów',
                    "CZK" => 'korona czeska',
                    "DKK" => 'korona duńska',
                    "ISK" => 'koron islandzkich',
                    "NOK" => 'korona norweska',
                    "SEK" => 'korona szwedzka',
                    "HRK" => 'kuna chorwacka',
                    "RON" => 'lej rumuński',
                    "BGN" => 'lew bułgarski',
                    "TRY" => 'lira turecka',
                    "ILS" => 'nowy izraelski szekel',
                    "CLP" => 'peso chilijskich',
                    "PHP" => 'peso filipińskie',
                    "MXN" => 'peso meksykańskie',
                    "ZAR" => 'rand (RPA)',
                    "BRL" => 'real brazylijski',
                    "MYR" => 'ringgit malezyjski',
                    "RUB" => 'rubel rosyjski',
                    "IDR" => 'rupii indonezyjskich',
                    "INR" => 'rupii indyjskich',
                    "KRW" => 'wonów południowokoreańskich',
                    "CNY" => 'yuan chiński',
                    "XDR" => 'SDR'
                );
                error_reporting(0);
                ini_set('display_errors', 0);
                $polaczenie = mysqli_connect('localhost','root','','kursy');
                if (mysqli_connect_errno()) {
                    echo "<p>Nie znaleziono bazy danych! Upewnij się, że uruchomiony został plik <i><a href='kantorek.py'>kantorek.py</a></i> lub została zaimportowana baza danych <i><a href='kursy.sql'>kursy.sql</a></i>.</p>";
                    return;
                }
                $NrTabeli = "SELECT DISTINCT Data FROM kursy ORDER BY Data DESC;";
                $QNrTabeli = mysqli_query($polaczenie, $NrTabeli);
                echo "<form method='POST'><span style='font-size: 20px; color: rgb(29,45,79);'>Wybierz dzień: <select name='selectDate' onchange='this.form.submit()' style='font-size: 16px' id='selectTable'></form>";
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
                echo "</select></span>";
                if (mysqli_connect_errno()) {
                    echo "<p>Nie znaleziono bazy danych! Upewnij się, że uruchomiony został plik <i><a href='kantorek.py'>kantorek.py</a></i> lub baza danych została zaimportowana (<a href='kursy.sql'>kursy.sql</a>).</p>";
                } else {
                    if (!isset($_POST['selectDate'])) {
                    $selectAll = "SELECT * FROM kursy WHERE Data = '".$doSelecta."';";
                    } else {
                        $selectAll = "SELECT * FROM kursy WHERE Data = '".$_POST['selectDate']."';";
                    }
                    $QselectAll = mysqli_query($polaczenie, $selectAll);
                    $rowTemp = mysqli_fetch_row($QselectAll);
                    $QselectAll = mysqli_query($polaczenie, $selectAll);
                    echo "<h2>Aktualne kursy walut z dnia: ".$rowTemp[1]."</h2>";
                    echo "<table>";
                    echo "<tr>";
                    echo "<th>Numer tabeli</th>";
                    echo "<th>Data</th>";
                    echo "<th>Waluta</th>";
                    echo "<th>Kurs średni</th>";
                    echo "</tr>";
                    $i = 0;
                    while ($row2 = mysqli_fetch_row($QselectAll)) {
                        if ($i % 2 == 1 ) {
                            echo "<tr style='text-align: center; background-color: rgb(232,232,232)'>";
                        } else {
                            echo "<tr>";
                        }
                            echo "<td id='field' style='text-align: center'>".$row2[0]."</td>";
                            echo "<td id='field' style='text-align: center'>".$row2[1]."</td>";
                            echo "<td id='field' class='walutaTab'><span>".$waluty[$row2[2]]." ".$kody[$row2[2]]."</span><span>(".$row2[2].")</span></td>";
                            echo "<td id='field' style='text-align: right'>".number_format($row2[3]*$waluty[$row2[2]], 4)."</td>";
                            $i++;
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                mysqli_close($polaczenie);
                ?>
        </div>
    </div>
</body>

</html>