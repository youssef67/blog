<?php
class ArticleDB
{
    private PDO $pdo;
    private PDOStatement $statementGetAll;
    private PDOStatement $statementGetUserArticles;
    private PDOStatement $statementGetOne;
    private PDOStatement $statementUpdate;
    private PDOStatement $statementInsert;
    private PDOStatement $statementDelete;

    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->statementGetAll = $this->pdo->prepare('SELECT articles.*, user.firstname, user.lastname FROM articles 
                                                    LEFT JOIN user ON articles.author=user.id');

        $this->statementGetUserArticles = $this->pdo->prepare('SELECT * FROM articles WHERE author = :idAuthor');

        $this->statementGetOne = $this->pdo->prepare('SELECT articles.*, user.firstname, user.lastname FROM articles LEFT JOIN user ON articles.author=user.id WHERE articles.id = :id');

        $this->statementUpdate = $this->pdo->prepare("UPDATE articles SET 
        title = :title,
        image = :image, 
        category = :category,
        content = :content,
        author = :author
        WHERE id = :id
        ");

        $this->statementInsert = $this->pdo->prepare("INSERT INTO articles 
        (title, image, category,content, author) VALUES (
            :title,
            :image, 
            :category, 
            :content,
            :author
        )");

        $this->statementDelete = $this->pdo->prepare('DELETE FROM articles WHERE id = :id');
    }

    public function fetchAll()
    {
        $this->statementGetAll->execute();
        return $this->statementGetAll->fetchAll();
    }

    public function fetchAllUserArticles($id)
    {
        $this->statementGetUserArticles->bindValue(':idAuthor', $id);
        $this->statementGetUserArticles->execute();
        return $this->statementGetUserArticles->fetchAll();
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
        $this->statementInsert->bindValue(':author',  $article['author']);
        $this->statementInsert->execute();
    }

    public function updateOne($article)
    {
        $this->statementUpdate->bindValue(':id', $article['id']);
        $this->statementUpdate->bindValue(':title', $article['title']);
        $this->statementUpdate->bindValue(':image',  $article['image']);
        $this->statementUpdate->bindValue(':category',  $article['category']);
        $this->statementUpdate->bindValue(':content',  $article['content']);
        $this->statementUpdate->bindValue(':author',  $article['author']);
        $this->statementUpdate->execute();
    }

    public function deleteOne(int $id)
    {
        $this->statementDelete->bindValue(':id', $id);
        $this->statementDelete->execute();
    }
}

return new ArticleDB($pdo);
