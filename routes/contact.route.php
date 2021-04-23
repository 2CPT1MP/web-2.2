<?php
require_once('../controllers/contact.controller/contact.controller.php');
require_once('../controllers/contact.controller/contact-verifier.controller.php');

class ContactRouter extends RootRouter {
    public function __construct() {
        $this->addController('/', new ContactController());
        $this->addController('/verify', new ContactVerifierController());
    }
}