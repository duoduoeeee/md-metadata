<?php
//网易云音乐 api 解析
function getNeMusicDetails($resid) {
  $requestURL = 'https://napi.duoee.cn/?type=song&id=' .$resid;
  $lyricRequestURL = 'https://napi.duoee.cn/?type=lyric&id=' .$resid. '&lv=-1&kv=-1';
  $getNeJsonData = file_get_contents($requestURL);
  $getNeLyricJsonData = file_get_contents($lyricRequestURL);
  $objectifyNeJsonData = json_decode($getNeJsonData);
  $objectifyNeLyricJsonData = json_decode($getNeLyricJsonData);
  if ($objectifyNeJsonData -> code == "200") {
    $songInfo = $objectifyNeJsonData -> songs[0] -> name;
    //some lovable regex?
    //no regex now
    //艺术家信息
    foreach ($objectifyNeJsonData -> songs[0] -> artists as $artistArray) {
      $artistString = $artistArray -> name;
      $artistOutput = $artistString . '/' ;
      $artistTrim = $artistTrim . $artistOutput;
      }
    $artistInfo = rtrim($artistTrim, "/ ");
    //判断是否纯音乐
    if ($objectifyNeLyricJsonData -> uncollected == "true") {
      $musicType = "的纯音乐";
    } else {
      $musicType = "演唱的歌曲";
    }
    $mdoutput = '> 【音乐】' .$artistInfo .' ' .$musicType .' ' .$songInfo. '。[网易云音乐](https://music.163.com/song/' .$resid. ')';
  } else {
    $mdoutput = '参数不正确。';
  }
  return $mdoutput;
}

//bilibili api解析
function parseBilibiliApi($avid, $standalone) {
  $requestURL = 'https://api.bilibili.com/x/article/archives?ids=' .$avid. '&jsonp=jsonp';
  $biliRawDocument = file_get_contents($requestURL);
  $ObjectBiliRawDocument = json_decode($biliRawDocument);

  $resHTMLObject = 'https://www.bilibili.com/video/av' .$avid. '/';
  $resTitleObject = $ObjectBiliRawDocument -> data -> $avid -> title;
  //some lovable regex
  $patterns_bg = array();
    $patterns_bg[0] = "/【.+】/";
    $patterns_bg[1] = "/（.+）/";
  $replacements_bg = array();
    $replacements_bg[0] = "";
    $replacements_bg[1] = "";
  ksort($patterns_bg);
  ksort($replacements_bg);
  $resProcessTitle = preg_replace($patterns_bg, $replacements_bg, $resTitleObject);

  if ($standalone == true) {
    $mdoutput = '【视频】' .$resProcessTitle. '。[哔哩哔哩动画](' .$resHTMLObject. ')';
  }
  else {}
}

 ?>
