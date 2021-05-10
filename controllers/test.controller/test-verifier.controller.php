<?php require_once(__DIR__ . '/../../modules/form-validators/examinee.validator.php');
require_once(__DIR__ . '/../../views/test/test-results.view.php');

class TestVerifierController implements Controller {
    public function processRequest($request): string {
        if ($request->getMethod() === 'POST') {
            $validator = new ExamineeValidator($request->getBody());
            $res = $validator->validate();

            if (!$res[0])
                return MessageView::render('Тест не пройден', $res[1]);

            $result = $validator->verifyResults($request->getBody());
            $result->save();

            return MessageView::render("Проверка теста", "<a href='/test/result?id={$result->getId()}'>Просмотреть результат теста</a>" . $res[1]);
        }

        return MessageView::render("Ошибка", "Неверное использование валидатора теста");
    }
}