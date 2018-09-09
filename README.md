### PTT版面索引及文章網頁分析

* 下載到 Linux 目錄
```
# git clone git@github.com:mtchang/pttcrawler.git
```

#### 執行分析PTT版面索引
```
# php cw_index.php
```

* PTT版面索引檔案：./pttdata/NBA/index.html 解析後的內容：
```
array(10) {
  [0]=>
  array(6) {
    ["title"]=>
    array(1) {
      [0]=>
      string(45) "[討論] 勇士火箭G1 精彩的雷霆內戰"
    }
...
```

#### 執行分析PTT文章
```
php cw_post.php 
```

* PTT文章檔案：./pttdata/NBA/M.1526307884.A.D90.html 文章主要內容解析後：
```
array(5) {
  ["article-meta-tag"]=>
  array(4) {
    [0]=>
    string(6) "作者"
    [1]=>
    string(6) "看板"
    [2]=>
    string(6) "標題"
    [3]=>
    string(6) "時間"
  }
  ["article-meta-value"]=>
  array(4) {
...
```

