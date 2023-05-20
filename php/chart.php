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
                <li><a href="./calc.php">Kalkulator</a></li>
                <li><a href="./chart.php">Wykres</a></li>
            </ul>
        </div>
        <div id="window">
            <div>
                <canvas id="myChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const labels = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June'
  ];

  const data = {
    labels: labels,
    <?php
    echo "datasets: [{
      label: 'Wykres',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 0, 132)',
      data: [0, 10, 5, 2, 20, 30, 45],
    }]";
    ?>
  };

  const config = {
    type: 'line',
    data: data,
    options: {}
  };
</script>
<script>
  const myChart = new Chart(
    document.getElementById('kanwaWykres'),
    config
  );
</script>
</html>