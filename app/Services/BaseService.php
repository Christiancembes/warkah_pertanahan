<?php

namespace App\Services;

use Closure;

class BaseService
{
  protected function atomic(Closure $callback)
  {
    return \DB::transaction($callback);
  }

  // protected function userService() { return \App::make('\App\Services\User\UserServiceInterface'); }

  protected function parse(
    /* string */ $subject,
    array        $variables,
    /* string */ $escapeChar = '@',
    /* string */ $errPlaceholder = null
) {
    $esc = preg_quote($escapeChar);
    $expr = "/
        $esc$esc(?=$esc*+{)
      | $esc{
      | {(\w+)}
    /x";

    $callback = function($match) use($variables, $escapeChar, $errPlaceholder) {
      switch ($match[0]) {
        case $escapeChar . $escapeChar:
          return $escapeChar;

        case $escapeChar . '{':
          return '{';

        default:
          if (isset($variables[$match[1]])) {
              return $variables[$match[1]];
          }

          return isset($errPlaceholder) ? $errPlaceholder : $match[0];
      }
    };

    return preg_replace_callback($expr, $callback, $subject);
  }
}
