<!DOCTYPE html>
<html>
<head>
    <title>Pencarian Hasil Pertandingan Sepakbola</title>
</head>
<body>
    <h1>Pencarian Hasil Pertandingan Sepakbola</h1>
    <form method="GET">
        <label for="date">Tanggal Pertandingan:</label>
        <input type="date" name="date" id="date">
        <button type="submit">Cari</button>
    </form>

    <?php
        if(isset($_GET["date"])) {
            $date = $_GET["date"];
            $url = "https://api.football-data.org/v2/matches?dateFrom=$date&dateTo=$date";
            $options = array(
                'http' => array(
                    'method' => 'GET',
                    'header' => "X-Auth-Token: d336fd5da19d4f97a2a5d3edc1ee423f\r\n"
                )
            );

            $context = stream_context_create($options);
            $response = file_get_contents($url, false, $context);

            $matches = json_decode($response, true)["matches"];

            if(empty($matches)) {
                echo "<p>Tidak ada hasil pertandingan pada tanggal $date.</p>";
            } else {
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Tanggal Pertandingan</th>";
                echo "<th>Tim Tuan Rumah</th>";
                echo "<th>Skor</th>";
                echo "<th>Tim Tamu</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                foreach($matches as $match) {
                    $matchDate = date('d F Y H:i', strtotime($match["utcDate"]));
                    $homeTeam = $match["homeTeam"]["name"];
                    $awayTeam = $match["awayTeam"]["name"];
                    $score = $match["score"]["fullTime"]["homeTeam"] . " - " . $match["score"]["fullTime"]["awayTeam"];

                    echo "<tr>";
                    echo "<td>$matchDate</td>";
                    echo "<td>$homeTeam</td>";
                    echo "<td>$score</td>";
                    echo "<td>$awayTeam</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            }
        }
    ?>
</body>
</html>
