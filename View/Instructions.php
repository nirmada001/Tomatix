<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Instructions</title>
        <link rel="stylesheet" href="../Assests/css/Instructions.css?v=<?php echo time(); ?>">
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
       
        <script>
            let audio = null; 

            window.onload = function() {
                playOrStopGameSound(); 
            };
            function quitGame() {
                window.location.href = "Quitgame.php";
            }

            function goToHomePage() {
                window.location.href = "Menu.php";
            }

            function playOrStopGameSound() {
            
            if (!audio || audio.paused) {
                // FreeSound API key
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
        <div class="background-image">
            <div class="content">
                <center>
                    <div class="instructions">
                        <div class="instrction-content">
                            <div class="buttons">
                                <button id="home-btn" class="homebtn" onclick="goToHomePage()"><i class="fa fa-home" aria-hidden="true"></i></button>
                                <button id="quit-btn" class="quitbtn" onclick="quitGame()"><i class="fa fa-times" aria-hidden="true"></i></button>
                            </div>
                            <div class="inst-text">
                                <p>In this game, players are presented with tomato-themed 
                                    images containing math problems where a number is missing. 
                                    Players must guess the missing number within a set time limit 
                                    to progress. The game offers multiple levels of difficulty, and 
                                    players can select their desired level at the start. Each correct 
                                    guess earns points, but failing to guess within the time limit 
                                    may result in losing lives. The game continues until all 
                                    questions are answered or lives are lost. Quick thinking and 
                                    accuracy are crucial for success!
                                </p>
                            </div>
                        </div>
                    </div>
                </center>

                <button class="btn btn-primary sound-icon" onclick="playOrStopGameSound()"><i id="icon" class="fas fa-volume-up"></i></button>
            </div>
        </div>
    </body>
</html>