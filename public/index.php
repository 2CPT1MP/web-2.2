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

/*
require_once("../models/test.model/answer.model.php");
$newAnswer = Answer::findAll();
var_dump($newAnswer);
*/

require_once("../models/test.model/test-question.model.php");

$s1 = TestQuestion::findById(2);


