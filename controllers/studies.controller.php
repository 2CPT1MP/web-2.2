<?php require_once('../views/studies.view.php');

class StudiesController {
    public function showStudies(Studies $studies): string {
        return StudiesView::render($studies);
    }

    public function processRequest($request): string {
        if ($request->getMethod() === 'GET') {
            $student = new Student();
            return $this->showStudies($student->getStudies());
        }
        return "<p>Handler was not found</p>";
    }
}