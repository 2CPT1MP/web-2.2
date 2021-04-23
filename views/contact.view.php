<?php require_once('header.view.php');

class ContactView {
    public static function render(): string {
        $html = HeaderView::render('Контакт');
        $html .= '<section class="card">';

        return $html . <<<CONTACT
            <article class="flex-container card">
                <form id="contact-form" action="/contact/verify" method=POST autocomplete="off">
                    <label id="fio-label">Ваше ФИО
                        <input name="sender-name" type="text" required autocomplete="off">
                    </label>
                    <label>Пол<br>
                        <label>Мужской
                            <input name="sender-gender" type="radio" value="Мужской" required>
                        </label>
                        <label>Женский
                            <input name="sender-gender" type="radio" value="Женский" required>
                        </label>
                    </label>
                    <br>
                        <label>Месяц рождения
                            <select name="sender-month" required>
                                <option value="0">Январь</option>
                                <option value="1">Февраль</option>
                                <option value="2">Март</option>
                                <option value="3">Апрель</option>
                                <option value="4">Май</option>
                                <option value="5">Июнь</option>
                                <option value="6">Июль</option>
                                <option value="7">Август</option>
                                <option value="8">Сентябрь</option>
                                <option value="9">Октябрь</option>
                                <option value="10">Ноябрь</option>
                                <option value="11">Декабрь</option>
                            </select>
                        </label>
                        <label> Год рождения
                            <input name="sender-year" type="number" min="1910" max="2005" value="2000" required>
                        </label>
                        <label> День рождения
                            <input name="sender-day" type="number" required readonly>
                        </label>
                        <div class="calendar" id="dob-calendar">
                            <div class="calendar-header">
                                <div>Пн</div><div>Вт</div><div>Ср</div><div>Чт</div><div>Пт</div><div>Сб</div><div>Вс</div>
                            </div>
                            <div class="calendar-data calendar-header" id="calendar-root"></div>
                        </div>
                    <label id="email-label">Email
                        <input name="sender-email" id="sender-email" type="email" required autocomplete="off">
                    </label>
                    <label id="phone-label">Телефон
                        <input name="sender-phone" type="tel" required autocomplete="off">
                    </label>
                    <label>Сообщение
                        <textarea name="sender-msg" rows="10" cols="50" required>&nbsp;</textarea>
                    </label>
                    <button id='submit-btn' type="submit" onsubmit="">Submit</button>
                </form>
            </article>
        CONTACT;
    }
}