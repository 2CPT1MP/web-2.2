<?php require_once(__DIR__ . '/../../modules/form-validators/examinee.validator.php');
require_once(__DIR__ . '/../../views/test/test-results.view.php');

class TestResultController extends RestController {

    public function GET(Request $request): string {
        if (!isset($request->getParams()["id"]))
            return MessageView::render("Ошибка", "Укажите номер теста для просмотра результата. Например: <br>/test/result?id=1");

        $testId = $request->getParams()["id"];
        $properResult = Result::findById($testId);

        if ($properResult)
            return TestResultsView::render($properResult);
        return MessageView::render("Результат не найден", "Результат теста #$testId не найден");
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}