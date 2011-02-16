<?php

/**
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage lib
 * @author     Lexik <j.barthe@lexik.fr>
 */
class lxErrorLoggerService
{
  /**
   * PHP erros type labels.
   * @var array
   */
  public static $errorTypes = array(
    1     => 'Error',                  // E_ERROR
    2     => 'Warning',                // E_WARNING
    4     => 'Parsing Error',          // E_PARSE
    8     => 'Notice',                 // E_NOTICE
    16    => 'Core Error',             // E_CORE_ERROR
    32    => 'Core Warning',           // E_CORE_WARNING
    64    => 'Compile Error',          // E_COMPILE_ERROR
    128   => 'Compile Warning',        // E_COMPILE_WARNING
    256   => 'User Error',             // E_USER_ERROR
    512   => 'User Warning',           // E_USER_WARNING
    1024  => 'User Notice',            // E_USER_NOTICE
    2048  => 'Runtime Notice',         // E_STRICT
    4096  => 'Catchable Fatal Error',  // E_RECOVERABLE_ERROR - PHP 5.2
    8192  => 'Deprecated Notice',      // E_DEPRECATED - PHP 5.3
    16384 => 'User Deprecated Notice', // E_USER_DEPRECATED - PHP 5.3
  );

  /**
   * This var is only used to reserve some memory in order to
   * be able to notify fatal errors.
   * @var string
   */
  private static $str = null;

  /**
   * @var sfEventDispatcher
   */
  protected $dispather = null;

  /**
   * @var sfContext
   */
  protected $context = null;

  /**
   * Configuration loaded from app.yml
   * @var array
   */
  protected $configuration  = array();

  /**
   * Construct.
   *
   * @param sfEventDispatcher $dispather
   * @param array $configuration
   */
  public function __construct(sfEventDispatcher $dispather, array $configuration = array())
  {
    $this->dispather     = $dispather;
    $this->configuration = $configuration;

    set_error_handler(array($this, 'errorHandler'), sfConfig::get('app_lx_error_logger_plugin_php_error_reporting'));
		set_exception_handler(array($this, 'exceptionHandler'));
		register_shutdown_function(array($this, 'shutdownHandler'));

    $this->dispather->connect('application.throw_exception', array($this, 'listenToApplicationThrowExceptionEvent'));

    self::reserveMemory();
  }

  /**
   * Call back function used with register_shutdown_function().
   * @see self::__construct()
   */
  public function shutdownHandler()
  {
    self::freeMemory();

    if ($error = error_get_last())
    {
      $this->errorHandler($error['type'], $error['message'], $error['file'], $error['line']);
    }

    return;
  }

  /**
   * Call back function used with set_error_handler().
   * @see self::__construct()
   *
   * @param int $code
   * @param string $message
   * @param string $file
   * @param int $line
   */
  public function errorHandler($code, $message, $file, $line)
  {
    ob_start();
    debug_print_backtrace();
    $trace = ob_get_contents();
    ob_end_clean();

    $class = isset(self::$errorTypes[$code]) ? self::$errorTypes[$code] : null;

    $this->notify(lxError::TYPE_ERROR, $code, $class, $message, $file, $line, $trace, debug_backtrace());
  }

  /**
   * Call back function used with set_exception_handler().
   * @see self::__construct()
   *
   * @param Exception $exception
   */
  public function exceptionHandler($exception)
	{
		$this->notify(lxError::TYPE_EXCEPTION, $exception->getCode(), get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString(), $exception->getTrace());
	}

	/**
	 * Listen the application.throw_exception symfony event to also log sf exceptions
	 * such as sfError404Exception or sfStopException.
	 *
	 * @param sfEvent $event
	 */
  public function listenToApplicationThrowExceptionEvent(sfEvent $event)
  {
    return $this->exceptionHandler($event->getSubject());
  }

  /**
   * Create anew error object. (lxError by default)
   *
   * @param string $type
   * @param int $code
   * @param string $class
   * @param string $message
   * @param string $file
   * @param int $line
   * @param string $trace
   * @param array $arrayTrace
   * @return lxError
   *
   * @throws sfConfigurationException - In case of error does not exist.
   */
  protected function generateError($type, $code, $class, $message, $file, $line, $trace, $arrayTrace)
  {
    if (!class_exists($errorClass = sfConfig::get('app_lx_error_logger_plugin_error_class', 'lxError')))
    {
      throw new sfConfigurationException(sprintf('The %s error class does not exist', $errorClass));
    }

    $error = new $errorClass($type, $code, $class, $message, $file, $line, $trace, $arrayTrace);
    $error->setErrorService($this);

    return $error;
  }

  /**
   * Notify an error with each enabled notifier.
   *
   * @param string $type
   * @param int $code
   * @param string $class
   * @param string $message
   * @param string $file
   * @param int $line
   * @param string $trace
   * @param array $arrayTrace
   *
   * @throws sfConfigurationException
   */
  protected function notify($type, $code, $class, $message, $file, $line, $trace, $arrayTrace)
  {
    $error = $this->generateError($type, $code, $class, $message, $file, $line, $trace, $arrayTrace);

    foreach ($this->configuration as $notifierConfig)
    {
      if ($notifierConfig['enabled'])
      {
        if (!class_exists($notifierClass = $notifierConfig['class']))
        {
          throw new sfConfigurationException(sprintf('The %s service class does not exist', $notifierClass));
        }

        $notifierObject = new $notifierClass($error, isset($notifierConfig['options']) ? $notifierConfig['options'] : array());
        $notifierObject->notify();
      }
    }
  }

  /**
   * Return the sfEventDispatcher instance.
   *
   * @return sfEventDispatcher
   */
  public function getDispather()
  {
    return $this->dispather;
  }

  /**
   * Set the sfContext instance.
   *
   * @param sfContext $context
   */
  public function setContext(sfContext $context)
  {
    $this->context = $context;
  }

  /**
   * Returns a sfContext instance, false otherwise.
   *
   * @return mixed
   */
  public function getContext()
  {
    if (null === $this->context)
    {
      return sfContext::hasInstance() ? sfContext::getInstance() : false;
    }

    return $this->context;
  }

  /**
   * Returns a request, false otherwise.
   *
   * @return mixed
   */
  public function getRequest()
  {
    return (false !== $this->getContext()) ? $this->context->getRequest() : false;
  }

  /**
   * Returns the current configuration.
   *
   * @return array
   */
  public function getConfiguration()
  {
    return $this->configuration;
  }

  /**
   * Create a string to reserve some memory.
   * Inspired by sfErrorNotifierPlugin http://svn.symfony-project.com/plugins/sfErrorNotifierPlugin/trunk/lib/sfErrorNotifierErrorHandler.php
   */
  protected static function reserveMemory()
  {
    if(sfConfig::get('app_lx_error_logger_plugin_reserve_memory', false))
    {
      self::$str = str_repeat('x', 1024 * 500);
    }
  }

  /**
   * Free reserved memory.
   * Inspired by sfErrorNotifierPlugin http://svn.symfony-project.com/plugins/sfErrorNotifierPlugin/trunk/lib/sfErrorNotifierErrorHandler.php
   */
  protected static function freeMemory()
	{
	  self::$str = null;
	}
}
