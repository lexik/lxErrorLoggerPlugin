<?php

/**
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage lib
 * @author     Lexik <j.barthe@lexik.fr>
 */
class lxErrorRouting
{
  /**
   * Add routes for lxErrorNotifierRss module.
   *
   * @param sfEvent $event
   */
  static public function addRouteForNotifierRss(sfEvent $event)
  {
    $event->getSubject()->prependRoute('lx_error_notifier_rss', new sfRoute('/lx-error/rss.xml', array(
      'module' => 'lxErrorNotifierRss',
      'action' => 'rss',
    )));
  }
}
