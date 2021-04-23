<?php require_once(__DIR__ . '/../../views/test.view.php');

class TestController implements Controller {
    public function showTest(Test $test): string {
        return TestView::render($test);
    }

    public function processRequest($request): string {
        if ($request->getMethod() === 'GET') {
            $student = new Student();
            return $this->showTest($student->getTest());
        }
        return "<p>Handler was not found</p>";
    }
}