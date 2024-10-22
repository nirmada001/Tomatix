<?php
include '../Controllers/SignInController.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOMATIX</title>
    <link rel="stylesheet" href="../Assests/css/Login.css?v=<?php echo time(); ?>">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
     
    <script>
        function quitGame() {
            window.location.href = "Home.php";
        }

        let audio = null; 

        window.onload = function() {
            playOrStopGameSound(); 
        };


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
            <div class="progress-bar">
                <center>
                    <div class="login">
                        <div class="buttons">
                            <button id="quit-btn" class="quitbtn" onclick="quitGame()"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i></button>
                        </div>
                        <div class="login-content">
                            <h1>User Login</h1>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" id="loginform">

                                <div class="form-input">
                                    <p>Username</p>
                                    <div class="email">
                                        <input type="text" name="lusername" placeholder="Enter Your Username" class="form-control"  required/><br><br>
                                    </div>
                                </div>
                                <div class="form-input">
                                    <p>Password</p>
                                    <div class="password">
                                        <input type="password" name="lpassword" placeholder="Enter Your Password" class="form-control" required /><br><br>
                                    </div>
                                </div>

                                <button class="btn btn-primary" name="userlogin">LOGIN</button><br><br>
                                
                                <p class="login-register-text">Don't Have an Account?<a href="Register.php"> Register Here</a>.</p>	
                            </form>
                        </div>
                    </div>
                </center>
                <button class="btn btn-primary sound-icon" onclick="playOrStopGameSound()"><i id="icon" class="fas fa-volume-up"></i></button>

            </div>
        </div>
    </div>
</body>
</html>