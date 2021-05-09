<?php require_once('header.view.php');
//require_once("../models/test.model/test-results.model.php");
require_once("../models/test.model/test-question.model.php");

class TestResultsView {
    public static function render(Result $result): string {
        $html = HeaderView::render("Результаты тестирования");
        $html .= '<section class="card"><h2>Результаты тестирования</h2>';
        $questions = [];
        $number = 1;

        $maxScore = count($result->getAnswers());
        $actualScore = 0;

        foreach ($result->getAnswers() as $answer) {
            $questions[$answer->getTestQuestionId()][] = $answer;
        }

        foreach ($questions as $question) {
            $html .= "<h3>Вопрос $number</h3>";

            foreach ($question as $answer) {
                if ($answer->getType() === "RIGHT") {
                    $html .= "<input type='checkbox' checked disabled><span class='correct'> {$answer->getText()}</span><br>";
                    $actualScore++;
                }
                else {
                    $html .= "<input type='checkbox' checked disabled><span class='wrong'> {$answer->getText()}</span><br>";
                }
            }
            $number++;
        }

        $percent = round($actualScore / $maxScore * 100);

        $html .= "<h2>Итог: $actualScore/ $maxScore ($percent%)</h2>";
        return "</section>" . $html;
    }
}