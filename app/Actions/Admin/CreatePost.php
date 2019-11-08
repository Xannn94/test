<?php


namespace App\Actions\Admin;


use App\Actions\BaseAction;
use App\Contracts\Repository;
use App\FlashMessages;
use App\Repositories\PostRepository;
use NilPortugues\Sql\QueryBuilder\Builder\BuilderInterface;
use PDO;

class CreatePost extends BaseAction
{
    public function __construct()
    {
        parent::__construct();

        $this->params['flash'] = FlashMessages::getInstance();
    }

    protected function getViewName(): string
    {
        return 'Admin/Posts/create.twig';
    }

    public function getRepository(BuilderInterface $builder, PDO $db): Repository
    {
        return new PostRepository($builder, $db);
    }
}