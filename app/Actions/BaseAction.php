<?php


namespace App\Actions;


use App\Contracts\Action;
use App\FlashMessages;
use App\MysqlPdo;
use NilPortugues\Sql\QueryBuilder\Builder\BuilderInterface;
use NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder;
use PDO;
use Twig\TwigFunction;
use Twig\TwigTest;

abstract class BaseAction implements Action
{
    protected $twig;
    protected $repository;
    protected $params = [];
    protected $id;

    public function __construct(int $id = 0)
    {
        $this->repository = $this->getRepository($this->getBuilder(), $this->getDb());
        $this->id         = $id;

        $loader     = new \Twig\Loader\FilesystemLoader('../App/Views');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => '../../tmp/cache',
            'debug' => true
        ]);

        $isArray = new TwigTest('array', function ($value) {
            return is_array($value);
        });

        $this->twig->addTest($isArray);

        $flashMessages = new TwigFunction('flash', function () {
            return FlashMessages::getInstance();
        });

        $this->twig->addFunction($flashMessages);
    }

    public function run()
    {
        return $this->twig->display($this->getViewName(), $this->params);
    }

    protected function getBuilder(): BuilderInterface
    {
        return new GenericBuilder();
    }

    protected function getDb(): PDO
    {
        return new MysqlPdo();
    }

    abstract protected function getViewName(): string;
}