<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;

class PostController
{
    public function __construct(
        private readonly View $view
    ) {
    }

    public function show(Request $request): Response
    {
        $slug = (string) $request->route('slug', '');

        return Response::html($this->view->render('pages/post.tpl', [
            'pageTitle' => 'Post',
            'post' => [
                'title' => ucwords(str_replace('-', ' ', $slug)),
                'description' => 'Post page is ready for the next step.',
            ],
        ]));
    }
}

