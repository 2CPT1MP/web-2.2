<?php require_once(__DIR__ . '/../../modules/form-validators/examinee.validator.php');

class TestVerifierController implements Controller {

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