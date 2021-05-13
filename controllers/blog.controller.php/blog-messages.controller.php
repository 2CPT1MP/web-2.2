<?php
require_once(__DIR__ . "/../../core/routing/controller.core.php");
require_once(__DIR__ . "/../../core/io/file-uploader.core.php");
require_once(__DIR__ . "/../../core/io/file-reader.core.php");
require_once(__DIR__ . "/../../views/blog/upload-blog-messages.view.php");

class BlogMessagesController extends RestController {

    private function validateMessagesFile(string $fileName): bool {
        $lines = file($fileName, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        $emptyFile = !$lines || count($lines) < 1;

        if ($emptyFile)
            return false;

        foreach ($lines as $line) {
            $fields = explode(',', $line);
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

    private function submitRecords(): bool {
        $fileNotAttached = !isset($_FILES["blog-data"]) || !isset($_FILES["blog-data"]["tmp_name"]);
        if ($fileNotAttached)
            return false;

        $file = $_FILES["blog-data"]["tmp_name"];
        /*
        if (!$this->validateMessagesFile($file))
            return false;*/

        $fileReader = new CSVFileReader($file);
        BlogMessage::deleteAll();

        foreach ($fileReader->read() as $record) {
            $newBlogMessage = new BlogMessage();
            $newBlogMessage->setTopic($record["topic"]);
            $newBlogMessage->setText($record["text"]);
            $newBlogMessage->setImagePath($record["imagePath"]);
            $newBlogMessage->setTimestamp($record["timestamp"]);
            $newBlogMessage->save();
        }

        return true;
    }

    public function GET(Request $request): string {
        return UploadBlogMessagesView::render();
    }

    public function POST(Request $request): string {
        if (!$this->submitRecords())
            return MessageView::render("Ошибка", "При попытке загрузки файла произошла ошибка");
        return MessageView::render("Файл загружен", "Файл был успешно загружен");
    }
}