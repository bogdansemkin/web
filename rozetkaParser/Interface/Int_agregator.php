<?php
/*Главный интерфейс agregator*/
interface Int_agregator {
    public function item_query($DBH); //выборка из бд поисковых запросов
    public function item_info($DBH); //выборка из бд товаров
    public function insert_item($DBH,$my_result,$result_get_url);//запись информации в БД
}
?>