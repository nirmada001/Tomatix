<?php
// Start the session
session_start();

include '../Model/Connect.php';

if(isset($_SESSION['username'])) {
    // Get data from the AJAX request
    $username = $_SESSION['username'];
    $score = isset($_POST['score']) ? intval($_POST['score']) : 0;
    $gamesPlayed = isset($_POST['gamesPlayed']) ? intval($_POST['gamesPlayed']) : 0;
    $gamesWon = isset($_POST['gamesWon']) ? intval($_POST['gamesWon']) : 0; 
    $bestScore = isset($_POST['bestScore']) ? intval($_POST['bestScore']) : 0;

    // Check if the connection is established
    if ($conn) {
        // Check if a record exists for the user
        $checkStmt = $conn->prepare("SELECT * FROM game_data WHERE username = ?");
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        // If a record exists, update it; otherwise, insert a new record
        if ($result->num_rows > 0) {
            $updateStmt = $conn->prepare("UPDATE game_data SET score = ?, games_played = ?, games_won = ?, best_score = GREATEST(best_score, ?) WHERE username = ?");
            $updateStmt->bind_param("iiiss", $score, $gamesPlayed, $gamesWon, $bestScore, $username);
            
            // Execute the update statement
            if ($updateStmt->execute()) {
                echo "Game data updated successfully";
            } else {
                echo "Error: Unable to update game data";
            }
            
            // Close update statement
            $updateStmt->close();
        } else {
            // Insert new record
            $insertStmt = $conn->prepare("INSERT INTO game_data (username, score, games_played, games_won, best_score) VALUES (?, ?, ?, ?, ?)");
            $insertStmt->bind_param("siiis", $username, $score, $gamesPlayed, $gamesWon, $bestScore);
            
            // Execute the insert statement
            if ($insertStmt->execute()) {
                echo "New game data saved successfully";
            } else {
                echo "Error: Unable to insert new game data";
            }
            
            // Close insert statement
            $insertStmt->close();
        }

        // Close check statement and result
        $checkStmt->close();
        $result->close();
    } else {
        echo "Error: Database connection failed";
    }

    // Close database connection
    $conn->close();
} else {
    echo "User not logged in";
}
?>
