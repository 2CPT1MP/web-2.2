<?php require_once('../controllers/test.controller/test.controller.php');

class TestRouter extends RootRouter {
    public function __construct() {
        $this->addController('/', new TestController());
        $this->addController('/verify', new TestVerifierController());
    }
}