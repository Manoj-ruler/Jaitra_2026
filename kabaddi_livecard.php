<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kabaddi Live Card</title>
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
            background-color: white; /* Default to white, can be made transparent if needed */
            overflow: hidden;
        }

        .container {
            display: flex;
            gap: 10vw; /* Gap between teams */
            align-items: center;
            width: 100%;
            justify-content: center;
        }

        .team-name {
            font-size: 3vw; /* Large text */
            font-weight: bold;
            text-align: center;
        }

        /* Optional: Hide if empty to prevent layout shifts */
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

            fetch(`api/get_live_match.php?venue=${encodeURIComponent(court)}&sport_id=1`)
                .then(response => response.json())
                .then(data => {
                    const t1 = document.getElementById('team1');
                    const t2 = document.getElementById('team2');

                    if (data.success && data.has_match) {
                        t1.textContent = data.data.team1_name;
                        t2.textContent = data.data.team2_name;
                    } else {
                        // Clear if no match is live
                        t1.textContent = '';
                        t2.textContent = '';
                    }
                })
                .catch(err => console.error('Error fetching score:', err));
        }

        // Poll every 3 seconds
        setInterval(updateScore, 3000);
        
        // Initial call
        updateScore();
    </script>
</body>
</html>
