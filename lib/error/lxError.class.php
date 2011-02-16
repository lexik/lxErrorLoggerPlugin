<?php

/**
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage lib
 * @author     Lexik <j.barthe@lexik.fr>
 */
class lxError
{
  /*
   * Error types.
   */
  const TYPE_ERROR = 'error';
  const TYPE_EXCEPTION = 'exception';

  protected
    $type,
    $code,
    $class,
    $message,
    $file,
    $line,
    $trace,
    $arrayTrace,
    $similarErrorId = false;

  /**
   * @var lxErrorLoggerService
   */
  protected $errorService = null;

  /**
   * Construct.
   *
   * @param string $type
   * @param int $code
   * @param string $class
   * @param string $message
   * @param string $file
   * @param int $line
   * @param string $trace
   * @param array $arrayTrace
   * @param string $action
   * @param string $module
   */
  public function __construct($type, $code = 0, $class, $message, $file, $line, $trace = null, $arrayTrace = array())
  {
    $this->type       = $type;
    $this->code       = $code;
    $this->class      = $class;
    $this->message    = $message;
    $this->file       = $file;
    $this->line       = $line;
    $this->trace      = $trace;
    $this->arrayTrace = $arrayTrace;
  }

  /**
   * Set the error service object.
   *
   * @param lxErrorLoggerService $errorService
   */
  public function setErrorService($errorService)
  {
    $this->errorService = $errorService;
  }

  /**
   * Returns the error service object.
   *
   * @return lxErrorLoggerService
   */
  public function getErrorService()
  {
    return $this->errorService;
  }

  /**
   * Returns true is this error object represent a php error.
   * @return boolean
   */
  public function isError()
  {
    return ($this->type == self::TYPE_ERROR);
  }

  /**
   * Returns true is this error object represent an exceptionr.
   * @return boolean
   */
  public function isException()
  {
    return ($this->type == self::TYPE_EXCEPTION);
  }

  /**
   * Returns all object attributes as array.
   *
   * @return array
   */
  public function toArray()
  {
    $context = $this->errorService->getContext();
    $moduleName = (false !== $context) ? $context->getModuleName() : null;
    $actionName = (false !== $context) ? $context->getActionName() : null;

    $request = $this->errorService->getRequest();
    $userAgent = (false !== $request) ? $request->getHttpHeader('User-Agent') : null;

    return array(
      'environment'   => $this->getEnvironment(),
      'type'          => $this->type,
      'url'           => $this->getUrl(),
      'code'          => $this->code,
    	'class'				  => $this->class,
      'message'       => $this->message,
      'file'          => $this->file,
      'line'          => $this->line,
      'module'        => $moduleName,
      'action'        => $actionName,
      'trace'         => $this->trace,
      'user_agent'    => $userAgent,
    	'server'        => $this->dump($_SERVER),
      'session'       => $this->dump(isset($_SESSION) ? $_SESSION : null),
      'similar_error' => $this->similarErrorId,
    	'created_at'	  => date('Y-m-d H:i:s'),
    	'updated_at'	  => date('Y-m-d H:i:s'),
    );
  }

  /**
   * Returns an HTML representation of the error.
   *
   * @return string
   */
  public function toHtml()
  {
    return lxErrorRenderer::toHtml($this->toArray());
  }

  /**
   * Returns a string representation of an array.
   *
   * @param array $array
   * @return string
   */
  public function dump($array)
  {
    return var_export($array, true);
  }

  /**
   * Returns the error message.
   *
   * @return string
   */
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * Returns current requested URL.
   *
   * @return string
   */
  protected function getUrl()
  {
    $request = $this->errorService->getRequest();
    if (false !== $request)
    {
      return $request->getUri();
    }
  }

  /**
   * Returns current symfony environment.
   *
   * @return string
   */
  public function getEnvironment()
  {
    return sfConfig::get('sf_environment', 'prod');
  }

  /**
   * Returns the error trace as array.
   *
   * @return array
   */
  public function getTraceAsArray()
  {
    return $this->arrayTrace;
  }

  /**
   * Returns the similar error id.
   *
   * @return mixed
   */
  public function getSimilarErrorId()
  {
    return $this->similarErrorId;
  }

  /**
   * Set the similar error id.
   *
   * @param mixed $similarErrorId
   */
  public function setSimilarErrorId($similarErrorId)
  {
    $this->similarErrorId = $similarErrorId;
  }
}
