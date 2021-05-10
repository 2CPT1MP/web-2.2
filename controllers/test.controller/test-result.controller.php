<?php require_once(__DIR__ . '/../../modules/form-validators/examinee.validator.php');
require_once(__DIR__ . '/../../views/test/test-results.view.php');

class TestResultController implements Controller {
    public function processRequest($request): string {
        if ($request->getMethod() === 'GET') {
            if (!isset($request->getParams()["id"]))
                return MessageView::render("Ошибка", "Укажите номер теста для просмотра результата. Например: <br>/test/result?id=1");
            $result = Result::findById($request->getParams()["id"]);
            if ($result) return TestResultsView::render($result);
                return MessageView::render("Результат не найден", "Результат теста #{$request->getParams()["id"]} не найден");
        }
        return "<p>Handler was not found</p>";
    }
}