<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;

class CategoryController
{
    public function __construct(
        private readonly View $view
    ) {
    }

    public function show(Request $request): Response
    {
        $slug = (string) $request->route('slug', '');

        return Response::html($this->view->render('pages/category.tpl', [
            'pageTitle' => 'Category',
            'category' => [
                'name' => ucwords(str_replace('-', ' ', $slug)),
                'description' => 'Category page is ready for the next step.',
            ],
            'sort' => (string) $request->query('sort', 'date'),
        ]));
    }
}

