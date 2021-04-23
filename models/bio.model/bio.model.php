<?php require_once('article.model.php');

class Bio {
    private array $articles = [];

    public function addArticle(string $title, string $text): void {
        $this->articles[] = new Article($title, $text);
    }

    public function getArticles(): array {
        return $this->articles;
    }
}