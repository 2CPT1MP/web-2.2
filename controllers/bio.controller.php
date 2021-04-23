<?php require_once('../views/bio.view.php');

class BioController implements Controller {
    public function showBio(Bio $bio): string {
        return BioView::render($bio);
    }

    public function processRequest($request): string {
        if ($request->getMethod() === 'GET') {
            $student = new Student();
            return $this->showBio($student->getBio());
        }
        return "<p>Handler was not found</p>";
    }
}