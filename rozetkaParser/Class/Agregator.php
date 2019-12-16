<?php
require('Interface/Int_agregator.php');
class Agregator implements Int_agregator{
    /**
     * @param $DBH
     * выборка поля из БД (поисковые запросы)
     * @return mixed
     */
	public function item_query($DBH){
        $this->DBH = $DBH;
        $sql_select = "SELECT `item_query`
                       FROM `items` GROUP BY `item_query`";
        if(!$DBH){
            echo "<p>Выборка не прошла!</p>";
            exit();
        }else{
            $result = $this->DBH->query($sql_select)->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    }
	
	
	/**
     * @param $DBH
	 * вызываем метод item_query, узнаем поисковый запрос
	 * используем цикл for
     * выборка полей из БД по поисковому запросу и увелечению цен
     * @return array
     */
    public function item_info($DBH){
		$query = $this->item_query($DBH);
		for($i=0;$i<count($query);$i++){
		$sql_select = "SELECT `item_uah`,`item_title`,
								  `item_link`,`item_date`
						   FROM `items` WHERE `item_query`='".$query[$i]['item_query']."' ORDER BY `item_uah` ";
		$result[$query[$i]['item_query']] = $DBH->query($sql_select)->fetchAll(PDO::FETCH_ASSOC);
	
		}
		return($result);
    }

    /**
     * @param $DBH
     * @param $my_result
	 * @param $result_get_url
     * перебираем массив 
     * заносим товар в БД   («автоматический индекс», «текст запроса», «название товара»,
	 * «link на описание»,«цену», «дата парсинга»)
     */
    public function insert_item($DBH,$my_result,$result_get_url){
		foreach($my_result as $infoItem){
			$sql="INSERT INTO  `items` (`item_link`,`item_title`,`item_date`,`item_query`,`item_uah`)
                    VALUES (:item_link,:item_title,:item_date,:item_query,:item_uah)";
            $STH = $DBH->prepare($sql);	
				$uah = str_replace(" ","",$infoItem['uah']);
			$STH->bindParam(':item_link', $infoItem['link']);
			$STH->bindParam(':item_title', trim($infoItem['title']));
			$STH->bindParam(':item_date', date('Y-m-d'));
			$STH->bindParam(':item_query', $result_get_url);
			$STH->bindParam(':item_uah', $uah);
			$STH->execute();
		}
	}
}