<?php require_once('header.view.php');
//require_once("../models/test.model/test-results.model.php");
require_once("../models/test.model/test-question.model.php");

class TestResultsView {
    public static function render(TestResults $testResults): string {
        $html = HeaderView::render("Результаты тестирования");
        $html .= '<section class="card"><h2>Результаты тестирования</h2>';

        $number = 1;
        foreach ($testResults->getTestAnswer() as $testQuestion) {
            $actualAnswer = $testQuestion->getAnswers();
            $html .= "<h3>Вопрос $number: {$testQuestion->getTestQuestion()->getQuestion()} ({$testQuestion->getActualScore()} / {$testQuestion->getMaxScore()})</h3>";
            $allAnswers = array_merge($testQuestion->getTestQuestion()->getRightAnswers(), $testQuestion->getTestQuestion()->getWrongAnswers());

            foreach ($testQuestion->getTestQuestion()->getRightAnswers() as $rightAnswer) {
                if (in_array($rightAnswer->getText(), $actualAnswer))
                    $html .= "<input class='correct' type='checkbox' checked disabled><span class='correct'> {$rightAnswer->getText()}</span><br>";
                else
                    $html .= "<input type='checkbox' disabled><span class='correct'> {$rightAnswer->getText()}</span><br>";
            }

            foreach ($testQuestion->getTestQuestion()->getWrongAnswers() as $wrongAnswer) {
                if (in_array($wrongAnswer->getText(), $actualAnswer))
                    $html .= "<input type='checkbox' checked disabled><span class='wrong'> {$wrongAnswer->getText()}</span><br>";
                else
                    $html .= "<input type='checkbox' disabled> <span class='wrong'> {$wrongAnswer->getText()}</span><br>";
            }

            $allAnswersTexts = [];
            foreach ($allAnswers as $allAnswer)
                $allAnswersTexts[] = $allAnswer->getText();

            foreach ($actualAnswer as $answer) {
                if (!in_array($answer, $allAnswersTexts))
                    $html .= "<input type='checkbox' checked disabled><span class='wrong'> {$answer->getText()}</span><br>";
            }
            $number++;
        }
        $actualScore = $testResults->getActualScore();
        $possibleScore = $testResults->getMaxScore();
        $percent = $actualScore / $possibleScore * 100;

        $html .= "<h2>Итог: $actualScore / $possibleScore ($percent%)</h2>";
        return "</section>" . $html;
    }
}