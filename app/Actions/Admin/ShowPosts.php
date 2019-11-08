<?php


namespace App\Actions\Admin;


use App\Actions\BaseAction;
use App\Contracts\Repository;
use App\Repositories\PostRepository;
use NilPortugues\Sql\QueryBuilder\Builder\BuilderInterface;
use PDO;

class ShowPosts extends BaseAction
{
    public function __construct()
    {
        parent::__construct();

        $this->params['entities'] = $this->repository->index();
    }

    protected function getViewName(): string
    {
        return 'Admin/Posts/index.twig';
    }

    public function getRepository(BuilderInterface $builder, PDO $db): Repository
    {
        return new PostRepository($builder, $db);
    }
}