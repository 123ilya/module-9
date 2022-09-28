<?php

class TelegraphText
{
    public string $text; //Сам текст
    public string $title; //Заголовок текста
    public string $author; //Автор
    public string $published; //Дата создания объекта
    public string $slug; //Имя файла

    public function __construct($author, $slug)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = date('Y-m-d H:i:s');
    }

    public function storeText(): void // На основе полей объекта формирует массив, серриализует его, а затем
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

    public function editText($title, $text): void//Изменяет содержимое полей объекта title и text
    {
        $this->title = $title;
        $this->text = $text;
    }
}


//1.Абстрактный класс для хранилища
abstract class Storage
{
    abstract public function create(&$object);//создает объект в хранилище

    abstract public function read($id, $slug): array;//получает объект из хранилища

    abstract public function update($id, $slug, $object);//обновляет существующий объект в хранилище

    abstract public function delete($id, $slug);//удаляет объект из хранмилища

    abstract public function list_(): array;//возвращает массив всех объектов в хранилище
}

//2. Абстрактный класс для представления
abstract class View
{
    public object $storage;

//    public function _construct($object)
//    {
//        $this->storage = $object;//Присваивает полю $storage значение объекта, созданного подклассом Storage
//    }

    abstract public function displayTextById($id);//Выводит текст по id

    abstract public function displayTextByUrl($url);//Выводитт текст по url

}

//3.Абстрактный класс User
abstract class User
{
    public string $id, $name, $role;

    abstract public function getTextToEdit();//Выводит список текстов, доступных пользователю для редактирования
}

//--------------------------------------------------------------------------
class FileStorage extends Storage
{
    public function create(&$object)
    {
        $count = 1;
        $fileName = $object->slug . '_' . date('Y-m-d');
        $name =$fileName;
        while (file_exists($name)) {
            $name = $fileName . '_'.$count;
            $count++;
        }
        $object->slug = $name;
        $serializedObject = serialize($object);
        file_put_contents($object->slug, $serializedObject);
    }

    public function delete($id, $slug)
    {
        // TODO: Implement delete() method.
    }

    public function list_(): array
    {
        // TODO: Implement list_() method.
        return [8, 9];
    }

    public function read($id, $slug): array
    {
        // TODO: Implement read() method.
        return [8, 0];
    }

    public function update($id, $slug, $object)
    {
        // TODO: Implement update() method.
    }

}

//--------------------------------------------------------------------------
$text = new TelegraphText('ilya', 'test');
$text->editText('testTitle', 'swlihsliuhfwleihfliwuhfwleiuhfliwuhfliwuhfwleihf');

$testStore = new FileStorage();
$testStore->create($text);

