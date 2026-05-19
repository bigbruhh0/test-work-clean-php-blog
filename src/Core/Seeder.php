<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

class Seeder
{
    public function __construct(
        private readonly Database $database
    ) {
    }

    public function run(): void
    {
        $connection = $this->database->connection();
        $connection->beginTransaction();

        try {
            $this->clear($connection);
            $categories = $this->seedCategories($connection);
            $this->seedPosts($connection, $categories);
            $connection->commit();
        } catch (\Throwable $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    private function clear(PDO $connection): void
    {
        $connection->exec('DELETE FROM post_category');
        $connection->exec('DELETE FROM posts');
        $connection->exec('DELETE FROM categories');
    }

    private function seedCategories(PDO $connection): array
    {
        $items = [
            [
                'name' => 'Travel',
                'slug' => 'travel',
                'description' => 'Places, routes and short notes from city weekends.',
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'description' => 'Simple ideas for daily routines, planning and free time.',
            ],
            [
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'Recipes, cafe notes and small kitchen experiments.',
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'Visual details, interiors and practical inspiration.',
            ],
        ];

        $statement = $connection->prepare(
            'INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)'
        );

        $categories = [];

        foreach ($items as $item) {
            $statement->execute($item);
            $item['id'] = (int) $connection->lastInsertId();
            $categories[$item['slug']] = $item;
        }

        return $categories;
    }

    private function seedPosts(PDO $connection, array $categories): void
    {
        $posts = [
            [
                'title' => 'Quiet morning in the old town',
                'slug' => 'quiet-morning-in-the-old-town',
                'image' => 'posts/post-1.svg',
                'description' => 'A short walking route through narrow streets before the city wakes up.',
                'content' => $this->content('A good city walk starts early, when cafes are still opening and the streets are almost empty. It is the easiest time to notice small signs, old doors and quiet courtyards.'),
                'views' => 42,
                'published_at' => '2026-05-10 09:00:00',
                'categories' => ['travel', 'lifestyle'],
            ],
            [
                'title' => 'Simple desk setup for focused work',
                'slug' => 'simple-desk-setup-for-focused-work',
                'image' => 'posts/post-2.svg',
                'description' => 'Several practical details that make a home workspace easier to use.',
                'content' => $this->content('A comfortable desk does not need many things. A clear surface, a warm lamp and a notebook nearby are often enough to make the workday feel organized.'),
                'views' => 76,
                'published_at' => '2026-05-12 12:30:00',
                'categories' => ['lifestyle', 'design'],
            ],
            [
                'title' => 'Coffee notes for slow weekends',
                'slug' => 'coffee-notes-for-slow-weekends',
                'image' => 'posts/post-3.svg',
                'description' => 'A small guide to choosing beans, grind size and a relaxed brewing pace.',
                'content' => $this->content('Weekend coffee is less about speed and more about rhythm. Fresh beans, clean water and a steady pour can change a familiar cup completely.'),
                'views' => 128,
                'published_at' => '2026-05-14 08:15:00',
                'categories' => ['food', 'lifestyle'],
            ],
            [
                'title' => 'How to plan a light city trip',
                'slug' => 'how-to-plan-a-light-city-trip',
                'image' => 'posts/post-4.svg',
                'description' => 'A compact checklist for a two-day trip without turning it into a schedule.',
                'content' => $this->content('The best short trip has one anchor point per day. Choose a district, one place to eat and enough empty time between them.'),
                'views' => 35,
                'published_at' => '2026-05-15 10:45:00',
                'categories' => ['travel'],
            ],
            [
                'title' => 'Color accents in a small room',
                'slug' => 'color-accents-in-a-small-room',
                'image' => 'posts/post-5.svg',
                'description' => 'How small color choices can change the mood of a compact interior.',
                'content' => $this->content('A small room reacts strongly to color. One chair, a poster or a textile detail can be enough when the rest of the space stays calm.'),
                'views' => 89,
                'published_at' => '2026-05-16 14:00:00',
                'categories' => ['design'],
            ],
            [
                'title' => 'Fresh lunch with seasonal vegetables',
                'slug' => 'fresh-lunch-with-seasonal-vegetables',
                'image' => 'posts/post-6.svg',
                'description' => 'A quick lunch idea built around vegetables, herbs and a simple sauce.',
                'content' => $this->content('Seasonal vegetables need very little help. A hot pan, a bright sauce and fresh herbs turn a quick lunch into something balanced.'),
                'views' => 54,
                'published_at' => '2026-05-17 13:20:00',
                'categories' => ['food'],
            ],
            [
                'title' => 'A calm evening route by the river',
                'slug' => 'a-calm-evening-route-by-the-river',
                'image' => 'posts/post-1.svg',
                'description' => 'A route for sunset light, open views and a slower end to the day.',
                'content' => $this->content('River routes work well in the evening because the view keeps changing. Bridges, reflections and open air make the walk feel longer than it is.'),
                'views' => 63,
                'published_at' => '2026-05-18 18:10:00',
                'categories' => ['travel', 'design'],
            ],
            [
                'title' => 'Kitchen shelf ideas that stay useful',
                'slug' => 'kitchen-shelf-ideas-that-stay-useful',
                'image' => 'posts/post-3.svg',
                'description' => 'Open shelves can work well when the most used things are placed first.',
                'content' => $this->content('Useful shelves are edited shelves. Keep the daily objects close, group similar items together and leave enough empty space to make cleaning simple.'),
                'views' => 101,
                'published_at' => '2026-05-19 11:40:00',
                'categories' => ['food', 'design'],
            ],
        ];

        $postStatement = $connection->prepare(
            'INSERT INTO posts (title, slug, image, description, content, views, published_at)
            VALUES (:title, :slug, :image, :description, :content, :views, :published_at)'
        );

        $categoryStatement = $connection->prepare(
            'INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)'
        );

        foreach ($posts as $post) {
            $postStatement->execute([
                'title' => $post['title'],
                'slug' => $post['slug'],
                'image' => $post['image'],
                'description' => $post['description'],
                'content' => $post['content'],
                'views' => $post['views'],
                'published_at' => $post['published_at'],
            ]);

            $postId = (int) $connection->lastInsertId();

            foreach ($post['categories'] as $categorySlug) {
                $categoryStatement->execute([
                    'post_id' => $postId,
                    'category_id' => $categories[$categorySlug]['id'],
                ]);
            }
        }
    }

    private function content(string $intro): string
    {
        return $intro . "\n\n" .
            'The idea is to keep the details practical and easy to repeat. A small habit, a short route or one clear choice can make the whole day feel more intentional.' . "\n\n" .
            'This note keeps the focus on simple observations instead of complicated rules. That is usually enough for a useful blog post.';
    }
}
