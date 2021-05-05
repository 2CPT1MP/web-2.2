<?php require_once(__DIR__ . '/../../modules/form-validators/examinee.validator.php');
require_once(__DIR__ . '/../../views/test-results.view.php');

class TestResultController implements Controller {
    public function processRequest($request): string {
        if ($request->getMethod() === 'GET') {
            $result = Result::findById($request->getParams()["id"]);
            //$test = Test::findById($request->getParams()["id"]);
            //var_dump($result);
            if ($result)
                return TestResultsView::render($result);
        }
        return "<p>Handler was not found</p>";
    }
}