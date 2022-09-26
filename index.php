<?php

class TelegraphText
{
    public $text; //Сам текст
    public $title; //Заголовок текста
    public $author; //Автор
    public $published; //Дата создания объекта
    public $slug; //Имя файла

    public function __construct($author, $slug)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = date('Y-m-d H:i:s');
    }

    public function storeText() // На основе полей объекта формирует массив, серриализует его, а затем
        //записывает в файл.
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

    public function loadText() //Выгружает содержимое из файла. И на основе выгруженного массива обновляет поля объекта.
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

    public function editText($title, $text)//Изменяет содержимое полей объекта title и text
    {
        $this->title = $title;
        $this->text = $text;
    }
}


//1.Абстрактный класс для хранилища
abstract class Storage
{
    abstract public function create($object);//создает объект в хранилище

    abstract public function read($id, $slug): array;//получает объект из хранилища

    abstract public function update($id, $slug, $object);//обновляет существующий объект в хранилище

    abstract public function delete($id, $slug);//удаляет объект из хранмилища

    abstract public function list_(): array;//возвращает массив всех объектов в хранилище
}

//2. Абстрактный класс для представления
abstract class View
{
    public $storage;

    public function _construct($object)
    {
        $this->storage = $object;//Присваивает полю $storage значение объекта, созданного подклассом Storage
    }

    abstract public function displayTextById($id);//Выводит текст по id

    abstract public function displayTextByUrl($url);//Выводитт текст по url

}

//3.Абстрактный класс User
abstract class User
{
    public $id, $name, $role;

    abstract public function getTextToEdit();//Выводит список текстов, доступных пользователю для редактирования
}

//--------------------------------------------------------------------------
$text = new TelegraphText('ilya', 'C:\Users\sobolev_ia\Desktop\PHP study\module-9\module-9\.test.txt');
$text->editText('titletitle', 'swlihsliuhfwleihfliwuhfwleiuhfliwuhfliwuhfwleihf');
var_dump($text);
$fileStorage = new FileStorage();
$fileStorage->create($text);

