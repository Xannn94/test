<?php


namespace App;


use App\Actions\Admin\CreatePost;
use App\Actions\Admin\EditPost;
use App\Actions\Admin\ShowPosts;
use App\Actions\Admin\StorePost;
use App\Actions\Admin\UpdatePost;
use App\Actions\MainPage;
use App\Contracts\Action;

class Router
{
    private CONST API_URI = 'api';

    private $uri;
    private $uriStructure = [];
    private $actions      = [];
    private $routes       = [
        'api'  => [
            'v1' => [

            ]
        ],
        'site' => [
            '/'                         => [
                'method' => 'get',
                'action' => MainPage::class,
                'type'   => 'strong'
            ],
            '/admin/posts/index'        => [
                'method' => 'get',
                'action' => ShowPosts::class,
                'type'   => 'strong'
            ],
            '/admin/posts/create'       => [
                'method' => 'get',
                'action' => CreatePost::class,
                'type'   => 'strong'
            ],
            '/admin/posts/store'        => [
                'method' => 'get',
                'action' => StorePost::class,
                'type'   => 'strong'
            ],
            '\/admin\/posts\/\d+\/edit' => [
                'method' => 'get',
                'action' => EditPost::class,
                'type'   => 'regex'
            ],
            '\/admin\/posts\/\d+'       => [
                'method' => 'put',
                'action' => UpdatePost::class,
                'type'   => 'regex'
            ],
        ]
    ];

    public function __construct(string $uri)
    {
        $this->uri          = $uri;
        $this->uriStructure = explode('/', $uri);
        $this->actions      = $this->getActions();

        /**
         * Удаляем первый элемент т.к он всегда равен пустой строке и пользы не приносит.
         */
        unset($this->uriStructure[0]);
    }

    public function run()
    {
        if (!$this->existAction($this->actions)) {
            http_response_code(404);
            include('Views/Errors/404.php');
            die();
        }

        return $this->getActionClass()->run();
    }

    private function isApi(): bool
    {
        return $this->uriStructure[1] === self::API_URI;
    }

    private function existAction(array $actions): bool
    {
        if (array_key_exists($this->uri, $actions)) {
            return true;
        }

        foreach ($actions as $key => $value) {
            if ($value['type'] === 'regex' && pattern($key)->match($this->uri)->count() && $value['method'] === $this->getMethod()) {
                return true;
            }
        }

        return false;
    }

    private function getActions(): array
    {
        return $this->isApi() ? $this->routes['api'] : $this->routes['site'];
    }

    private function getActionClass(): ?Action
    {
        if (isset($this->actions[$this->uri])) {
            $actionClassName = $this->actions[$this->uri]['action'];

            return new $actionClassName;
        }

        $actions = $this->getActions();

        foreach ($actions as $key => $value) {
            if ($value['type'] === 'regex' && pattern($key)->match($this->uri)->count() && $value['method'] === $this->getMethod()) {
                $actionClassName = $this->actions[$key]['action'];
                $id              = pattern('\d+')->match($this->uri)->first();

                return new $actionClassName($id);
            }
        }

        return null;
    }

    private function getMethod(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return 'get';
        }

        if (isset($_POST['_method']) && ($_POST['_method'] == 'PUT' || $_POST['_method'] == 'DELETE')) {
            $method = strtolower($_POST['_method']);
        } else {
            $method = 'post';
        }

        return $method;
    }
}