<?php require_once('../views/photos.view.php');

class PhotosController {
    public function showPhotos(array $photos): string {
        return PhotosView::render($photos);
    }

    public function processRequest($request): string {
        if ($request->getMethod() === 'GET') {
            if (isset($request->getParams()["id"])) {
                header('Content-Type: image/png');
                readfile("../images/backgrounds/{$request->getParams()["id"]}.png");
                return "";
            }

            $student = new Student();
            return $this->showPhotos($student->getPhotos());
        }
        return "<p>Handler was not found</p>";
    }
}