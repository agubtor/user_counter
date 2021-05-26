<?
session_start();
//выделяем уникальный идентификатор сессии
$id = session_id();

if ($id!="") {
 //текущее время
 $CurrentTime = time();
 //через какое время сессии удаляются
 $LastTime = time() - 30;
 //файл, в котором храним идентификаторы и время
 $base = "session.txt";

 $file = file($base);
 $k = 0;
 
 //Считываем текущую инфу из файла и отсеиваем тех, кто не был активным более 30 сек.
  for ($i = 0; $i < sizeof($file); $i++) {
  $line = explode("|", $file[$i]);
   if ($line[1] > $LastTime) {
   $ResFile[$k] = $file[$i];
   $k++;
  }
 }

 //Перебираем массив и обновляем время активности для текущего пользователя
 for ($i = 0; $i<sizeof($ResFile); $i++) {
  $line = explode("|", $ResFile[$i]);
  if ($line[0]==$id) {
      $line[1] = trim($CurrentTime)."\n";
      $is_sid_in_file = 1;
  }
  $line = implode("|", $line);
  $ResFile[$i] = $line;
 }

//Перезаписываем обновления для всех пользователей в файл
 $fp = fopen($base, "w");
 for ($i = 0; $i<sizeof($ResFile); $i++) { 
	fwrite($fp, $ResFile[$i]); 
 }
 
 fclose($fp);

//Если пользователь новый, дописываем в конец файла
 if (!$is_sid_in_file) {
  $fp = fopen($base, "a");
  $line = $id."|".$CurrentTime."\n";
  fwrite($fp, $line);
  fclose($fp);
 }
}

echo "Сейчас на сайте: <b>".sizeof(file($base))."</b>";
?>