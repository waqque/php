<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Таблица умножения - два способа</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .block {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        h2 {
            color: #da627d;
            text-align: center;
        }
        table {
            border-collapse: collapse;
            margin: 20px auto;
        }
        td, th {
            border: 1px solid #333;
            padding: 8px 12px;
            text-align: center;
        }
        th {
            background-color: #da627d;
            color: white;
        }
        td {
            background-color: #f7e1d7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Таблица умножения</h2>
        
        <div class="block">
            <h3>Способ 1: Линейный (10x10)</h3>
            
            <?php
            echo "<table>";
            
            echo "<tr>";
            echo "<th></th>";
            for ($j = 1; $j <= 10; $j++) {
                echo "<th>$j</th>";
            }
            echo "</tr>";

            for ($i = 1; $i <= 10; $i++) {
                echo "<tr>";
                echo "<th>$i</th>";
                
                for ($j = 1; $j <= 10; $j++) {
                    $result = $i * $j;
                    echo "<td>$result</td>";
                }
                echo "</tr>";
            }

            echo "</table>";
            ?>
        </div>
        
        <div class="block">
            <h3>Способ 2: Функциональный</h3>
            
            <?php
            function getMultiplicationTable($x = 10, $y = 10) {
                $table = "<table>";
                
                $table .= "<tr>";
                $table .= "<th></th>";
                for ($j = 1; $j <= $x; $j++) {
                    $table .= "<th>$j</th>";
                }
                $table .= "</tr>";
                
                for ($i = 1; $i <= $y; $i++) {
                    $table .= "<tr>";
                    $table .= "<th>$i</th>";
                    
                    for ($j = 1; $j <= $x; $j++) {
                        $result = $i * $j;
                        $table .= "<td>$result</td>";
                    }
                    $table .= "</tr>";
                }
                
                $table .= "</table>";
                return $table;
            }
            
            echo "<h4>Таблица 10x10</h4>";
            echo getMultiplicationTable();
            
            echo "<h4>Таблица 5x5</h4>";
            echo getMultiplicationTable(5, 5);
            
            echo "<h4>Таблица 12x7</h4>";
            echo getMultiplicationTable(12, 7);
            ?>
        </div>
    </div>
</body>
</html>