<?php
// ---------------------------------------------------
// 分析 www.ptt.cc 網頁的的單篇文章元素
// Aauthor: Ming-Tai Chang <mtchang.tw@gmail.com>
// ---------------------------------------------------
// 
include dirname(__FILE__)."/lib.php"; 


// --------------------------------------------
// main
// --------------------------------------------

// 取得檔案
//$file = "pttdata/NBA/index.html";
//$url = "https://www.ptt.cc/bbs/NBA/M.1526319732.A.0CC.html";
// $file="pttdata/NBA/M.1526319732.A.0CC.html";
$file="./pttdata/NBA/M.1526307884.A.D90.html";
// $file="https://www.ptt.cc/bbs/NBA/M.1526391235.A.BCA.html";
$html = file_get_contents($file);


// 文章
$xpath_query='//*[@id="main-container"]';
$result = get_xpath_node($html , $xpath_query);
// var_dump($result);

$epost['article-meta-tag'] = get_xpath_nodevalue($result[0], '//*[@id="main-content"]//*[@class="article-meta-tag"]');
$epost['article-meta-value'] = get_xpath_nodevalue($result[0], '//*[@id="main-content"]//*[@class="article-meta-value"]');
// 移除空陣列
$epost['text'] = array_filter(get_xpath_nodevalue($result[0], '//*[@id="main-content"]/text()'));
$epost['f2'] = get_xpath_nodevalue($result[0], '//*[@id="main-content"]//*[@class="f2"]');
$epost['push'] = get_xpath_nodevalue($result[0], '//*[@id="main-content"]//*[@class="push"]');


// 針對文章的 push 分析
$xpath_query='//*[@id="main-content"]//*[@class="push"]';
$push_result = get_xpath_node($html , $xpath_query);
foreach ($push_result as $key => $value) {
	$epostpush[$key]['push-tag'] = get_xpath_nodevalue($value, '//*[@class="push"]/*[@class="hl push-tag"]');
	$epostpush[$key]['f1_push-tag'] = get_xpath_nodevalue($value, '//*[@class="push"]/*[@class="f1 hl push-tag"]');
	$epostpush[$key]['push-userid'] = get_xpath_nodevalue($value, '//*[@class="push"]/*[@class="f3 hl push-userid"]');
	$epostpush[$key]['push-content'] = get_xpath_nodevalue($value, '//*[@class="push"]/*[@class="f3 push-content"]');
	$epostpush[$key]['push-ipdatetime'] = get_xpath_nodevalue($value, '//*[@class="push"]/*[@class="push-ipdatetime"]');
}



// 文章索引
echo "PTT文章檔案：".$file."\n";
echo "文章主要內容解析後：\n";
var_dump($epost);
//echo json_encode($epost);

echo "文章推文解析後：\n";
var_dump($epostpush);
//echo json_encode($epostpush);

?>
