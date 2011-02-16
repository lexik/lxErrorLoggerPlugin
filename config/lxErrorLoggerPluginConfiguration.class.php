<?php

/**
 * lxErrorLoggerPluginConfiguration
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage config
 * @author     Lexik <j.barthe@lexik.fr>
 */
class lxErrorLoggerPluginConfiguration extends sfPluginConfiguration
{
  /**
   * Plugin version
   * @var string
   */
  const VERSION = '0.3.0-DEV';

  /**
   * @var lxErrorLoggerService
   */
  protected $errorLoggerService = null;

  /**
   * (non-PHPdoc)
   * @see sfPluginConfiguration::initialize()
   */
  public function initialize()
  {
    if (sfConfig::get('app_lx_error_logger_plugin_enabled', false))
    {
      if (!class_exists($serviceClass = sfConfig::get('app_lx_error_logger_plugin_service_class', 'lxErrorLoggerService')))
      {
        throw new sfConfigurationException(sprintf('The %s service class does not exist', $serviceClass));
      }

      $configuration = sfConfig::get('app_lx_error_logger_plugin_notifier', array());
      $this->errorLoggerService = new $serviceClass($this->dispatcher, $configuration);

      $this->dispatcher->connect('context.load_factories', array($this, 'listenToContextLoadFactoriesEvent'));

      // routes
      foreach (array('lxErrorNotifierRss') as $module)
      {
        if (in_array($module, sfConfig::get('sf_enabled_modules', array())))
        {
          $this->dispatcher->connect('routing.load_configuration', array('lxErrorRouting', 'addRouteFor'.str_replace('lxError', '', $module)));
        }
      }
    }
  }

  /**
   * Listen to context.load_factories event to get a sfContext instance.
   *
   * @param sfEvent $event
   */
  public function listenToContextLoadFactoriesEvent(sfEvent $event)
  {
    $context = $event->getSubject();

    $this->errorLoggerService->setContext($context);
    $context->set('lx_error_logger', $this->errorLoggerService);
  }
}
