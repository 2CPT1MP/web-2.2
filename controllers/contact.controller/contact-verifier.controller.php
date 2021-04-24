<?php
require_once(__DIR__ . '/../../modules/form-validators/contact.validator.php');
require_once(__DIR__ . '/../../views/message.view.php');
require_once(__DIR__ . '/../../models/guest-book-item.model.php');

class ContactVerifierController implements Controller {
    public function processRequest($request): string {
        if ($request->getMethod() === 'POST') {
            $body = $request->getBody();
            $validator = new ContactValidator($body);
            $result = $validator->validate();

            if ($result[0]) {
                $data = new GuestBookItem();
                $data->setName($body["sender-name"]);
                $data->setGender($body["sender-gender"]);

                $data->setDayOfBirth(intval($body["sender-day"]));
                $data->setMonthOfBirth(intval($body["sender-month"]));
                $data->setYearOfBirth(intval($body["sender-year"]));

                $data->setEmail($body["sender-email"]);
                $data->setPhone($body["sender-phone"]);
                $data->setMessage($body["sender-msg"]);

                $data->save();
                header("Location: /contact");
                return MessageView::render("Успешная проверка", "Введенная информация верна");
            }
            return MessageView::render('Проверка не прошла', $validator->validate()[1]);
        }
        return "<p>Handler was not found</p>";
    }
}