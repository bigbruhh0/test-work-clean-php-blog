<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\CategoryRepository;

class HomeController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categories
    ) {
    }

    public function index(Request $request): Response
    {
        return Response::html($this->view->render('pages/home.tpl', [
            'pageTitle' => 'Home',
            'categories' => $this->categories->withLatestPosts(),
        ]));
    }
}
