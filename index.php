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
    abstract public function create( $object);

    abstract public function read($id, $slug): array;

    abstract public function update($id, $slug, $object);

    abstract public function delete($id, $slug);

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
    //create получает на вход obj. Изменяет slug, серрилизует и записывает
    //в файл с именем slug,  возвращает slug
    public function create($object)
    {
        $fileName = $object->slug . '_' . date('Y-m-d H:i:s');
        $i = 1;
        while (file_exists($fileName)) {
            $fileName . '_' . $i;
            $i++;
        }
        $object->slug = $fileName;
        $serializedText = serialize($object);
        file_put_contents($object->slug, $serializedText);
        return $object->slug;
    }
//--------------------------------------------------
//read возвращает десириализованный массив
    public function read($id, $slug): array
    {
        return unserialize(file_get_contents($slug));
    }
//-------------------------------------------------
//update - записывает или перезаписывает содержание файла
    public function update($id, $slug, $object)
    {
        file_put_contents($slug, serialize($object));
    }
//---------------------------------------------------
//delete Удаляет файл
    public function delete($id, $slug)
    {
        unlink($slug);
    }
//------------------------------------------------------
//Возвращает массив объектов, из созданных файлов
    public function list_(): array
    {
        $allFiles = array();

        foreach (scandir('./') as $item) {
            $allFiles[] = unserialize($item);
        }
        return $allFiles;
    }

}
//--------------------------------------------------------------------------
$text = new TelegraphText('ilya', 'C:\Users\sobolev_ia\Desktop\PHP study\module-9\module-9\.test.txt');
$text->editText('titletitle', 'swlihsliuhfwleihfliwuhfwleiuhfliwuhfliwuhfwleihf');
var_dump($text);
$fileStorage = new FileStorage();
$fileStorage->create($text);

