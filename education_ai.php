<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
include 'connection.php';
include 'session_timeout.php';
session_start();

if (!isset($_SESSION['login_id'])) {
    echo "<script>alert('You must be logged in to access this program.'); window.location.href='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Flashcard & Quiz Generator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="img/sklogo.png" type="image/png">
    <link rel="stylesheet" href="style/ai.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
    
<body>

<div id="notification" class="notification" style="display: none;"></div>
<?php
if (isset($_SESSION['notification'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const notification = document.getElementById("notification");
            notification.textContent = "' . $_SESSION['notification'] . '";
            notification.style.display = "block";
            setTimeout(() => {
                notification.style.opacity = "1";
                setTimeout(() => {
                    notification.style.opacity = "0";
                    setTimeout(() => {
                        notification.style.display = "none";
                    }, 500); // Match fade-out duration
                }, 3000); // Display for 3 seconds
            }, 100);
        });
    </script>';
    unset($_SESSION['notification']);
}
?>
<div class="main-container">
    
    <h1>AI-Powered Flashcards & Quizzes</h1>

    <form method="POST" onsubmit="return showSpinner()">
        <input type="text" name="topic" placeholder="Enter a topic..." required autocomplete="off">
        <button type="submit" class="btn btn-primary">Confirm</button>
    </form>
    
    <?php
    require 'AI/mistral_ai.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $topic = htmlspecialchars($_POST["topic"]);
  
        $flashcard_front = $topic;
        $raw_flashcard = generateFlashcard($topic);

        // Optional cleanup using regex or str_replace
        $flashcard_back = preg_replace('/\*\*Front:\*\*.*?\*\*Back:\*\*/is', '', $raw_flashcard);
        $flashcard_back = trim($flashcard_back);
        

        $quiz_data = generateQuiz($flashcard_back);
        $quiz_question = $quiz_data["question"];
        $quiz_choices = $quiz_data["choices"];
        $correct_answer = $quiz_data["correct_answer"];

        

        echo '
        <!-- Flashcard Modal -->
        <div class="modal fade show" id="flashcard" style="display: block;" tabindex="-1" aria-modal="true" role="dialog">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
              <div class="modal-header">
                <h5 class="modal-title"><strong>Flashcard - ' . htmlspecialchars($flashcard_front) . '</strong></h5>
                <button type="button" class="btn-close" aria-label="Close" onclick="quitFlashcard()"></button>
              </div>
              <div class="modal-body">
                <p><strong>Definition:</strong> ' . $flashcard_back . '</p>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary" onclick="proceedToQuiz()">Proceed to Quiz</button>
                <button class="btn btn-danger" onclick="quitFlashcard()">Quit</button>
              </div>
            </div>
          </div>
        </div>
        ';
    
        

        if (!empty($quiz_question)) {
            echo '
            <!-- Quiz Modal -->
            <div class="modal fade show" id="quiz-card" style="display: none;" tabindex="-1" aria-modal="true" role="dialog">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                  <div class="modal-header">
                    <h5 class="modal-title"><strong>Quiz on ' . htmlspecialchars($topic) . '</strong></h5>
                    <button type="button" class="btn-close" aria-label="Close" onclick="quitFlashcard()"></button>
                  </div>
                  <div class="modal-body">
                    <p><strong>Question:</strong> ' . $quiz_question . '</p>
                <ul id="quiz-options">';
                foreach ($quiz_choices as $letter => $choice) {
                    echo "<li><label><input type='radio' name='quiz_answer' value='$letter'> <strong>$letter)</strong> $choice</label></li>";
                }
                echo '
                </ul>
                <div class="correct-answers mt-3" id="correct-answers" style="display:none;">
                <p><strong>Correct Answer:</strong> ' . $correct_answer . '</p>
                <p id="answer-feedback" class="fw-semibold"></p>
                </div>
                </div>
                 <div class="score-summary mt-3" id="score-summary" style="display:none;">
                        <p><strong>Your Score:</strong> <span id="score-value"></span></p>
                    </div>
                <div class="modal-footer">
                <button class="btn btn-success" onclick="confirmAnswer(\'' . $correct_answer . '\')">Confirm Answer</button>
             <button type="button"
        class="btn btn-danger"
        onclick="quitFlashcard()">
  Quit
</button>
                </div>

                </div>
              </div>
            </div>
            ';
            
        }
    }
    ?>

<div id="loading-spinner">
    <div class="spinner"></div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedback-modal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Provide Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="save_feedback.php">
                    <input type="hidden" name="topic" value="<?php echo htmlspecialchars($topic); ?>">
                    <p>Was this helpful?</p>
                    <button type="submit" name="feedback" value="Yes" class="btn btn-success">Yes</button>
                    <button type="submit" name="feedback" value="No" class="btn btn-danger">No</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="redirectToCommunityEvents()">Quit Without Feedback</button>
            </div>
        </div>
    </div>
</div>
    <a href="Community_Events.php">
        <button class="btn btn-danger">Back</button>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
        function proceedToQuiz() {
            document.getElementById('flashcard').style.display = 'none';
            const quiz = document.getElementById('quiz-card');
            if (quiz) {
                quiz.style.display = 'block';
            }
        }

        function quitFlashcard() {
            window.location.href = 'Community_Events.php';
        }

    function toggleQuiz() {
        let quiz = document.getElementById('quiz-card');
        quiz.style.display = (quiz.style.display === 'none' || quiz.style.display === '') ? 'block' : 'none';
    }

    function toggleAnswers() {
        let answers = document.getElementById('correct-answers');
        answers.style.display = (answers.style.display === 'none' || answers.style.display === '') ? 'block' : 'none';
    }

    let score = 0;
let totalQuestions = 1;

function confirmAnswer(correctAnswer) {
    const selected = document.querySelector('input[name="quiz_answer"]:checked');
    const feedback = document.getElementById('answer-feedback');
    const correctBox = document.getElementById('correct-answers');
    const scoreBox = document.getElementById('score-summary');
    const scoreValue = document.getElementById('score-value');

    if (!selected) {
        alert('Please select an answer first.');
        return;
    }

    const userAnswer = selected.value;

    if (userAnswer === correctAnswer) {
        feedback.textContent = '✅ Correct!';
        feedback.style.color = 'green';
        score++;
    } else {
        feedback.textContent = '❌ Incorrect.';
        feedback.style.color = 'red';
    }

    // Show correct answer box
    correctBox.style.display = 'block';

    // Show score
    scoreValue.textContent = `${score} / ${totalQuestions}`;
    scoreBox.style.display = 'block';

    // Delay the feedback modal by 2 seconds (2000 milliseconds)
    setTimeout(function() {
        const feedbackModal = new bootstrap.Modal(document.getElementById('feedback-modal'));
        feedbackModal.show();
    }, 2000); // 2000 ms = 2 seconds

    function redirectToCommunityEvents() {
    // Close the feedback modal if it's open
    const feedbackModal = new bootstrap.Modal(document.getElementById('feedback-modal'));
    feedbackModal.hide();  // This hides the modal

    // Redirect to the Community_Events page
    window.location.href = 'education_ai.php';  // Adjust this URL if necessary
}

    // Disable confirm button to prevent multiple submissions
    document.querySelector('button[onclick^="confirmAnswer"]').disabled = true;
}

document.getElementById("loading-spinner").style.display = "flex";
document.getElementById("loading-spinner").style.display = "none";

function showSpinner() {
    document.getElementById("loading-spinner").style.display = "flex";
    return true; // Allow the form to submit
}
window.onload = function() {
    document.getElementById("loading-spinner").style.display = "none";
};


</script>

</body>
</html>
