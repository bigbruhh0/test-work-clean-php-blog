<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\Services\ImageUploader;

class ManageController
{
    public function __construct(
        private readonly CategoryRepository $categories,
        private readonly PostRepository $posts,
        private readonly ImageUploader $images
    ) {
    }

    public function storeCategory(Request $request): Response
    {
        $name = trim((string) $request->input('name', ''));
        $description = trim((string) $request->input('description', ''));

        if ($name === '') {
            return Response::redirect('/');
        }

        $category = $this->categories->create($name, $description);

        return Response::redirect('/category/' . $category['slug']);
    }

    public function storePost(Request $request): Response
    {
        $title = trim((string) $request->input('title', ''));
        $description = trim((string) $request->input('description', ''));
        $content = trim((string) $request->input('content', ''));
        $categoryIds = $request->input('category_ids', []);

        if ($title === '' || $description === '' || $content === '' || !is_array($categoryIds) || $categoryIds === []) {
            return Response::redirect('/');
        }

        $image = $this->images->upload($request->file('image'), $title);
        $post = $this->posts->create([
            'title' => $title,
            'image' => $image,
            'description' => $description,
            'content' => $content,
            'published_at' => date('Y-m-d H:i:s'),
        ], $categoryIds);

        return Response::redirect('/post/' . $post['slug']);
    }
}
