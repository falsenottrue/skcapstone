<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
include 'connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback'], $_POST['topic'])) {
    $feedback = htmlspecialchars($_POST['feedback']);
    $topic = htmlspecialchars($_POST['topic']);

    // Insert feedback into the database
    $stmt = $conn->prepare("INSERT INTO ai_feedback (topic, feedback) VALUES (?, ?)");
    $stmt->bind_param("ss", $topic, $feedback);

    if ($stmt->execute()) {
        // If feedback is "No", regenerate a detailed flashcard
        if ($feedback == "No") {
            require 'AI/mistral_ai.php'; // Include the AI logic
            $new_flashcard = generateFlashcard($topic); // Generate a new detailed flashcard

            // Store the new flashcard in the session or database for display
            $_SESSION['new_flashcard'] = $new_flashcard;

            // Set notification message
            $_SESSION['notification'] = "Thank you for your feedback!";
        } else {
            // Set notification message
            $_SESSION['notification'] = "Thank you for your feedback!";
        }

        // Redirect back to the main page
        header("Location: education_ai.php");
        exit();
    } else {
        $_SESSION['notification'] = "Error saving feedback.";
        header("Location: education_ai.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['notification'] = "Invalid request.";
    header("Location: education_ai.php");
    exit();
}
?>