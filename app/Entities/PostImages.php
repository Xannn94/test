<?php


namespace App\Entities;

class PostImages extends BaseEntity
{
    protected $id;
    protected $post;
    protected $image;
    protected $priority;

    public function tableName(): string
    {
        return 'post_images';
    }
}