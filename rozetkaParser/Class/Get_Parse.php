<?php 
require('Interface/Int_reader.php');
class Get_Parse implements Int_reader{			
	/**
     * Метод получение данных с командной строки
     * открытый поток stdin
     * читаем строку из потока stdin и заносим в переменную
     * @return string
     */
	public function get_url(){
        $url = fopen('php://stdin', 'w');
        $line = trim(fgets($url));
        return $line;
    }
	/**
     * Метод для парсинга с помощью curl
     * @param $url принимаем URL адресс
     * используем блок try/catch
	 * дописываем к поисковому запросу наш запрос
     * инициализируем сессию curl
     * указываем URL, куда отправлять запрос CURLOPT_URL
     * запрет на прием заголовков CURLOPT_HEADER
     * разрешаем перенаправление CURLOPT_FOLLOWLOCATION
     * возврат резудьтата в переменную CURLOPT_RETURNTRANSFER
     * выполняем запрос curl
	 * создаем цикл
	 * инициализируем сессию curl
     * указываем URL, куда отправлять запрос CURLOPT_URL
	 * выполняем запрос curl
	 * используем библиотека phpQuery для выборки нужных полей
     * поля:
	 * статус status,  для проверки есть товар в наличии
     * новости title
     * цена price
     * ссылка на описание link
	 * с помощью цикла For и status,price,
	 * отберает только товар который есть в наличии и у которого есть цена
     * если в результате работы возникла ошибка (исключительная ситуация),
     * перереходим в catch  (вывод ошибки)
     * @return array	 
	 * закрываем соединение
     */
	 
	public function action_curl($url){
        try{
            if(empty($url)) throw new Exception("Введены не все данные!");
            $this->url = 'http://rozetka.com.ua/search/?section=%2F&text='.$url;
            $content = "";
            $empty_contents = "";
            $curl_handle = curl_init();  // инициализируем сессию curl
            if ($curl_handle) {
                curl_setopt($curl_handle, CURLOPT_URL, $this->url);
                curl_setopt($curl_handle, CURLOPT_HEADER, false);
                curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
				$contents = curl_exec($curl_handle);
					for($j = 0; $j < 20; $j++){
						$search_url = 'http://rozetka.com.ua/search/?p='.$j.'&section=%2F&text='.$url;
						curl_setopt($curl_handle, CURLOPT_URL, $search_url);
						$my_search = curl_exec($curl_handle);					
								$results = phpQuery::newDocument($my_search);
								$photo = $results->find('body');
								foreach ($photo as $element){
									foreach(pq($element)->find('.status span') as $res){ 
										$status = pq($res)->text();		
										$status_item[] = array('status'=>$status);
									}
									foreach(pq($element)->find('.uah') as $uah){ 
										$uah = pq($uah)->text();
										$price[] = array('uah'=>$uah);			
									}
									foreach(pq($element)->find('.title a') as $result){ 
										$title = pq($result)->text();
										$link = pq($result)->attr('href');
										$name[] = array('title'=>$title,'link'=>$link);	
									}	
								}		
					}	
					for($i=0;$i<count($name);$i++){
						if(isset($price[$i]) && $status_item[$i]['status']=='Есть в наличии'){
							$info[$i]=$name[$i]+$price[$i];
						}	
					}
					return $info;
			    curl_close($curl_handle);
			}
        }
		catch(Exception $e){
            echo "Произошла ошибка ", $e->getMessage(),
            " в строке ", $e->getLine(),
            " файла ", $e->getFile();
            exit;
        }
    }			
			
}			

		
?>