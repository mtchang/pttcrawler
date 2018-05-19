<?php
// ---------------------------------------------------
// 分析 www.ptt.cc 網頁的索引檔
// Aauthor: Ming-Tai Chang <mtchang.tw@gmail.com>
// ---------------------------------------------------
// 
include dirname(__FILE__)."/lib.php"; 

// --------------------------------------------
// main
// --------------------------------------------

// 取得檔案
$file = "./pttdata/NBA/index.html";
//$file = "https://www.ptt.cc/bbs/NBA/index5922.html";
$html = file_get_contents($file);


// PTT index title 標題列, 檔名
$xpath_query='//*[@class="r-ent"]';
$result = get_xpath_node($html , $xpath_query);
foreach ($result as $key => $value) {
	// 取得局部source html 的 title a , nodevalue
	$board[$key]['title'] = get_xpath_nodevalue($value, '//*[@class="r-ent"]//*[@class="title"]//a');
	$board[$key]['title_href'] = get_xpath_nodevalue($value, '//*[@class="r-ent"]//*[@class="title"]//a/@href');
	$board[$key]['nrec'] = get_xpath_nodevalue($value, '//*[@class="r-ent"]//*[@class="nrec"]');
	$board[$key]['nrec'] = get_xpath_nodevalue($value, '//*[@class="r-ent"]//*[@class="nrec"]');
	$board[$key]['author'] = get_xpath_nodevalue($value, '//*[@class="r-ent"]//*[@class="author"]');
	$board[$key]['date'] = get_xpath_nodevalue($value, '//*[@class="r-ent"]//*[@class="date"]');
	$board[$key]['mark'] = get_xpath_nodevalue($value, '//*[@class="r-ent"]//*[@class="mark"]');

}
// var_dump($board);



// 版面的索引
/*
      [0]=>
      string(6) "看板"
      [1]=>
      string(9) "精華區"
      [2]=>
      string(6) "最舊"
      [3]=>
      string(10) "‹ 上頁"
      [4]=>
      string(10) "下頁 ›"
      [5]=>
      string(6) "最新"
*/
$xpath_query='//*[@class="action-bar"]';
$result = get_xpath_node($html , $xpath_query);
foreach ($result as $key => $value) {
	// 取得局部source html 的 title a , nodevalue
	$board['index']['a'] = get_xpath_nodevalue($value, '//*[@class="action-bar"]//div/a');
	$board['index']['a_href'] = get_xpath_nodevalue($value, '//*[@class="action-bar"]//div/a/@href');

}

// 文章索引
echo "PTT版面索引檔案：".$file."\n";
echo "解析後的內容：\n";
var_dump($board);

?>
