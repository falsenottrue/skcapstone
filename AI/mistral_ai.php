<?php
function askMistral($prompt) {
    $api_url = "https://api.mistral.ai/v1/chat/completions";
    $api_key = "Z1DgB576zuMtOFhc6iw1KrWuLm0MMF53";

    $data = [
        "model" => "mistral-tiny",  
        "messages" => [["role" => "user", "content" => $prompt]],
        "max_tokens" => 800
    ];

    $options = [
        "http" => [
            "header"  => "Content-Type: application/json\r\n" .
                         "Authorization: Bearer " . $api_key . "\r\n",
            "method"  => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context  = stream_context_create($options);
    $response = file_get_contents($api_url, false, $context);
    return json_decode($response, true)["choices"][0]["message"]["content"] ?? "Error retrieving response.";
}

function generateFlashcard($topic) {
    return askMistral("Create a flashcard for the topic '$topic'. Format:
    **Front:** <topic>
    **Back:** <definition>");
}

function generateQuiz($flashcard_content) {
    $quiz_prompt = "Based on the following flashcard, generate a structured multiple-choice quiz.
    Flashcard: '$flashcard_content'
    
    Format strictly as follows:
    **Question:** <question>
    **A)** <option A>
    **B)** <option B>
    **C)** <option C>
    **D)** <option D>
    **Answer:** <correct letter (A, B, C, or D)>";

    $raw_quiz = askMistral($quiz_prompt);

    // **Extract the question and answer choices**
    preg_match('/\*\*Question:\*\* (.*?)\n/', $raw_quiz, $question_match);
    preg_match('/\*\*A\)\*\* (.*?)\n/', $raw_quiz, $a_match);
    preg_match('/\*\*B\)\*\* (.*?)\n/', $raw_quiz, $b_match);
    preg_match('/\*\*C\)\*\* (.*?)\n/', $raw_quiz, $c_match);
    preg_match('/\*\*D\)\*\* (.*?)\n/', $raw_quiz, $d_match);
    preg_match('/\*\*Answer:\*\* ([A-D])/', $raw_quiz, $answer_match);

    if ($question_match && $a_match && $b_match && $c_match && $d_match && $answer_match) {
        return [
            "question" => trim($question_match[1]),
            "choices" => [
                "A" => trim($a_match[1]),
                "B" => trim($b_match[1]),
                "C" => trim($c_match[1]),
                "D" => trim($d_match[1])
            ],
            "correct_answer" => trim($answer_match[1])
        ];
    } else {
        return ["question" => "Error: Unable to generate quiz", "choices" => [], "correct_answer" => ""];
    }
}