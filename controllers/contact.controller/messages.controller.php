<?php
require_once(__DIR__ . "/../../core/controller.core.php");
require_once(__DIR__ . "/../../views/upload-messages.view.php");

class MessagesController implements Controller {
    public function showUploadMessagesForm(): string {
        return UploadMessagesView::render();
    }

    private function validateMessagesFile(string $fileName) {
        $lines = file($fileName, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);

        if (!$lines || count($lines) < 1)
            return false;

        foreach ($lines as $line) {

            $fields = explode(';', $line);
            if (count($fields) !== 9)
                return false;

            if (!preg_match('/^\d{1,2}\.\d{1,2}\.\d{4}\s\d{2}:\d{2}:\d{2}$/u', $fields[0]) ||
                !preg_match('/^[А-ЯA-Z]([а-яa-z])+\s[А-ЯA-Z]([а-яa-z])+\s[А-ЯA-Z]([а-яa-z])+$/u', $fields[1]) ||
                $fields[2] !== 'Мужской' && $fields[2] !== 'Женский' ||
                !preg_match('/^[A-Za-z0-9_]+[@][A-Za-z0-9_]+[.][A-Za-z0-9_]+$/u', $fields[3]) ||
                !preg_match('/^[+][7|3][0-9]{9,11}$/u', $fields[4]) ||
                !checkdate($fields[6], $fields[5], $fields[7])
            ) return false;
        }
        return true;
    }

    private function uploadMessagesFile(): bool {
        if (!isset($_FILES["messages"]) || !isset($_FILES["messages"]["tmp_name"]))
            return false;
        $file = $_FILES["messages"]["tmp_name"];

        if (!$this->validateMessagesFile($file))
            return false;

        return copy($file, __DIR__ . '/../../files/messages.inc');
    }

    public function processRequest($request): string {
        if ($request->getMethod() === 'GET')
            return $this->showUploadMessagesForm();

        if ($request->getMethod() === 'POST') {
            if (!$this->uploadMessagesFile())
                return MessageView::render("Ошибка", "При попытке загрузки файла произошла ошибка");
            return MessageView::render("Файл загружен", "Файл был успешно загружен");
        }
        return "<p>Handler was not found</p>";
    }
}