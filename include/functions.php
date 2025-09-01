<?php 

if (!function_exists('getConfig')) {
  function getConfig($value) {
      $env = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/.env', false, INI_SCANNER_RAW);

      $environment = $env['environment'];

      return $env[strtoupper($environment.'_'.$value)];
  }
}