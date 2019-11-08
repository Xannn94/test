<?php


namespace App\Entities;

class Post extends BaseEntity
{
    protected $id;
    protected $title;
    protected $preview;
    protected $content;
    protected $created_at;
    protected $images;

    public function tableName(): string
    {
        return 'posts';
    }

    public function fillable(): array
    {
        return [
            'title' => $this->title,
            'preview' => $this->preview,
            'content' => $this->content
        ];
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }
}