<?php
require_once(__DIR__ . "/../../core/routing/controller.core.php");
require_once(__DIR__ . "/../../core/io/file-uploader.core.php");
require_once(__DIR__ . "/../../modules/form-validators/blog-message.validator.php");
require_once(__DIR__ . "/../../views/blog/blog.view.php");
require_once(__DIR__ . "/../../models/blog-message.model.php");

class BlogController extends RestController {

    public function GET(Request $request): string {
        $page = 1;
        $recordsPerPage = 2;
        $pageCountLimit = 10;

        if (isset($request->getParams()["page"]))
            $page = $request->getParams()["page"];

        if (isset($request->getParams()["recordsPerPage"]))
            $recordsPerPage = $request->getParams()["recordsPerPage"];

        if (isset($request->getParams()["pageCountLimit"]))
            $pageCountLimit = $request->getParams()["pageCountLimit"];

        $pageCount = BlogMessage::getPageCount($recordsPerPage);
        $recordCount = BlogMessage::getCount();

        if ($pageCount > $pageCountLimit) {
            $recordsPerPage = intval(ceil($recordCount / $pageCountLimit));
            $pageCount = BlogMessage::getPageCount($recordsPerPage);
        }

        $blogMessages = BlogMessage::findAllForPage($page, $recordsPerPage);
        return BlogView::render($blogMessages, $pageCount, $page);
    }

    public function POST(Request $request): string {
        /*$messageBody = $request->getBody();
        $validator = new BlogMessageValidator();
        $validationResult = $validator->validate($messageBody);

        if (!$validationResult->isValid())
            return MessageView::render("Ошибка", $validationResult->getErrorMessage());

        $newBlogMessage = new BlogMessage();
        $newBlogMessage->setTopic($messageBody["topic"]);
        $newBlogMessage->setText($messageBody["text"]);

        if ($messageBody["image"] && $messageBody["image"]["size"] !== 0) {
            $uniqueId = uniqid();
            $uploadInfo = FileUploader::uploadFile("image", "../files/userImages/$uniqueId.png");
            if ($uploadInfo->isSuccessful())
                $newBlogMessage->setImagePath($uniqueId);
            else
                return MessageView::render("Ошибка", $validationResult->getErrorMessage());
        }
        $savedSuccessfully = $newBlogMessage->save();

        if (!$savedSuccessfully)
            return MessageView::render("Ошибка", $validationResult->getErrorMessage());
        return MessageView::render("Успешное добавление", "Успешное добавление нового сообщения блога");*/
        return "w";
    }
}