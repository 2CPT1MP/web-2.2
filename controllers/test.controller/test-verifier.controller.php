<?php require_once(__DIR__ . '/../../modules/form-validators/examinee.validator.php');
require_once(__DIR__ . '/../../views/test-results.view.php');

class TestVerifierController implements Controller {

    public function processRequest($request): string {
        if ($request->getMethod() === 'POST') {
            $validator = new ExamineeValidator($request->getBody());
            $result = $validator->validate();

            if ($result[0])
                return TestResultsView::render($result[1]);
            return MessageView::render('Тест не пройден', $validator->validate()[1]);
        }
        return "<p>Handler was not found</p>";
    }
}