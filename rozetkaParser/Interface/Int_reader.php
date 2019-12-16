<?php
/*Главный интерфейс чтения url*/
interface Int_reader {
    public function get_url(); // метод получение данных с командной строки
    public function action_curl($url); // парсер сайта с помощью curl, извлечения данных с помощью PhpQuery
}
?>