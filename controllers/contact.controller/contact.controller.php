<?php
require_once(__DIR__ . "/../../core/controller.core.php");
require_once(__DIR__ . "/../../views/contact.view.php");

class ContactController implements Controller {
    public function showContact(): string {
        return ContactView::render();
    }

    public function processRequest($request): string {
        if ($request->getMethod() === 'GET') {
            $student = new Student();
            return $this->showContact();
        }
        return "<p>Handler was not found</p>";
    }
}