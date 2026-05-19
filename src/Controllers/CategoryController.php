<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;

class CategoryController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categories,
        private readonly PostRepository $posts
    ) {
    }

    public function show(Request $request): Response
    {
        $slug = (string) $request->route('slug', '');
        $category = $this->categories->findBySlug($slug);

        if ($category === null) {
            return Response::html(render_not_found(), 404);
        }

        $sort = (string) $request->query('sort', 'date');
        $sort = in_array($sort, ['date', 'views'], true) ? $sort : 'date';
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 6;
        $total = $this->posts->countByCategory((int) $category['id']);
        $pages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $pages);

        return Response::html($this->view->render('pages/category.tpl', [
            'pageTitle' => $category['name'],
            'category' => $category,
            'posts' => $this->posts->findByCategory((int) $category['id'], $sort, $page, $perPage),
            'sort' => $sort,
            'pagination' => [
                'page' => $page,
                'pages' => $pages,
                'total' => $total,
                'perPage' => $perPage,
            ],
        ]));
    }
}
