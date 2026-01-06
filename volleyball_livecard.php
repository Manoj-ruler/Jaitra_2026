<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volleyball Live Card</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify_content: center;
            align-items: center;
            font-family: 'Archivo Black', sans-serif;
            background-color: white;
            overflow: hidden;
        }

        .container {
            display: flex;
            gap: 10vw;
            align-items: center;
            width: 100%;
            justify-content: center;
        }

        .team-name {
            font-size: 8vw;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }

        .team-name:empty {
            display: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <div id="team1" class="team-name"></div>
        <div id="team2" class="team-name"></div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const court = urlParams.get('court');

        function updateScore() {
            if (!court) {
                console.error('No court specified in URL');
                return;
            }

            fetch(`api/get_live_match.php?venue=${encodeURIComponent(court)}&sport_id=2`)
                .then(response => response.json())
                .then(data => {
                    const t1 = document.getElementById('team1');
                    const t2 = document.getElementById('team2');

                    if (data.success && data.has_match) {
                        t1.textContent = data.data.team1_name;
                        t2.textContent = data.data.team2_name;
                    } else {
                        t1.textContent = '';
                        t2.textContent = '';
                    }
                })
                .catch(err => console.error('Error fetching score:', err));
        }

        setInterval(updateScore, 3000);
        updateScore();
    </script>
</body>
</html>
