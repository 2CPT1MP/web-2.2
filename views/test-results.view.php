<?php require_once('header.view.php');
require_once("../models/test.model/test-results.model.php");

class TestResultsView {
    public static function render(TestResults $testResults): string {
        $html = HeaderView::render("Результаты тестирования");
        $html .= '<section class="card">';

        return "$html 
            <h3>$title</h3>
            <p>$text</p>
        </section>";
    }
}