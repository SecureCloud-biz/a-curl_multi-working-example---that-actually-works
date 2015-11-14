<?php

  function set_session($str = "PHPSESSID") {
    if ('' !== session_id()) return;

    @ini_set("session.cookie_httponly", 1);
    session_name($str);
    session_start();
    $_SESSION['uniqueID'] = true === isset($_SESSION['uniqueID']) ? $_SESSION['uniqueID'] : uniqid();
  }

?>