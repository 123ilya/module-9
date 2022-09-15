<?php


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
abstract class View {
    public $storage;
    public function _construct($object){
        $this->storage=$object;
    }
    abstract public function displayTextById($id);
    abstract public function displayTextByUrl($url);

}
//3. Создаю абстрактный класс User
abstract class User{
    public $id, $name,$role;
    abstract public function getTextToEdit ();
}