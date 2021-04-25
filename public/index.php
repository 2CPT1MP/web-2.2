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

//var_dump('<pre>', Answer::findAll(), '</pre>');

require_once("../models/test.model/test.model.php");
$t = Test::findById(1);
$t->delete();

var_dump(Test::findAll());