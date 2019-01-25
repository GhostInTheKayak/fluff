<?php

define(NL, "\n");
global $info;
$info = [];

/// access the global info array

function set(string $key, $value='') {
  global $info;

  $info[$key] = $value;
}

function get(string $key, $default = NULL) {
  global $info;

  return $info[$key] ?? $default;
}

//  not quite the standard library version

function debug(...$params) {
  $text = '';
  foreach ($params as $param) {
    $text .= ' ' . (is_string($param) ? (string)$param : debug_var($param));
  }
  echo $text, NL;
}

//  taken from our standard library
//  @codingStandardsIgnoreStart
//  suppress the complexity warning to the end of the file

function debug_var($value) {

  // @codingStandardsIgnoreEnd

  switch (gettype($value)) {
    case 'NULL':
      return 'NULL';

    case 'boolean':
      return $value ? 'TRUE' : 'FALSE';

    case 'integer':
    case 'double':
      return (string)$value;

    case 'string':
      $out = str_replace("\n", '\n', $value);
      $out = str_replace("\t", '\t', $out);
      $out = str_replace("\r", '\r', $out);
      return '"' . $out . '"';

    case 'array':
      $out = [];
      foreach ($value as $key => $val) {
        $out[] = debug_var($key) . '=>' . debug_var($val);
      }
      return '[' . implode(', ', $out) . ']';

    case 'object':
      $out = [];
      foreach (get_object_vars($value) as $key => $val) {
        $out[] = debug_var($key) . ': ' . debug_var($val);
      }
      if(count($out)){
        return '{' . get_class($value) . ': ' . implode(', ', $out) . '}';
      }
      return '{' . get_class($value) . '}';

    case 'resource':
      return 'resource "' . get_resource_type($value) . '"';

    default:
      return 'Unknown type "' . gettype($value) . '"';
  }
}
