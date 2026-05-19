<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class CategoryRepository
{
    public function __construct(
        private readonly Database $database
    ) {
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM categories WHERE slug = :slug LIMIT 1');
        $statement->execute(['slug' => $slug]);

        $category = $statement->fetch();

        return $category ?: null;
    }

    public function withLatestPosts(int $limit = 3): array
    {
        $statement = $this->connection()->query(
            'SELECT c.*
            FROM categories c
            WHERE EXISTS (
                SELECT 1 FROM post_category pc WHERE pc.category_id = c.id
            )
            ORDER BY c.name ASC'
        );

        $categories = $statement->fetchAll();

        foreach ($categories as $index => $category) {
            $categories[$index]['posts'] = $this->latestPosts((int) $category['id'], $limit);
        }

        return $categories;
    }

    private function latestPosts(int $categoryId, int $limit): array
    {
        $statement = $this->connection()->prepare(
            'SELECT p.*
            FROM posts p
            INNER JOIN post_category pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id
            ORDER BY p.published_at DESC
            LIMIT :limit'
        );

        $statement->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    private function connection(): PDO
    {
        return $this->database->connection();
    }
}
