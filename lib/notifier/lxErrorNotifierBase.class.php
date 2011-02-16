<?php

/**
 * Base class for all error notifier classes.
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage lib
 * @author     Lexik <j.barthe@lexik.fr>
 */
abstract class lxErrorNotifierBase
{
  /**
   * @var lxError
   */
  protected $error;

  /**
   * @var array
   */
  protected $configuration;

  /**
   * Construct.
   *
   * @param lxError $error
   * @param array $configuration
   */
  public function __construct(lxError $error, array $configuration = array())
  {
    $this->error         = $error;
    $this->configuration = $configuration;
  }

  /**
   * Notify a new error with this notifier.
   */
  abstract public function notify();
}
