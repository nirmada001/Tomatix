<?php

include '../Model/Connect.php';
// Start the session
session_start();

// Check if the user is logged in
if(isset($_SESSION['username'])) {
    // User is logged in, get the username from the session
    $Uusername = $_SESSION['username'];

    $sql = "SELECT username, best_score FROM game_data ORDER BY best_score DESC";
    $result = $conn->query($sql);

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
    <title>Scoreboard</title>
    <link rel="stylesheet" href="../Assests/css/Scoreboard.css?v=<?php echo time(); ?>">
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
                <div class="level">
                    <div class="buttons">
                        <button id="home-btn" class="homebtn" onclick="goToHomePage()"><i class="fa fa-home" aria-hidden="true"></i></button>
                        <button id="quit-btn" class="quitbtn" onclick="quitGame()"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                    <div class="level-content">
                        <h2 class="welcome">Scoreboard</h2><br>

                        <div class="level-button">
                            <div class="button">
                                <?php
                                    while($row = mysqli_fetch_array($result)){
                                    echo "<div class= 'scores'>";
                                    echo "<div class='players'>" . "<p>" . $row["username"] . "</p>" ."</div><br>". "<div class='players'>" . "<p>". $row["best_score"] . "</p>" ."</div> <br> <br> ";
                                    echo "</div>";
                                    } 
                                ?>
                            </div>

                            <img src="../Assests/css/images/tomato 1.png" alt="" class="tomato">
                        </div>
                    </div>
                </div>
            </center> 
            
            <button class="btn btn-primary sound-icon" onclick="playOrStopGameSound()"><i id="icon" class="fas fa-volume-up"></i></button>
        </div>
    </div> 
</body>
</html>
