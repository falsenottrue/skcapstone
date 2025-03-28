<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['login_id'])) {
    echo "<script>alert('You must be logged in to access this program.'); window.location.href='index.php';</script>";
    exit();
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>AI Flashcard & Quiz Generator</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f4f4f4; }
        input, button { padding: 10px; margin: 10px; font-size: 16px; }

        .container { display: flex; flex-direction: column; align-items: center; }
        .flashcard, .quiz-card {
            background: white;
            width: 350px;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .back, .quiz-card, .correct-answers {
            display: none;
        }
        .btn-toggle {
            margin-top: 10px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>AI-Powered Flashcards & Quizzes</h1>
    
    <form method="POST">
        <input type="text" name="topic" placeholder="Enter a topic..." required autocomplete="off">
        <button type="submit">Generate</button>
    </form>

    <div class="container">
    <?php
require 'AI/mistral_ai.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topic = htmlspecialchars($_POST["topic"]);

    $flashcard_front = $topic;
    $flashcard_back = generateFlashcard($topic);

    $quiz_data = generateQuiz($flashcard_back);
    $quiz_question = $quiz_data["question"];
    $quiz_choices = $quiz_data["choices"];
    $correct_answer = $quiz_data["correct_answer"];

    echo "<div class='flashcard' id='flashcard'>
            <h3>Front:</h3>
            <p>$flashcard_front</p>
            <div class='back' id='flashcard-back'>
                <h3>Back:</h3>
                <p>$flashcard_back</p>
            </div>
          </div>";

    echo "<button class='btn btn-primary btn-toggle' onclick='toggleFlashcard()'>Show Flashcard Back</button>";

    if (!empty($quiz_question)) {
        echo "<div class='quiz-card' id='quiz-card'>
                <h3>Quiz on $topic</h3>
                <p><strong>Question:</strong> $quiz_question</p>
                <ul>";
        
        foreach ($quiz_choices as $letter => $choice) {
            echo "<li><input type='radio' name='quiz_answer' value='$letter'> <strong>$letter)</strong> $choice</li>";
        }
        
        echo "</ul>
                <button class='btn btn-warning btn-toggle' onclick='toggleAnswers()'>Show Answers</button>
                <div class='correct-answers' id='correct-answers' style='display:none;'>
                    <h4>Correct Answer:</h4>
                    <p><strong>$correct_answer</strong></p>
                </div>
              </div>";
        echo "<button class='btn btn-success btn-toggle' onclick='toggleQuiz()'>Show Quiz</button>";
    }
}
?>

<script>
    function toggleFlashcard() {
        let back = document.getElementById('flashcard-back');
        back.style.display = (back.style.display === 'none' || back.style.display === '') ? 'block' : 'none';
    }

    function toggleQuiz() {
        let quiz = document.getElementById('quiz-card');
        quiz.style.display = (quiz.style.display === 'none' || quiz.style.display === '') ? 'block' : 'none';
    }

    function toggleAnswers() {
        let answers = document.getElementById('correct-answers');
        answers.style.display = (answers.style.display === 'none' || answers.style.display === '') ? 'block' : 'none';
    }
</script>

    <a href="Community_Events.php">
        <button class="btn btn-danger mt-3"> Back </button>
    </a>
</body>
</html>
