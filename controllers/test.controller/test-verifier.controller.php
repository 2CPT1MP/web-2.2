<?php require_once('../modules/form-validators/examinee.validator.php');

class TestVerifierController {

    public function processRequest($request): string {
        if ($request->getMethod() === 'POST') {
            $validator = new ExamineeValidator($request->getBody());
            $result = $validator->validate();

            if ($result[0])
                return MessageView::render("Тест пройден", $validator->validate()[1]);
            return MessageView::render('Тест не пройден', $validator->validate()[1]);
        }
        return "<p>Handler was not found</p>";
    }
}