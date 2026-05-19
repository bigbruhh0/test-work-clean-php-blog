<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\PostRepository;

class PostController
{
    public function __construct(
        private readonly View $view,
        private readonly PostRepository $posts
    ) {
    }

    public function show(Request $request): Response
    {
        $slug = (string) $request->route('slug', '');
        $post = $this->posts->findBySlugWithCategories($slug);

        if ($post === null) {
            return Response::html(render_not_found(), 404);
        }

        $this->posts->incrementViews((int) $post['id']);

        return Response::html($this->view->render('pages/post.tpl', [
            'pageTitle' => $post['title'],
            'post' => $post,
            'relatedPosts' => $this->posts->relatedPosts(
                (int) $post['id'],
                array_column($post['categories'], 'id')
            ),
        ]));
    }
}
