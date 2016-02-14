<?php
require "globals.inc.php";

function download($path, $filename) {
  header('Content-Type: application/force-download');
  header('Content-Disposition: attachment;filename="'. $filename . '"');
  $fp = fopen($path . "/". $filename, 'r');
  fpassthru($fp);
  fclose($fp);
}

download($tmp_dir, $_SESSION['uniqueId'].".blast".".out");
