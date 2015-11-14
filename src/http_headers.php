<?php

  /**
   * @param array $headers
   *
   * @return string
   */
  function http_headers__arr2flat_arr($headers = []) {
    /* key => val  -to-  key: val */
    $headers = array_map(function ($key, $value) {
      return $key . ': ' . $value;
    }, array_keys($headers), array_values($headers));

    return $headers;
  }


  /**
   * @param array $headers
   *
   * @return string
   */
  function http_headers__arr2str($headers = []) {
    /* key => val  -to-  key: val */
    $headers = array_map(function ($key, $value) {
      return $key . ': ' . $value;
    }, array_keys($headers), array_values($headers));

    /* [key1: val1, key2: val2]  -to-  key1: val1\r\nkey2: val2  */
    $headers = implode("\r\n", $headers);

    /* http-header's end is marked by another "\r\n" */
    $headers .= "\r\n\r\n";

    return $headers;
  }


  /**
   * @param string $headers
   *
   * @return array
   */
  function http_headers__str2arr($headers = "") {
    $arr = [];

    $headers = explode("\r\n", $headers);

    foreach ($headers as $index => $header) {
      $header = explode(": ", $header, 2); //limit to one match.

      if (1 === count($header)) array_unshift($header, $index); //fix "key" to be the index, in case there is no ': ' delimiter.

      $key = $header[0];
      $value = $header[1];

      $arr[ $key ] = $value;
    }

    return $arr;
  }


  $headers_str = ""
                 . "HTTP/1.1 200 OK" . "\r\n"
                 . "Date: Sat, 07 Nov 2015 18:21:44 GMT" . "\r\n"
                 . "Server: Apache/2.4.10 (Win32) OpenSSL/1.0.1i PHP/5.6.3" . "\r\n"
                 . "X-Powered-By: PHP/5.6.3" . "\r\n"
                 . "Content-Length: 397" . "\r\n"
                 . "Keep-Alive: timeout=5, max=100" . "\r\n"
                 . "Connection: Keep-Alive" . "\r\n"
                 . "Content-Type: text/plain; charset=utf-8";

  $headers_arr = [
    '0'                => 'HTTP/1.1 200 OK'
    , 'Date'           => 'Sat, 07 Nov 2015 18:21:44 GMT'
    , 'Server'         => 'Apache/2.4.10 (Win32) OpenSSL/1.0.1i PHP/5.6.3'
    , 'X-Powered-By'   => 'PHP/5.6.3'
    , 'Content-Length' => 397
    , 'Keep-Alive'     => 'timeout=5, max=100'
    , 'Connection'     => 'Keep-Alive'
    , 'Content-Type'   => 'text/plain; charset=utf-8'
  ];


  //  var_dump(
  //    json_encode(http_headers__str2arr($headers_str))
  //    json_encode(http_headers__arr2str($headers_arr))
  //  );


  /*
HTTP/1.1 200 OK
Date: Sat, 07 Nov 2015 18:21:44 GMT
Server: Apache/2.4.10 (Win32) OpenSSL/1.0.1i PHP/5.6.3
X-Powered-By: PHP/5.6.3
Content-Length: 397
Keep-Alive: timeout=5, max=100
Connection: Keep-Alive
Content-Type: text/plain; charset=utf-8
Request Headers

   */


  //  $headers = http_headers__arr2str($headers);
  //  $headers = whitespace_visualizer($headers);
