<?php
header("Content-type: text/html; charset=utf-8");
/**
 * в командной строке вводим php -f index.php -- arg  ввод
 * затем вводим запрос поиска например "фотоаппарат"
 * запускается скрипт
 * подключаем БД,классы,библиотеку phpQuery
 * создаем объект и вызываем метод get_url() получаем url из командной строки
 * парсим по данному url c помощью curl
 * вытаксиваем данные с помощью библиотеки phpQuery
 * создаем объект и вызываем метод insert_item() записываем данные в БД
 * вызываем метод item_info (вытаскиваем информацию о товарах из БД)
 * вызываем метод item_query (вытаскиваем информацию поисковые запросы из БД)
 * выбока из БД и занос данных в csv файл, название запроса поиска и данные отсортированные по возростанию цен.
 * выводим информацию в командную строку и записывает в СSV файл по возрастанию цен
 */
require('Database/DB.php');
require('Class/Get_Parse.php');
require('Lib/phpQuery.php');
require('Class/Agregator.php');

$myArg = new Get_parse();
$result_get_url = $myArg->get_url();

$result_get_url = iconv("CP866","UTF-8",$result_get_url); //переводим кодировку принятых данных из командной строки
$result_curl = $myArg->action_curl($result_get_url);

$myParse = new Agregator();
$myParse->insert_item($DBH,$result_curl,$result_get_url);
$result_select_db = $myParse->item_info($DBH);
$result_select_query = $myParse->item_query($DBH);

//перебираем массив выводим данные
foreach($result_select_query as $query){
	$res = iconv("UTF-8","CP1251",'Поисковый запрос:  '.$query['item_query']);
	$my_result .= $res."\n\n";
	foreach($result_select_db[$query['item_query']] as $item){
		
		 		 $item_title = iconv("UTF-8","CP866",$item['item_title']);//замена кодировки для командной строки

	   echo $item['item_uah'].','.$item_title.','.$item['item_link'].','.$item['item_date']."\n";
		 
	$str = implode(",", $item);//массив в строку разделитель ','
    $result = iconv("UTF-8","CP1251",$str);//меняем кодировку для записи в csv файл
	$my_result .= $result."\n";
		
	}
}
file_put_contents('csv_file.csv', $my_result);//запись в файл



