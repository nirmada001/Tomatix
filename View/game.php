<?php
// Start the session
session_start();

// Check if the user is logged in
if(isset($_SESSION['username'])) {
    // get the username from the session
    $username = $_SESSION['username'];
} else {
    // Redirect the user to the login page if not logged in
    header("Location: Login.php");
    exit();
}

// Initialize game data if it doesn't exist in the session
if (!isset($_SESSION['game_data'])) {
    $_SESSION['game_data'] = array(
        'score' => 0,
        'gamesPlayed' => 0,
        'gamesWon' => 0,
        'bestScore' => 0
    );
}

// Retrieve game data from the session
$gameData = $_SESSION['game_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assests/css/game.css?v=<?php echo time(); ?>">
    <title>Main Game Page</title>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
     
    <script>
        // Game data variables
        let score = <?php echo $gameData['score']; ?>;
        let gamesPlayed = <?php echo $gameData['gamesPlayed']; ?>;
        let gamesWon = <?php echo $gameData['gamesWon']; ?>;
        let bestScore = <?php echo $gameData['bestScore']; ?>;

        // Update game data within the session
        function updateGameData(newScore, wonGame) {
            // Update score
            score = newScore;

            // Increment games played
            gamesPlayed++;

            // Update games won
            if (wonGame) {
                gamesWon++;
            }

            // Update best score if applicable
            if (newScore > bestScore) {
                bestScore = newScore;
            }

            // Save updated game data to the session
            saveGameData(score, gamesPlayed, gamesWon, bestScore);
        }


       // Save game data using AJAX
       function saveGameData(score, gamesPlayed, gamesWon, bestScore) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../Controllers/gamedataController.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText); 
                }
            };
            var data = "score=" + score + "&gamesPlayed=" + gamesPlayed + "&gamesWon=" + gamesWon + "&bestScore=" + bestScore;
            xhr.send(data);
        }


        //fetching the tomato api
        function fetchImage() {
            fetch('https://marcconrad.com/uob/tomato/api.php')
                .then(response => response.json())
                .then(data => {
                    imgApi = data.question;
                    solution = data.solution;
                    document.getElementById("imgApi").src = imgApi;
                    document.getElementById("note").innerHTML = '';
                    resetTimer(); // Reset timer for each question
                    startTimer(); // Start the timer for the new question
                    document.getElementById("answer-feedback").textContent = '';
                })
                .catch(error => {
                    console.error('Error fetching image from the API:', error);
                });
        }

        let timeLeft = 60;
        let lives = 3;
        let level = 1;
        let correctAnswers = 0; 
        let timer;
        let solution;

        function startTimer() {
            timer = setInterval(() => {
                timeLeft--;
                document.getElementById("timer").textContent = "Time: " + timeLeft + " seconds";
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    handleTimeOut();
                }
            }, 1000);
        }

        function stopTimer() {
            clearInterval(timer);
        }

        function resetTimer() {
            stopTimer();
            timeLeft = 60;
            document.getElementById("timer").textContent = "Time: " + timeLeft + " seconds";
        }

        //check answers
        function checkAnswer(answer) {
            if (answer == solution) {
                document.getElementById("answer-feedback").textContent = "Correct!";
                score += 10; // Increase score for correct answer
                document.getElementById("score-count").textContent = score;
                correctAnswers++; // Increment correct answer counter
                if (correctAnswers >= 3) {
                    levelUp(); // Check if the user should level up
                } else {
                    fetchImage(); // Move to the next question
                }
                updateGameData(score, true); // Update game data when the user wins
            } else {
                document.getElementById("answer-feedback").textContent = "Wrong!";
                lives--; // Decrease lives for wrong answer
                document.getElementById("life-count").textContent = lives;
                if (lives <= 0) {
                    handleGameOver(); // If no lives left, game over
                }
            }
        }

        function handleTimeOut() {
            document.getElementById("answer-feedback").textContent = "Time's up!";
            lives--; // Decrease lives for running out of time
            document.getElementById("life-count").textContent = lives;
            if (lives <= 0) {
                handleGameOver(); // If no lives left, game over
            } else {
                fetchImage(); // Move to the next question
            }
        }

        function handleGameOver() {
            stopTimer();
            updateGameData(score, gamesPlayed, gamesWon, bestScore);
             // Redirect to the game-over page
             window.location.href = "gameover.php";
        }

        function quitGame() {
            updateGameData(score, gamesPlayed, gamesWon, bestScore);
            window.location.href = "Quitgame.php";s
        }

        function goToHomePage() {
            updateGameData(score, gamesPlayed, gamesWon, bestScore);
            window.location.href = "Menu.php";
        }

        function levelUp() {
            level++; // Increment level
            document.getElementById("level-display").textContent = "Level: " + level;
            correctAnswers = 0; // Reset correct answer counter
            alert("Congratulations! You've reached Level " + level + "!");
            resetTimer(); // Reset the timer
            lives = 3; // Reset the number of lives
            document.getElementById("life-count").textContent = lives;
            updateGameData(score, true); // Update game data when the user wins
            fetchImage(); // Move to the next question
            startTimer();
        }

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
            } else {
                // Pause the audio
                audio.pause();

                // Change the icon to play icon when sound is paused
                document.getElementById('icon').classList.remove('fa-pause');
                document.getElementById('icon').classList.add('fa-volume-up');
            }
        }


        // Start the game
        startTimer();
        fetchImage();

    </script>   
</head>
<body>
    <div class="background-image">
        <div class="content">
            <div class="game">
                    <div class="buttons">
                        <button id="home-btn" class="homebtn" onclick="goToHomePage()"><i class="fa fa-home" aria-hidden="true"></i></button>
                        <button id="quit-btn" class="quitbtn" onclick="quitGame()"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                    <div class="game-content">
                        <div class="timer-lives">
                         <h3 class="welcome">Welcome!!! <?php echo $username; ?></h3>
                            <div id="score">Score: <span id="score-count">0</span></div>
                        </div>
                            <div class="game-image">
                                <img id="imgApi" src="" alt="Game Image" class="game-image">
                                <div class="score-card">
                                    <center>
                                        
                                        <div id="level-display" class="level">Level: 1</div>
                                        <div class="timer-label">
                                            <img src="../Assests/css/images/sand timer im.png">
                                            <div id="timer">Time: <span id="time">60</span> seconds</div>
                                        </div>
                                        <div id="lives" class="lives">Lives: <span id="life-count">3</span></div>
                                        <img src="../Assests/css/images/tomato 1.png" alt="" class="tomato">
                                    </center>
                                </div>
                            </div>
                            
                            
                            
                            <div id="answer-buttons">
                                
                                <?php for ($i = 0; $i <= 9; $i++) { ?>
                                    <button onclick="checkAnswer(<?php echo $i; ?>)"><?php echo $i; ?></button>
                                <?php } ?>
                            </div>
                            <br>
                            <div class="answer-restart">
                                <div class="answer">
                                    <div id="answer-feedback" class="answer-feedback"></div>
                                    <img src="../Assests/css/images/tomat.png" alt="" class="answer-tomato">
                                </div>
                            </div>

                    </div>
                    <br>
                    
                    <button class="btn btn-primary sound-icon" onclick="playOrStopGameSound()"><i id="icon" class="fas fa-volume-up"></i></button>
            </div>
        </div>
    </div>
</body>
</body>
</html>
