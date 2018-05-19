<?php
// ---------------------------------------------------
// PHP 爬蟲專用 lib
// Aauthor: Ming-Tai Chang <mtchang.tw@gmail.com>
// ---------------------------------------------------

// ---------------------------------------------------
// https://www.awaimai.com/2113.html
// 取得指定 xpath 的 node html
function get_xpath_node($html, $xpath_query){

	$dom = new DOMDocument();
	$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);

	// $xpath_query='//*[@class="r-ent"]';
	$nodeList = $xpath->query($xpath_query);
	// var_dump($nodeList);
	$result = [];
	foreach ($nodeList as $node) {
		// 取得 r-ent class內的 html
		$str = mb_convert_encoding($dom->saveHTML($node), "UTF-8", "auto");
	    $result[] = $str;
	}
	return($result);
}


// ---------------------------------------------------
// 針對 get_xpath_node 取得的 html source code, 擷取內容. 需要加上編碼, 否則輸出會亂碼
function get_xpath_nodevalue($subhtml, $xpath_query) {

$html2 = <<<HTML
<!doctype html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
$subhtml
</body>
</html>
HTML;

	$dom = new DOMDocument();
	$dom->loadHTML($html2);
	$xpath = new DOMXPath($dom);
	// $xpath_query='//*[@class="r-ent"]//*[@class="title"]//a';
	$nodeList = $xpath->query($xpath_query);
	// var_dump($nodeList);
	$result = [];
	foreach ($nodeList as $node) {
	    $result[] = trim($node->nodeValue," \n\0");

	}

return($result);
}



// -------------------------------------------------------------------
// 名稱：my_curl($url,$post)
// 功能：呼叫指定的 url 並傳入 POST 值
//
function ptt_curl($url,$post,$port)
{
	$cookieFile = 'cookie.txt';
	// default port is 80
	// $port = 79;
	
	$ch = curl_init();
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
	# sending manually set cookie
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: over18=1"));
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_PORT,$port);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);	
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
	// debug mode
	//curl_setopt($ch, CURLOPT_VERBOSE, true);

	$curl_result = curl_exec($ch);
	
	if(!curl_errno($ch)) {
		$info = curl_getinfo($ch);
		// var_dump($info);
	}

	curl_close ($ch);

	if($info["http_code"] == 200) {
		return($curl_result);
	}else{
		return(false);
	}
	
}
// -------------------------------------------------------------------







// -------------------------------------------------------------------
// 名稱：get_pttpost($ptt_board,$ptt_file) 
// 功能：取得指定ptt的版面文章，並寫入檔案內。
//
function get_pttpost($ptt_board,$ptt_file) {

	GLOBAL $pwd;

	// input
	$curlpost['board'] 	= $ptt_board;
	$curlpost['file'] 	= $ptt_file;
	
	$curlpost['from']	= 'pttbbs';
	$curlpost['URL']	= 'https://www.ptt.cc/bbs';
	
	$curlpost['outputfile']	= $curlpost['from'].'/'.$curlpost['board'].'/'.$curlpost['file'];
	$curlpost['outputdir']	= $curlpost['from'].'/'.$curlpost['board'];

	// Get URL to File


	// $postUrl 	= 'https://www.ptt.cc/bbs/Gossiping/index.html';
	$sendto_curl	= $curlpost['URL'].'/'.$curlpost['board'].'/'.$curlpost['file'];
	$sendto_httpport	= 443;
	$sendto_postvar	= array(
	'__VIEWSTATE' => 'test'
	);

	// return
	$return_var[0]			= false;
	$return_var[1]			= $curlpost['outputfile'];
	
	//var_dump($sendto_curl);

	// use CURL get http content
	$getBody = ptt_curl($sendto_curl,$sendto_postvar,$sendto_httpport);
	// var_dump($getBody);
	
	// curl 是否成功
	if($getBody){	
		$filename_dir 	= $pwd['dir'].'/'.$curlpost['outputdir'];
		$filename		= $pwd['dir'].'/'.$curlpost['outputfile'];
		//echo "$filename_dir \n";

		// check DIR exit ?
		if (file_exists($filename_dir)) {
			//echo "The file $filename_dir exists \n";
		} else {
			mkdir($filename_dir,0755,true);
			//echo "The file $filename_dir does not exist \n";
		}

		// write to $filename
		if(file_put_contents($filename,$getBody)) {
			// echo 'success!! write to '.$filename."\n";
			$return_var[0]			= true;
			$return_var[1]			= $curlpost['outputfile'];
		}
	}else{
		echo 'ERROR: 從網路取得檔案失敗!!';
		$return_var[0]			= false;
	}

	// 再次檢查檔案是否存在，不存在設定失敗。
	if (file_exists($filename)) {
		$return_var[0]			= true;
		$return_var[1]			= $curlpost['outputfile'];
	}else{
		echo 'ERROR: 寫入檔案失敗，檔案不存在本機上。';
		var_dump($return_var);
		var_dump($sendto_curl);
		$return_var[0]			= false;		
	}
	
	return($return_var);
}
// -------------------------------------------------------------------


// -------------------------------------------------------------------
// 名稱：get_pttpost_fromfile($ptt_board,$ptt_file) 
// 功能：取得指定ptt的版面文章 from file to value。
//
function get_pttpost_fromfile($ptt_board,$ptt_file) {
	
	GLOBAL $pwd;
	// Get URL from File
	$curlpost['from']	= 'pttdata';
	$curlpost['board'] 	= $ptt_board;
	$curlpost['file'] 	= $ptt_file;

	$curlpost['outputfile']	= $pwd['dir'].'/'.$curlpost['from'].'/'.$curlpost['board'].'/'.$curlpost['file'];
	$curlpost['outputdir']	= $pwd['dir'].'/'.$curlpost['from'].'/'.$curlpost['board'];

	$file = $curlpost['outputfile'];
	// var_dump($file);

	// $file_html = file_get_contents("$file", FILE_USE_INCLUDE_PATH);
	// $html = file_get_contents("$file");
	// http://simplehtmldom.sourceforge.net/
	// include('./lib/simple_html_dom.php');

	$html = file_get_html($file);

	// var_dump($html);
	
	return($html);
}	
// -------------------------------------------------------------------




function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
// -------------------------------------------------------------------

?>
