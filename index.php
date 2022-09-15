<?php


class TelegraphText
{
    //Добавляю поля
    public $text, $title, $author, $published, $slug;

//Добавляю конструктор. При создании объекта ,конструктор инициализирует, указанные в нём переменные
    public function __construct(string $author, string $slug)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = date('Y-m-d H:i:s');
    }

//Создаю метод, для записи текста в файл
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

    //Создаю метод для загрузки текста из файла
    public function loadText()
    {
        //Десереализую строку, сохраненную в файле
        $loadedPost = unserialize(file_get_contents($this->slug));
        //Присваиваю полям класса, значения полученного массива
        if (filesize($this->slug) !== 0) {
            $this->title = $loadedPost['title'];
            $this->text = $loadedPost['text'];
            $this->author = $loadedPost['author'];
            $this->published = $loadedPost['published'];
//            echo $this->text;
            return $this->text;
        }
    }

    //Создаю метод, для редактирование текста и заголовка
    public function editText(string $title, string $text)
    {
        $this->title = $title;
        $this->text = $text;
//        echo $this->title.PHP_EOL;
//        echo $this->text.PHP_EOL;
    }
}

//Создаю объект класса TelegraphText
$obj = new TelegraphText('ilya', '.\obj.txt');
var_dump($obj);
//Вызываю метод 'editText'
$obj->editText('new story', 'klsjhklsjfhakjhfakjdhkljashkajshafs');
var_dump($obj);
//Вызываю метод 'storeText'
$obj->storeText();
//Вызываю метод 'LoadText' и вывожу возвращеное им значение (text)
echo $obj->loadText();