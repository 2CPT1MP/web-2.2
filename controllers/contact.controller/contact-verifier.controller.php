<?php
require_once(__DIR__ . '/../../modules/form-validators/contact.validator.php');
require_once(__DIR__ . '/../../views/message.view.php');

class ContactVerifierController implements Controller {

    public function processRequest($request): string {
        if ($request->getMethod() === 'POST') {
            $validator = new ContactValidator($request->getBody());
            $result = $validator->validate();

            if ($result[0])
                return MessageView::render("Успешная проверка", "Вееденная информация верна");
            return MessageView::render('Проверка не прошла', $validator->validate()[1]);
        }
        return "<p>Handler was not found</p>";
    }
}