<?php require_once(__DIR__ . '/../header.view.php');

class BlogEditorView {
    public static function render(): string {
        $html = HeaderView::render('Редактор блога');
        $html .= '<section class="card">';

        /*
        $msgs = "<h2>Оставленные сообщения</h2><article class=''><table class='message-table'>";
        $msgs .= "<a href='/contact/messages'><b>Загрузить файл сообщений</b></a>";

        foreach ($messages as $message)
            $msgs .= "
               <div class='msg-block'>
                {$message->getSaveDate()}<br>
                <b>{$message->getName()}</b>
                &lt;<i>{$message->getEmail()}</i>&gt;
                <br>
                {$message->getMessage()}<br>
                <i>Телефон: {$message->getPhone()}</i>
                
               </div>
            ";

        $msgs .= "</table></article>";*/

        return $html . <<<EDITOR
            <article class="flex-container card">
            <h2>Добавить запись блога</h2>
                <form id="contact-form" action="/blog/editor" method=POST autocomplete="off" enctype="multipart/form-data">
                    <label for="topic" id="fio-label">Тема сообщения</label>
                    <input name="topic" id="topic" type="text" required autocomplete="off">

                    <label for="image" id="email-label">Прикрепить изображение</label>
                    <input name="image" id="image" type="file" accept="image/x-png,image/gif,image/jpeg">
                    
                    <label for="text">Текст сообщения</label>
                    <textarea id="text" name="text" rows="10" cols="50" required></textarea>
                    
                    <button id='submit-btn' type="submit" onsubmit="">Submit</button>
                </form>
            </article>
        EDITOR;
    }
}