<?

function __dm_autoload_geo( $name )
{
	$map = array (
  'Yandex\\Geo\\Api' => 'Yandex/Geo/Api.php',
  'Yandex\\Geo\\Exception' => 'Yandex/Geo/Exception.php',
  'Yandex\\Geo\\Exception\\CurlError' => 'Yandex/Geo/Exception/CurlError.php',
  'Yandex\\Geo\\Exception\\ServerError' => 'Yandex/Geo/Exception/ServerError.php',
  'Yandex\\Geo\\GeoObject' => 'Yandex/Geo/GeoObject.php',
  'Yandex\\Geo\\Response' => 'Yandex/Geo/Response.php',
);
	if ( isset( $map[ $name ] ) )
	{
		require $map[ $name ];
	}
}
spl_autoload_register( '__dm_autoload_geo' );

function getLines($file) {
    $f = fopen($file, 'r');
    if (!$f) throw new Exception();
    while ($line = fgets($f)) {          
        yield $line;
    }
    fclose($f);
}
?>
<br/>
<br/>
<center>
<form id="config" name="form1" method="post" enctype="multipart/form-data" action="index.php">

	<table width="50%" border="0" align="center" cellspacing="2" cellpadding="2" class="form1_table">
	<tr>
		<td align="center">Укажите ваш ключ yandex-api <br/><br/></td>
	</tr>
	<tr>
		<td align="center"><input type="text" name="YA_KEY" value=""></td>
	</tr>
	<tr>
		<td align="center">Укажите CSV-файл<br/><br/></td>
	</tr>
	<tr>
		<td align="center"><input name="filename" type="file" class="fields">
		<input name="op" type="hidden" value="add"><br/><br/></td>
	</tr>
 	<tr>
    	<td align="center">
		<input type="Submit" value="Загрузить" name="Submit" class="fields"></td>
    </tr>
	</table>

	</form>
</center>
<?
	if($_POST['op']=="add" && $_FILES['filename']['name'] )
	{
		$fileNameShort = basename($_FILES['filename']['name']);
		$pext = strtolower(end(explode('.',$fileNameShort)));
		if (($pext != "csv")) 
		{ 
			print "<h1>ERROR</h1>Неправильный формат файла<br>"; 
			print "<p>Расширение файла должно быть CSV<br><br>"; 
			print "Расширение Вашего файла: $pext</p>\n"; 
			unlink($_FILES['filename']['tmp_name']); 
			exit(); 
		}
		$newfile = "imported.".$pext;
		if (!move_uploaded_file($_FILES['filename']['tmp_name'],$newfile))  
		{ 
			print "Error Uploading File."; 
			exit(); 
		} 
echo '<center><table width="800" border="1" cellspacing="2" cellpadding="2"><tr>
					<th>Объект</th>
					<th style="width:20%">Координаты</th>
					<th style="width:10%">Найдено результатов</th>
				</tr>';

		foreach (getLines($newfile) as $line) {
				$api = new \Yandex\Geo\Api();
				$api->setToken($_POST['YA_KEY']);
				$api->setQuery($line);
				// Настройка фильтров
				$api
					->setLimit(5) // кол-во результатов
					->setLang(\Yandex\Geo\Api::LANG_US) // локаль ответа
					->load();

				$response = $api->getResponse();
				//$response->getFoundCount(); // кол-во найденных адресов
				//$response->getQuery(); // исходный запрос
				//$response->getLatitude(); // широта для исходного запроса
				//$response->getLongitude(); // долгота для исходного запроса

				$lat = '';
				$long = '';
				// Список найденных точек
				$collection = $response->getList();
				
				if(count($collection) > 0) {
					$item = $collection[0];
					$lat = $item->getLatitude(); // широта
					$long = $item->getLongitude(); // долгота
					echo"<tr>
						<td>".iconv('CP1251','UTF-8',$line)."</td>
						<td>".$lat.' - '.$long."</td>
						<td>".$response->getFoundCount()."</td>
					</tr>";
				}
		}
			echo "</table></center>";
	}
?>