<?php require_once('url-parser.php');

class Request {
    private $method, $path, $params, $body;

    public function __construct() {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->setUrl($_SERVER["REQUEST_URI"]);
        $this->body = $_POST;
    }

    public function getMethod() {
        return $this->method;
    }

    public function shift(): void {
        array_shift($this->path);
        if (!$this->path)
            $this->path[] = "";
    }

    public function setUrl($url): void {
        $result = UrlParser::parseRequestUrl($url);
        $this->path = $result[0];
        $this->params = $result[1];
    }

    public function getParams() {
        return $this->params;
    }

    public function getPath() {
        return $this->path;
    }

    public function getBody(): array {
        return $this->body;
    }
}