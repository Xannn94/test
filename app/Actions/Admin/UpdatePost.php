<?php


namespace App\Actions\Admin;


use App\Actions\BaseAction;
use App\Contracts\Repository;
use App\Forms\PostCreateForm;
use App\FlashMessages;
use App\Forms\PostUpdateForm;
use App\Repositories\PostRepository;
use NilPortugues\Sql\QueryBuilder\Builder\BuilderInterface;
use PDO;

class UpdatePost extends BaseAction
{
    private $form;
    private $flash;

    public function __construct(int $id)
    {
        parent::__construct($id);

        $this->form  = new PostUpdateForm();
        $this->flash = FlashMessages::getInstance();
    }

    public function run()
    {
        $entity = $this->repository->find($this->id);
        if (is_null($entity)) {
            http_response_code(404);
            include('Views/Errors/404.php');
            die();
        }

        $this->form->load($_REQUEST);

        if (!$this->form->validate()) {
            $this->flash->set('errors', $this->form->getErrors());

            header("Location: /admin/posts/create", true, 301);
            die();
        }

        $entity = $this->repository->update($this->form->getData(), $entity);

        header("Location: /admin/posts/{$entity->getId()}/edit", true, 301);
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