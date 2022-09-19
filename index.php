<?php

class TelegraphText
{
    public $text;
    public $title;
    public $author;
    public $published;
    public $slug;

    public function __construct($author, $slug)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = date('Y-m-d H:i:s');
    }

    public function storeText()
    {
        $post = [
            'title' => $this->title,
            'text' => $this->text,
            'author' => $this->author,
            'published' => $this->published
        ];
        $serializedPost = serialize($post);
        file_put_contents($this->slug, $serializedPost);
    }

    public function loadText()
    {
        $loadedPost = unserialize(file_get_contents($this->slug));
        if (filesize($this->slug) !== 0) {
            $this->title = $loadedPost['title'];
            $this->text = $loadedPost['text'];
            $this->author = $loadedPost['author'];
            $this->published = $loadedPost['published'];
            return $this->text;
        }
    }

    public function editText($title, $text)
    {
        $this->title = $title;
        $this->text = $text;
    }
}


//1. Создаю абстрактный класс для хранилища
abstract class Storage
{
    abstract public function create(array $object);

    abstract public function read($id, $slag): array;

    abstract public function update($id, $slag, $object);

    abstract public function delete($id, $slag);

    abstract public function list_(): array;
}

//2. Создаю абстрактный класс для представления
abstract class View
{
    public $storage;

    public function _construct($object)
    {
        $this->storage = $object;
    }

    abstract public function displayTextById($id);

    abstract public function displayTextByUrl($url);

}

//3. Создаю абстрактный класс User
abstract class User
{
    public $id, $name, $role;

    abstract public function getTextToEdit();
}

//4. Реализую класс "Файл" (FileStorage) для абстрактного класса Storage
class FileStorage extends Storage
{
    public function create(array $object)
    {
    }

    public function read($id, $slag): array

     public function update($id, $slag, $object)
     {
     }

     public function delete($id, $slag)
     {
     }

     public function list_(): array
     {
     }
}