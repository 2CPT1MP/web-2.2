<?php
require_once('../controllers/blog.controller.php/blog-editor.controller.php');

class BlogRouter extends Router {
    public function __construct() {
        //$this->addController('/', new Controller());
        $this->addController('/editor', new BlogEditorController());
    }
}