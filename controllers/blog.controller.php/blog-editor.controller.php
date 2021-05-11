<?php
require_once(__DIR__ . "/../../core/routing/controller.core.php");
require_once(__DIR__ . "/../../core/io/file-uploader.core.php");
require_once(__DIR__ . "/../../modules/form-validators/blog-message.validator.php");
require_once(__DIR__ . "/../../views/blog/blog-editor.view.php");
require_once(__DIR__ . "/../../models/blog-message.model.php");

class BlogEditorController extends RestController {

    public function GET(Request $request): string {
        return BlogEditorView::render();
    }

    public function POST(Request $request): string {
        $messageBody = $request->getBody();
        $validator = new BlogMessageValidator();
        $validationResult = $validator->validate($messageBody);

        if (!$validationResult->isValid())
            return MessageView::render("Ошибка", $validationResult->getErrorMessage());

        $newBlogMessage = new BlogMessage();
        $newBlogMessage->setTopic($messageBody["topic"]);
        $newBlogMessage->setText($messageBody["text"]);

        if ($messageBody["image"]) {
            $uniqueId = uniqid();
            $uploadInfo = FileUploader::uploadFile("image", "../files/userImages/$uniqueId.png");
            if ($uploadInfo->isSuccessful())
                $newBlogMessage->setImagePath($uploadInfo->getServerFilepath());
            else
                return MessageView::render("Ошибка", $validationResult->getErrorMessage());
        }
        $savedSuccessfully = $newBlogMessage->save();

        if (!$savedSuccessfully)
            return MessageView::render("Ошибка", $validationResult->getErrorMessage());
        return MessageView::render("Успешное добавление", "Успешное добавление нового сообщения блога");
    }
}