<?php


namespace App\Actions\Admin;


use App\Actions\BaseAction;
use App\Contracts\Repository;
use App\FlashMessages;
use App\Repositories\PostRepository;
use NilPortugues\Sql\QueryBuilder\Builder\BuilderInterface;
use PDO;

class EditPost extends BaseAction
{
    public function run()
    {
        $this->params['entity'] = $this->repository->find($this->id);

        return $this->twig->display($this->getViewName(), $this->params);
    }

    protected function getViewName(): string
    {
        return 'Admin/Posts/edit.twig';
    }

    public function getRepository(BuilderInterface $builder, PDO $db): Repository
    {
        return new PostRepository($builder, $db);
    }
}