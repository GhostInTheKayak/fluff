<?php

require_once 'fluff_library.inc';

// set('debug', 1);

// debug($argv);

$reg = [];
$arg_num = 0;

foreach ($argv as $arg) {
  if (preg_match('/--([^=]+)=(.*)/',$arg,$reg)) {
    set($reg[1], $reg[2]);
  } elseif(preg_match('/-([a-zA-Z0-9])/',$arg,$reg)) {
    set($reg[1], TRUE);
  } else {
    set($arg_num++, $arg);
  }
}

global $file_count;
$file_count = get('file_count', 99);

debug('info', $info);

$directory = get('root') ?: get(1) ?: 'unknown';
echo NL, NL, 'Starting at ', $directory, NL, 'info array', $info, NL, NL;

process_directory($directory);


echo 'Finished', NL;


function process_directory($directory) {
  echo 'DIR  ', $directory, NL;

  if(FALSE === @include 'dirs/dir_all.php') {
    echo 'No generic processor for directories', NL;
  }

  $files = glob($directory . "/*");

  // echo 'found ', count($files), NL;

  foreach ($files as $filename) {

    // echo $key, ' = ', $filename, NL;

    if(is_dir($filename)) {
      //  depth first
      process_directory($filename);
    } else {
      process_file($filename);
    }
  }

}

function process_file($filename) {

  //  just limit things while testing
  global $file_count;
  if($file_count-- < 0) {
    die('reached file limit'. NL);
  }

  echo 'FILE ', $filename, NL;

  $parts = explode('.', $filename);
  $suffix = array_pop($parts);

  if(FALSE === @include 'files/all.php') {
    echo 'No generic processor for files', NL;
  }

  if(FALSE === @include 'files/' . $suffix . '.php') {
    echo 'No processor for file type -- ', $suffix, NL;
  }

}
