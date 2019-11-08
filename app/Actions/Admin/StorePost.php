<?php


namespace App\Actions\Admin;


use App\Actions\BaseAction;
use App\Contracts\Repository;
use App\Forms\PostCreateForm;
use App\FlashMessages;
use App\Repositories\PostRepository;
use NilPortugues\Sql\QueryBuilder\Builder\BuilderInterface;
use PDO;

class StorePost extends BaseAction
{
    private $form;
    private $flash;

    public function __construct()
    {
        parent::__construct();

        $this->form = new PostCreateForm();
        $this->flash = FlashMessages::getInstance();
    }

    public function run()
    {
        $this->form->load($_REQUEST);

        if (!$this->form->validate()) {
            $this->flash->set('errors', $this->form->getErrors());

            header("Location: /admin/posts/create", true, 301);
            die();
        }

        $entity = $this->repository->save($this->form->getData());

        header("Location: /admin/posts/{$entity->getId()}/edit", true, 200);

        return;
    }

    protected function getViewName(): string
    {
        return '';
    }

    /**
     * @param BuilderInterface $builder
     *
     * @return Repository
     */
    public function getRepository(BuilderInterface $builder, PDO $db): Repository
    {
        return new PostRepository($builder, $db);
    }
}