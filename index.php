<?php
/* this is a part of Elad-Karako 's code for parsing multiple url-string using curl lib in PHP, will be used for short url expanding..*/


  require_once('src/encoding.php');
//  require_once ('src/session.php') && set_session("highlight");
//  require_once('src/whitespace_visualizer.php');
  require_once('src/http_headers.php');


  if (false === defined('CURLMSG_DONE')) define('CURLMSG_DONE', 1);
  if (false === defined('CURLM_OK')) define('CURLM_OK', 0);

  $urls = [
    "https://jigsaw.w3.org/HTTP/300/302.html"
  ];

  $curl_handles = array_map(function ($url) {
    $curl_handle = curl_init();
    curl_setopt_array($curl_handle, [
        CURLOPT_URL            => $url// -------------------------------------------- set full target URL.
      , CURLOPT_CONNECTTIMEOUT => 30 // ---------------------------------------- timeout on connect, in seconds
      , CURLOPT_TIMEOUT        => 30 // ---------------------------------------- timeout on response, in seconds
      , CURLOPT_BUFFERSIZE     => 2048 // -------------------------------------------- smaller buffer-size for proxies.
      , CURLOPT_HEADER         => true // -------------------------------------------- return headers too
      , CURLINFO_HEADER_OUT    => true // -------------------------------------------- to use $rh = curl_getinfo($ch); var_dump($rh['request_header']);
      , CURLOPT_RETURNTRANSFER => true // -------------------------------------------- return as string
      , CURLOPT_FAILONERROR    => true // -------------------------------------------- don't fetch error-page's content (500, 403, 404 pages etc..)
      , CURLOPT_SSL_VERIFYHOST => false // ------------------------------------------- don't verify ssl
      , CURLOPT_SSL_VERIFYPEER => false // ------------------------------------------- don't verify ssl
      , CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4 // ------------------------------- force IPv4 (instead of IPv6)
      , CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1// ---------------------------- force HTTP 1.1

   // , CURLOPT_CUSTOMREQUEST => 'HEAD' /* TODO: add for only "HEAD" */
   // , CURLOPT_NOBODY => true          /* TODO: add for only "HEAD" */


      /* redirects */
      , CURLOPT_FOLLOWLOCATION => true
      , CURLOPT_MAXREDIRS      => 5
      , CURLOPT_HTTPHEADER     => http_headers__arr2flat_arr([
            "Accept"          => "*/*"
          , "Connection"      => "keep-alive"
          , "Cache-Control"   => "no-cache"
          , "Pragma"          => "no-cache"
          , "Accept-Language" => "en,en-US;q=0.8"
          , "User-Agent"      => "Mozilla/5.0 Chrome"
          , "Content-Type"    => "text/plain; charset=utf-8"
          , "Referer"         => "http://eladkarako.com/"
        ])
    ]);

    return $curl_handle;
  }, $urls);

  $multi_curl_handle = curl_multi_init();
  foreach ($curl_handles as $curl_handle) curl_multi_add_handle($multi_curl_handle, $curl_handle);

  $is_still_running = null;
  //connect
  do {
    $status = curl_multi_exec($multi_curl_handle, $is_still_running);
    usleep(1000);
  } while (CURLM_CALL_MULTI_PERFORM === $status);

  //wait until download process is done for each curl request
  while (CURLMSG_DONE === $is_still_running && CURLM_OK === $status) {
    do {
      $status = curl_multi_exec($multi_curl_handle, $is_still_running);
    } while ($status === CURLM_CALL_MULTI_PERFORM);
  }

  //read the response of each single-curl request
  $multi_information = array_map(function ($handle) use ($multi_curl_handle) {
    return curl_multi_getcontent($handle);
  }, $curl_handles);


  //remove handles from multi_curl, close each single handle and free memory
  foreach ($curl_handles as $index => $curl_handle) {
    curl_multi_remove_handle($multi_curl_handle, $curl_handle);
    curl_close($curl_handle);
    unset($curl_handle);
  }

  //close the multi_curl handle and free memory
  curl_multi_close($multi_curl_handle);
  unset($multi_curl_handle);

  //-----------------------------------------
  //-----------------------------------------
  //-----------------------------------------


  $multi_information = array_map(function ($information) {
    $information = explode("\r\n\r\n", $information);

    $headers = [];
    $content = [];

    foreach ($information as $index => $segment) {
      if (0 === mb_strpos($segment, "HTTP/", 0)) //is header
        array_push($headers, $segment);
      else
        array_push($content, $segment);
    }

    $content = implode("\r\n\r\n", $content);

    return [
      'headers'   => array_map(function ($header) {
        return http_headers__str2arr($header);
      }, $headers)
      , 'content' => $content
    ];
  }, $multi_information);


  var_dump($multi_information);

?>