<?php

class Article {
    private string $heading, $text;

    public function __construct(string $heading, string $text) {
        $this->heading = $heading;
        $this->text = $text;
    }

    public function getHeading(): string {
        return $this->heading;
    }

    public function getText(): string {
        return $this->text;
    }
}