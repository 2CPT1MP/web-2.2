<?php
require_once('../core/request.core.php');

require_once('../core/router.core.php');
require_once('../routes/contact.route.php');
require_once('../routes/test.route.php');

require_once('../controllers/index.controller.php');
require_once('../controllers/history.controller.php');
require_once('../controllers/bio.controller.php');
require_once('../controllers/interests.controller.php');
require_once('../controllers/studies.controller.php');
require_once('../controllers/photos.controller.php');
require_once('../controllers/test.controller/test-verifier.controller.php');
require_once('../core/active-record.core.php');

$request = new Request();
$rootRouter = new Router();
ActiveRecord::connect();

$rootRouter->addRouter("/contact", new ContactRouter());
$rootRouter->addRouter("/test", new TestRouter());

$rootRouter->addController('/', new IndexController());
$rootRouter->addController("/bio", new BioController());
$rootRouter->addController("/interests", new InterestsController());
$rootRouter->addController("/studies", new StudiesController());
$rootRouter->addController("/photos", new PhotosController());
$rootRouter->addController('/history', new HistoryController());

$res = $rootRouter->processRequest($request);
echo $res;

require_once("../models/test.model/result.model.php");
require_once("../models/test.model/test.model.php");
require_once("../models/test.model/answer.model.php");
require_once("../models/test.model/test-question.model.php");


if (count(Test::findAll()) < 1) {
    $test = new Test("Тест 1");
    $q1 = new TestQuestion("JS расшифровывается как");
    $q1->addRightAnswer(new Answer("JavaScript"));
    $q1->addWrongAnswer(new Answer("JackScript", "WRONG"));
    $test->addTestQuestion($q1);

    $q2 = new TestQuestion("Допустимые имена переменных", "MULTIPLE_SELECT");
    $q2->addRightAnswer(new Answer("var1"));
    $q2->addRightAnswer(new Answer("_var1"));
    $q2->addWrongAnswer(new Answer("1_var", "WRONG"));
    $test->addTestQuestion($q2);

    $q3 = new TestQuestion("PC расшифровывается как", "RADIO");
    $q3->addRightAnswer(new Answer("Personal Computer"));
    $q3->addWrongAnswer(new Answer("Personal Calculator", "WRONG"));
    $test->addTestQuestion($q3);

    $q4 = new TestQuestion("SQL расшифровывается как", "TEXT");
    $q4->addRightAnswer(new Answer("Structured Query Language"));
    $test->addTestQuestion($q4);

    $test->save();
}