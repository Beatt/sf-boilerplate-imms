<?php


namespace AppBundle\Util;


class CVSUtil
{

  /**
   * @param array values
   * @return string CVS line
   */
  public static function arrayToCsvLine(array $values, $delimiter=',') {
    $line = '';

    $values = array_map(function ($v) {
      return '"' .
        ( str_replace('"', '""', strval($v))  )
        . '"';
    }, $values);

    $line .= implode($delimiter, $values);

    return $line;
  }

}