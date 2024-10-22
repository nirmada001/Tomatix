<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOMATIX</title>
    <!-- CSS file -->
    <link rel="stylesheet" href="../Assests/css/Home.css?v=<?php echo time(); ?>">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        let audio = null; 

        window.onload = function() {
           playOrStopGameSound(); 
        };

        function playOrStopGameSound() {
            if (!audio || audio.paused) {
                //FreeSound API key
                const apiKey = 'a0iyeUhzXv5cyxg0oXeb2Sz3HBU2CVHNC5OsXWZa';

                // Make a request to the FreeSound API using the sound's ID
                fetch('https://freesound.org/apiv2/sounds/514878/?token=' + apiKey)
                    .then(response => response.json())
                    .then(data => {
                        // Get the URL of the sound
                        const soundUrl = data.previews['preview-hq-mp3'];

                        // Create an audio element dynamically
                        audio = new Audio(soundUrl);

                        audio.loop = true;

                        // Play the sound
                        audio.play();

                        // Change the icon to pause icon when sound starts playing
                        document.getElementById('icon').classList.remove('fa-pause');
                        document.getElementById('icon').classList.add('fa-volume-up');
                    })
                    .catch(error => {
                        console.error('Error fetching game sound:', error);
                    });
            }
        }
    </script>
</head>
<body>
    <div class="background-image">
        <div class="content">
            <div class="progress-bar">
                <center>
                    
                    <div class="playbutton">
                        <a href="Login.php" class="btn btn-info btn-lg">
                            <i class="fas fa-play"></i>
                        </a>
                    </div>
                </center>
            </div>
        </div>
    </div>
</body>
</html>
