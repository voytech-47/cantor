<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Kantorek</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="shortcut icon" href="../components/icon.ico" type="image/x-icon">
</head>

<body>
    <div id="wrapper">
        <div id="banner">
            <img src="../components/logo.png" alt="logo" style='width:100px; padding:10px'>
            <h1 style="flex-grow: 3;"><a href="./index.php">Witamy na stronie kantorka!</a></h1>
            <ul style="flex-grow: 2;">
                <li><a href="./usd.php">Kurs USD</a></li>
                <li><a href="./gbp.php">Kurs GBP</a></li>
                <li><a href="./eur.php">Kurs EUR</a></li>
            </ul>
        </div>
        <div id="window">
            <?php
                // error_reporting(0);
                // ini_set('display_errors', 0);
                $polaczenie = mysqli_connect('localhost','root','','kursy');
                $NrTabeli = "SELECT DISTINCT Data FROM kursy ORDER BY Data DESC;";
                $QNrTabeli = mysqli_query($polaczenie, $NrTabeli);
                echo "<form method='POST'><div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-bottom: 0.5rem'>";
                echo "<label for='czek'>Pokazać wszystkie rekordy?</label>";
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
                        $selectAll = "SELECT * FROM kursy WHERE KodWaluty = 'GBP' ORDER BY Data DESC;";
                    } elseif (!isset($_POST['selectDate'])) {
                        $selectAll = "SELECT * FROM kursy WHERE Data = '".$doSelecta."' AND KodWaluty = 'GBP' ORDER BY Data DESC;";
                    } else {
                        $selectAll = "SELECT * FROM kursy WHERE Data = '".$_POST['selectDate']."' AND KodWaluty = 'GBP' ORDER BY Data DESC;";
                    }
                    $selectAllTitle = "SELECT * FROM kursy WHERE KodWaluty = 'GBP';";
                    $QselectAll = mysqli_query($polaczenie, $selectAllTitle);
                    $rowTemp = mysqli_fetch_row($QselectAll);
                    echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center'>";
                    if (isset($_POST['czek']) AND $_POST['czek'] == 'on') {
                        echo "<h2>Aktualne kursy GBP od dnia: ".$rowTemp[1]."</h2>";
                    } else {
                        echo "<h2>Aktualny kurs GBP z dnia: ".$rowTemp[1]."</h2>";
                    }
                    echo "<table>";
                    $QselectAll = mysqli_query($polaczenie, $selectAll);
                    echo "<tr>";
                    echo "<th>Numer tabeli</th>";
                    echo "<th>Data</th>";
                    echo "<th>Kurs średni</th>";
                    echo "</tr>";
                    while ($row2 = mysqli_fetch_row($QselectAll)) {
                        echo "<tr>";
                        echo "<td style='text-align: center; background-color: rgb(232,232,232)'>".$row2[0]."</td>";
                        echo "<td style='text-align: center; background-color: rgb(232,232,232)'>".$row2[1]."</td>";
                        echo "<td style='text-align: right; background-color: rgb(232,232,232)'>".$row2[3]."</td>";
                        echo "</tr>";
                    }
                    echo "</table></div>";
                    if (!isset($_POST['czek'])) {
                    $QselectAll = mysqli_query($polaczenie, $selectAll);
                    $rowTemp = mysqli_fetch_row($QselectAll);
                    $kurs = $rowTemp[3];
                    if (!isset($_POST['sourceCurr1']) or !isset($_POST['sourceCurr2'])) {
                        echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-top: 1rem; display: flex; flex-direction: row; justify-content: center; align-items: stretch;'><input name='sourceCurr1' id='sourceCurr1' type='number' min = 0 step= 0.01 value=1 oninput='calculateTo(this.value)'><b>&nbspPLN →&nbsp</b><input type='number' disabled id='targetCurr1' value = ".round((1/$kurs),4)."><b>&nbspGBP</b></div>";
                        echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-top: 1rem; display: flex; flex-direction: row; justify-content: center; align-items: stretch;'><input name='sourceCurr2' id='sourceCurr2' type='number' min = 0 step= 0.01 value=1 oninput='calculateFrom(this.value)'><b>&nbspGBP →&nbsp</b><input type='number' disabled id='targetCurr2' value = ".$kurs."><b>&nbspPLN</b></div>";
                    } else {
                        echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-top: 1rem; display: flex; flex-direction: row; justify-content: center; align-items: stretch;'><input name='sourceCurr1' id='sourceCurr1' type='number' min = 0 step= 0.01 value=".$_POST['sourceCurr1']." oninput='calculateTo(this.value)'><b>&nbspPLN →&nbsp</b><input type='number' disabled id='targetCurr1' value = ".round((1/$kurs),4)."><b>&nbspGBP</b></div>";
                        echo "<div style='font-size: 20px; color: rgb(29,45,79); text-align: center; margin-top: 1rem; display: flex; flex-direction: row; justify-content: center; align-items: stretch;'><input name='sourceCurr2' id='sourceCurr2' type='number' min = 0 step= 0.01 value=".$_POST['sourceCurr2']." oninput='calculateFrom(this.value)'><b>&nbspGBP →&nbsp</b><input type='number' disabled id='targetCurr2' value = ".$kurs."><b>&nbspPLN</b></div>";
                    }
                    }
                    
                }
                mysqli_close($polaczenie);
                ?>

        </div>
    </div>
    <script src="../script/kantor.js"></script>
</body>

</html>