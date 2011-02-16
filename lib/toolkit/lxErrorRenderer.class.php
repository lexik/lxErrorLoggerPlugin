<?php

/**
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage lib
 * @author     Lexik <c.girard@lexik.fr>
 */
class lxErrorRenderer
{
  /**
   * Render the error as html.
   *
   * @param array $values
   * @return string
   */
  public static function toHtml($values)
  {
    $file = sfConfig::get('app_lx_error_logger_plugin_error_html_template', '');

    if(!is_readable($file))
    {
      return;
    }

    extract(array('lx_error' => $values));

    ob_start();
    ob_implicit_flush(false);
    require($file);

    return ob_get_clean();
  }
}
