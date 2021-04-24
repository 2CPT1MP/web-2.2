<?php
require_once(__DIR__ . "/../../core/controller.core.php");
require_once(__DIR__ . "/../../views/contact.view.php");

class ContactController implements Controller {
    public function showContact(array $messages): string {
        return ContactView::render($messages);
    }

    public function processRequest($request): string {
        if ($request->getMethod() === 'GET') {
            return $this->showContact(GuestBookItem::findAll());
        }
        return "<p>Handler was not found</p>";
    }
}