<?php require_once('header.view.php');

class UploadMessagesView {
    public static function render(): string {
        $html = HeaderView::render('Загрузка сообщений');
        $html .= '<section class="card">';
        return $html . <<<MESSAGES
            <h2>Загрузить файл сообщений</h2>
            <article class="flex-container card">
                <form id="contact-form" action="/contact/messages" method=POST autocomplete="off" enctype="multipart/form-data">
                    <label id="file-label">Выберите файл сообщений<br>
                        <input name="messages" type="file" required autocomplete="off" accept=".inc">
                    </label>
                   
                    <button id='submit-btn' type="submit" onsubmit="">Submit</button>
                </form>
            </article>
        MESSAGES;
    }
}