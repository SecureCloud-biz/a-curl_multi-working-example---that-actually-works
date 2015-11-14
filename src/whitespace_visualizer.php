<?php
  define('WHITESPACE__R', 'ⓡ');
  define('WHITESPACE__N', 'ⓝ');
  define('WHITESPACE__SPACE', 'ⓢ'); //or ␣·⋅ ⋆∙⇢ₒ ․ ‥ … ‧᛫ˑ·
  define('WHITESPACE__TAB', 'ⓣ');

  /**
   * @param string $str
   *
   * @return string
   */
  function whitespace_visualizer($str = "") {
    $replacement_table = [
      /* foo */
      "##"             => ""

      /* order is important- first \r\n than \r than \n */
      , "#\r\n#"       => "**r**n" //handle it later...
      , "#\r#"         => WHITESPACE__R . "\r" //single instances of \r should be treated first (before \r\n pair)
      , "#\n#"         => WHITESPACE__N . "\n" //single instances of \n should be treated first (before \r\n pair)
      , "#\*\*r\*\*n#" => WHITESPACE__R . WHITESPACE__N . "\r\n" //...handle it now

      /* order is not-important */
      , "# #"          => WHITESPACE__SPACE
      , "#\t#"         => WHITESPACE__TAB . "\t"
    ];

    $str = (string)preg_replace(array_keys($replacement_table), array_values($replacement_table), $str);

    return $str;
  }



  //  $headers = whitespace_visualizer($headers);

?>