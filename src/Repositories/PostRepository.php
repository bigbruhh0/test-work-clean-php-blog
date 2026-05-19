<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Services\ImageUrlResolver;
use PDO;

class PostRepository
{
    public function __construct(
        private readonly Database $database,
        private readonly ImageUrlResolver $images
    ) {
    }

    public function countByCategory(int $categoryId): int
    {
        $statement = $this->connection()->prepare(
            'SELECT COUNT(*)
            FROM posts p
            INNER JOIN post_category pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id'
        );

        $statement->execute(['category_id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }

    public function findByCategory(int $categoryId, string $sort, int $page, int $perPage): array
    {
        $orderBy = $sort === 'views'
            ? 'p.views DESC, p.published_at DESC'
            : 'p.published_at DESC';

        $offset = max(0, ($page - 1) * $perPage);

        $statement = $this->connection()->prepare(
            "SELECT p.*
            FROM posts p
            INNER JOIN post_category pc ON pc.post_id = p.id
            WHERE pc.category_id = :category_id
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset"
        );

        $statement->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue('limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $this->withImageUrls($statement->fetchAll());
    }

    public function findBySlugWithCategories(string $slug): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM posts WHERE slug = :slug LIMIT 1');
        $statement->execute(['slug' => $slug]);

        $post = $statement->fetch();

        if (!$post) {
            return null;
        }

        $post['categories'] = $this->categoriesForPost((int) $post['id']);
        $post = $this->withImageUrl($post);

        return $post;
    }

    public function incrementViews(int $postId): void
    {
        $statement = $this->connection()->prepare('UPDATE posts SET views = views + 1 WHERE id = :id');
        $statement->execute(['id' => $postId]);
    }

    public function relatedPosts(int $postId, array $categoryIds, int $limit = 3): array
    {
        if ($categoryIds === []) {
            return [];
        }

        $placeholders = [];
        $params = ['post_id' => $postId];

        foreach (array_values($categoryIds) as $index => $categoryId) {
            $key = 'category_' . $index;
            $placeholders[] = ':' . $key;
            $params[$key] = (int) $categoryId;
        }

        $statement = $this->connection()->prepare(
            'SELECT p.*, COUNT(pc.category_id) AS shared_categories
            FROM posts p
            INNER JOIN post_category pc ON pc.post_id = p.id
            WHERE p.id != :post_id
                AND pc.category_id IN (' . implode(', ', $placeholders) . ')
            GROUP BY p.id
            ORDER BY shared_categories DESC, p.published_at DESC
            LIMIT :limit'
        );

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value, PDO::PARAM_INT);
        }

        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $this->withImageUrls($statement->fetchAll());
    }

    private function categoriesForPost(int $postId): array
    {
        $statement = $this->connection()->prepare(
            'SELECT c.*
            FROM categories c
            INNER JOIN post_category pc ON pc.category_id = c.id
            WHERE pc.post_id = :post_id
            ORDER BY c.name ASC'
        );

        $statement->execute(['post_id' => $postId]);

        return $statement->fetchAll();
    }

    private function connection(): PDO
    {
        return $this->database->connection();
    }

    private function withImageUrls(array $posts): array
    {
        foreach ($posts as $index => $post) {
            $posts[$index] = $this->withImageUrl($post);
        }

        return $posts;
    }

    private function withImageUrl(array $post): array
    {
        $post['image_url'] = $this->images->resolve($post['image'] ?? null);

        return $post;
    }
}
