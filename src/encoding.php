<?php
  mb_internal_encoding('UTF-8');
  mb_language("uni");
  setlocale(LC_ALL, 'en_US.UTF-8');
  while (ob_get_level() > 0) ob_end_flush();
  header('Content-Type: text/plain; charset=UTF-8');
?>