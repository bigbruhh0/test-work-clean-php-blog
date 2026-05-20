<?php

declare(strict_types=1);

namespace App;

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\ManageController;
use App\Controllers\PostController;
use App\Core\Container;
use App\Core\Database;
use App\Core\Request;
use App\Core\Router;
use App\Core\View;
use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\Services\ImageUploader;
use App\Services\ImageUrlResolver;

class Application
{
    private Container $container;
    private Router $router;

    public function __construct()
    {
        loadEnv(base_path('.env'));

        $this->container = new Container();
        $this->router = new Router();

        $this->registerServices();
        $this->registerRoutes();
    }

    public function run(): void
    {
        $request = Request::capture();
        $this->container->get(View::class)->assign('formCategories', $this->container->get(CategoryRepository::class)->all());
        $response = $this->router->dispatch($request);

        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $response->getContent();
    }

    public function container(): Container
    {
        return $this->container;
    }

    private function registerServices(): void
    {
        $this->container->singleton('config', function (Container $container): array {
            return [
                'app' => require config_path('app.php'),
                'assets' => require config_path('assets.php'),
                'database' => require config_path('database.php'),
            ];
        });

        $this->container->singleton(Database::class, function (Container $container): Database {
            $config = $container->get('config');
            return new Database($config['database']);
        });

        $this->container->singleton(View::class, function (Container $container): View {
            $config = $container->get('config');
            return new View($config['app']);
        });

        $this->container->singleton(ImageUrlResolver::class, function (Container $container): ImageUrlResolver {
            $config = $container->get('config');
            return new ImageUrlResolver($config['assets']['url']);
        });

        $this->container->singleton(ImageUploader::class, function (): ImageUploader {
            return new ImageUploader();
        });

        $this->container->singleton(CategoryRepository::class, function (Container $container): CategoryRepository {
            return new CategoryRepository(
                $container->get(Database::class),
                $container->get(ImageUrlResolver::class)
            );
        });

        $this->container->singleton(PostRepository::class, function (Container $container): PostRepository {
            return new PostRepository(
                $container->get(Database::class),
                $container->get(ImageUrlResolver::class)
            );
        });

        $this->container->singleton(HomeController::class, function (Container $container): HomeController {
            return new HomeController(
                $container->get(View::class),
                $container->get(CategoryRepository::class)
            );
        });

        $this->container->singleton(CategoryController::class, function (Container $container): CategoryController {
            return new CategoryController(
                $container->get(View::class),
                $container->get(CategoryRepository::class),
                $container->get(PostRepository::class)
            );
        });

        $this->container->singleton(PostController::class, function (Container $container): PostController {
            return new PostController(
                $container->get(View::class),
                $container->get(PostRepository::class)
            );
        });

        $this->container->singleton(ManageController::class, function (Container $container): ManageController {
            return new ManageController(
                $container->get(CategoryRepository::class),
                $container->get(PostRepository::class),
                $container->get(ImageUploader::class)
            );
        });
    }

    private function registerRoutes(): void
    {
        $this->router->get('/', fn (Request $request) => $this->container->get(HomeController::class)->index($request));
        $this->router->get('/category/{slug}', fn (Request $request) => $this->container->get(CategoryController::class)->show($request));
        $this->router->get('/post/{slug}', fn (Request $request) => $this->container->get(PostController::class)->show($request));
        $this->router->post('/categories', fn (Request $request) => $this->container->get(ManageController::class)->storeCategory($request));
        $this->router->post('/posts', fn (Request $request) => $this->container->get(ManageController::class)->storePost($request));
    }
}
