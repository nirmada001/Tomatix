<?php

include '../Model/Connect.php';
// Start the session
session_start();

// Check if the user is logged in
if(isset($_SESSION['username'])) {
    // User is logged in, get the username from the session
    $Uusername = $_SESSION['username'];

} else {
    // Redirect the user to the login page if not logged in
    header("Location: Login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Over</title>
    <link rel="stylesheet" href="../Assests/css/gameover.css?v=<?php echo time(); ?>">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      
    <script>
        let audio = null; 

       
        window.onload = function() {
            fetchMotivationalQuote();
            playOrStopGameSound();
        };

        function quitGame() {
            window.location.href = "Quitgame.php";
        }

        function goToHomePage() {
            window.location.href = "Menu.php";
        }

        function restartGame() {
            // Redirect to game.php
            window.location.href = "game.php";
        }

        //motivational quote api
        function fetchMotivationalQuote() {
            fetch('../Model/proxy.php') // Use the proxy script instead of the ZenQuotes API directly
                .then(response => response.json())
                .then(data => {
                    const quote = data[0].q; // Extract the quote from the API response
                    // Display the quote 
                    document.getElementById("motivational-quote").textContent = quote;
                })
                .catch(error => {
                    console.error('Error fetching motivational quote:', error);
                });
        }

        function playOrStopGameSound() {
            
            if (!audio || audio.paused) {
                //  FreeSound API key
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
            } else {
                // Pause the audio
                audio.pause();

                // Change the icon to play icon when sound is paused
                document.getElementById('icon').classList.remove('fa-pause');
                document.getElementById('icon').classList.add('fa-volume-up');
            }
        }
        
    </script>
</head>
<body>
    <div class="video">
        <!-- Video background -->
        <video id="video-background" autoplay muted loop>
                <source src="../Assests/css/images/gameover background.mp4" type="video/mp4">
            </video>
    </div>
    


        <div class="content">
            <center>
                <div class="level">
                    <div class="buttons">
                        <button id="home-btn" class="homebtn" onclick="goToHomePage()"><i class="fa fa-home" aria-hidden="true"></i></button>
                        <button id="quit-btn" class="quitbtn" onclick="quitGame()"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>

                    <div class="gemeover-img">
                        <img src="../Assests/css/images/gameover.png" alt="">
                    </div>
                    
                    <div class="quote-background">
                        <div class="motivational-quote" id="motivational-quote"></div>
                    </div>

                    <button id="restart-btn" class="restartbtn" onclick="restartGame()">Restart</button>
                </div>
            </center> 

            <button class="btn btn-primary sound-icon" onclick="playOrStopGameSound()"><i id="icon" class="fas fa-volume-up"></i></button>

        </div>
</body>
</html>
