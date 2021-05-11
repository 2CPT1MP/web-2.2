<?php
require_once(__DIR__ . "/../../core/routing/controller.core.php");
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
        //
        //$newBlogMessage->setImagePath("");
        $savedSuccessfully = $newBlogMessage->save();

        if (!$savedSuccessfully)
            return MessageView::render("Ошибка", "Ошибка при попытке сохранения сообщения");
        return MessageView::render("Успешное добавление", "Успешное добавление нового сообщения блога");
    }
}