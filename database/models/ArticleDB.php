<?php

$pdo = require_once './database/database.php';

class ArticleDB
{
    private PDO $pdo;
    private PDOStatement $statementGetAll;
    private PDOStatement $statementGetOne;
    private PDOStatement $statementUpdate;
    private PDOStatement $statementInsert;
    private PDOStatement $statementDelete;

    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->statementGetAll = $this->pdo->prepare('SELECT * FROM articles');

        $this->statementGetOne = $this->pdo->prepare('SELECT * FROM articles WHERE id = :id');

        $this->statementUpdate = $this->pdo->prepare("UPDATE articles SET 
        title = :title,
        image = :image, 
        category = :category,
        content = :content
        WHERE id = :id
        ");

        $this->statementInsert = $this->pdo->prepare("INSERT INTO articles 
        (title, image, category,content) VALUES (
            :title,
            :image, 
            :category, 
            :content
        )");

        $this->statementDelete = $this->pdo->prepare('DELETE FROM articles WHERE id = :id');
    }

    public function fetchAll()
    {
        $this->statementGetAll->execute();
        return $this->statementGetAll->fetchAll();
    }
    public function fetchOne(int $id)
    {
        $this->statementGetOne->bindValue(':id', $id);
        $this->statementGetOne->execute();
        return $this->statementGetOne->fetch();
    }

    public function createOne($article)
    {
        $this->statementInsert->bindValue(':title', $article['title']);
        $this->statementInsert->bindValue(':image',  $article['image']);
        $this->statementInsert->bindValue(':category',  $article['category']);
        $this->statementInsert->bindValue(':content',  $article['content']);
        $this->statementInsert->execute();
    }

    public function updateOne($article)
    {
        $this->statementUpdate->bindValue(':id', $article['id']);
        $this->statementUpdate->bindValue(':title', $article['title']);
        $this->statementUpdate->bindValue(':image',  $article['image']);
        $this->statementUpdate->bindValue(':category',  $article['category']);
        $this->statementUpdate->bindValue(':content',  $article['content']);
        $this->statementUpdate->execute();
    }

    public function deleteOne(int $id)
    {
        $this->statementDelete->bindValue(':id', $id);
        $this->statementDelete->execute();
    }
}

return new ArticleDB($pdo);
