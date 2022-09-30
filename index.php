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

    abstract public function read($slug): object;//получает объект из хранилища

    abstract public function update($slug, $object);//обновляет существующий объект в хранилище

    abstract public function delete($slug);//удаляет объект из хранмилища

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

class FileStorage extends Storage // Метод серриализует и записывает в файл, объект класса TelegraphText
{
    public function create(&$object): string
    {
        $count = 1;
        $fileName = $object->slug . '_' . date('Y-m-d');
        $name = $fileName;
        while (file_exists($name)) {
            $name = $fileName . '_' . $count;
            $count++;
        }
        $object->slug = $name;
        $serializedObject = serialize($object);
        file_put_contents($object->slug, $serializedObject);
        return $object->slug;
    }

    public function delete($slug) // Удаляет файл с именем $slug
    {
        unlink($slug);
    }

    public function list_(
    ): array //Возвращает массив объектов, полученных при дессиаризации содержимого файлов в дирректории.
    {
        $resultList = [];//Результирующий массив
        $list = scandir('./');//Перечень всех файлов и папок, находящихся в дирректории
        foreach ($list as $item) {
            if ($item !== '.' && $item !== '..' && !is_dir($item) && $item !== 'index.php') {
                $content = file_get_contents($item);
                $resultList[] = unserialize($content);
            }
        }
        return $resultList;
    }

    public function read($slug): object //Возвращает дессиаризованный объект из файла с именем $slug
    {
        return unserialize(file_get_contents($slug));
    }

    public function update($slug, $object) //Перезаписывает файл с именем $slug серриализованным объектом $object
    {
        $serializedObject = serialize($object);
        file_put_contents($slug, $serializedObject);
    }

}

//--------------------------------------------------------------------------
$text = new TelegraphText('ilya', 'test');
$text->editText('testTitle', 'swlihsliuhfwleihfliwuhfwleiuhfliwuhfliwuhfwleihf');

$testStore = new FileStorage();
$testStore->create($text);
var_dump($testStore->list_());