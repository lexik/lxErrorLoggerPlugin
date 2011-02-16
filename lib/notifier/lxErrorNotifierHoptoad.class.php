<?php

/**
 * Notify error on Hoptoad webservice.
 * http://hoptoadapp.com/
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage lib
 * @author     Lexik <c.girard@lexik.fr>
 */
class lxErrorNotifierHoptoad extends lxErrorNotifierBase
{
  /**
   * lxErrorNotifierHoptoad datas
   */
  const NOTIFIER_NAME    = 'lxErrorLoggerPlugin Hoptoad Notifier';
	const NOTIFIER_VERSION = '0.0.1-dev';
	const NOTIFIER_URL     = 'https://github.com/lexik/lxErrorLoggerPlugin';

	/**
	 * Hoptoad datas
	 */
	const HOPTOAD_REQUEST_URL = 'http://hoptoadapp.com/notifier_api/v2/notices';
	const HOPTOAD_REQUEST_XSD = 'http://hoptoadapp.com/hoptoad_2_0.xsd';
	const HOPTOAD_VERSION     = '2.0';

  /**
   * @var DOMDocument
   */
  protected $document = null;

  /**
   * @var string
   */
  protected $responseContent;

  /**
   * @var string
   */
  protected $responseHttpCode;

  /**
   * (non-PHPdoc)
   * @see lxErrorNotifierBase::notify()
   */
  public function notify()
  {
    $this->buildXmlNotice();
    $this->doHoptoadRequest();

    if($this->configuration['log_response'])
    {
      $this->logResponse();
    }

    $this->document = null;
  }

  /**
   * Build an xml notice for Hoptoad v2 API.
   *
   * @see http://help.hoptoadapp.com/kb/api-2/notifier-api-v2
   */
  protected function buildXmlNotice()
  {
    $datas = $this->error->toArray();

    $this->document = new DOMDocument('1.0', 'UTF-8');

    // notice node (root node)
    $noticeNode = $this->document->createElement('notice');

    $version = $this->document->createAttribute('version');
    $version->appendChild($this->document->createTextNode(self::HOPTOAD_VERSION));

    $noticeNode->appendChild($version);

    // notice child - api-key node
    $noticeNode->appendChild($this->document->createElement('api-key', $this->configuration['apiKey']));

    // notice child - notifier node
    $notifierNode = $this->document->createElement('notifier');
    $notifierNode->appendChild($this->document->createElement('name', self::NOTIFIER_NAME));
    $notifierNode->appendChild($this->document->createElement('version', self::NOTIFIER_VERSION));
    $notifierNode->appendChild($this->document->createElement('url', self::NOTIFIER_URL));

    $noticeNode->appendChild($notifierNode);

    // notice child - error node
    $errorNode = $this->document->createElement('error');
    $errorBacktrace = $this->document->createElement('backtrace');

    $this->appendBacktraceChildren($errorBacktrace, $this->error->getTraceAsArray());

    $errorNode->appendChild($this->document->createElement('class', $datas['class']));
    $errorNode->appendChild($this->document->createElement('message', $datas['message']));
    $errorNode->appendChild($errorBacktrace);

    $noticeNode->appendChild($errorNode);

    // notice child - request node
    $requestNode = $this->document->createElement('request');
    $cgiData = $this->document->createElement('cgi-data');

    $this->appendCgiDataChildren($cgiData, $_SERVER);
    $this->appendCgiDataChildren($cgiData, isset($_SESSION) ? $_SESSION : array());

    $requestNode->appendChild($this->document->createElement('url', $datas['url']));
    $requestNode->appendChild($this->document->createElement('component', $datas['module']));
    $requestNode->appendChild($this->document->createElement('action', $datas['action']));
    $requestNode->appendChild($cgiData);

    $noticeNode->appendChild($requestNode);

    // notice child - server-env node
    $serverEnvNode = $this->document->createElement('server-environment');
    $serverEnvNode->appendChild($this->document->createElement('project-root', isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '/'));
    $serverEnvNode->appendChild($this->document->createElement('environment-name', $datas['environment']));

    $noticeNode->appendChild($serverEnvNode);

    $this->document->appendChild($noticeNode);
  }

  /**
   * Append line child node to the given backtrace node.
   *
   * @param DOMNode $backtrace
   * @param array $trace
   */
  private function appendBacktraceChildren($backtrace, $trace)
  {
    foreach ($trace as $line)
    {
      $method = $this->document->createAttribute('method');
      $method->appendChild($this->document->createTextNode(isset($line['function']) ? $line['function'] : ''));

      $file = $this->document->createAttribute('file');
      $file->appendChild($this->document->createTextNode(isset($line['file']) ? $line['file'] : ''));

      $number = $this->document->createAttribute('number');
      $number->appendChild($this->document->createTextNode(isset($line['line']) ? $line['line'] : ''));

      $line = $this->document->createElement('line');
      $line->appendChild($method);
      $line->appendChild($file);
      $line->appendChild($number);

      $backtrace->appendChild($line);
    }
  }

  /**
   * Append var child node to the given cgi-data node.
   *
   * @param DOMNode $cgi
   * @param array $datas
   */
  private function appendCgiDataChildren($cgi, $datas)
  {
    if(is_array($datas))
    {
      foreach ($datas as $name => $value)
      {
        $key = $this->document->createAttribute('key');
        $key->appendChild($this->document->createTextNode($name));

        $var = $this->document->createElement('var', is_array($value) ? var_export($value, true) : $value);
        $var->appendChild($key);

        $cgi->appendChild($var);
      }
    }
  }

  /**
   * Valid the document with the Hoptoad XSD.
   *
   * @return boolean
   */
	protected function validXmlNotice()
	{
    return @$this->document->schemaValidate(dirname(__FILE__).'/../xsd/hoptoad_2_0.xsd');
	}

  /**
   * Submit the xml notice to Hoptoad by using CRUL.
   *
   * @param string $xml
   */
	protected function doHoptoadRequest()
	{
		$handle = curl_init();

		curl_setopt($handle, CURLOPT_URL, self::HOPTOAD_REQUEST_URL);
		curl_setopt($handle, CURLOPT_POST, 1);
		curl_setopt($handle, CURLOPT_HEADER, 0);
		curl_setopt($handle, CURLOPT_TIMEOUT, $this->configuration['timeout']);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $this->document->saveXml());
		curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);

		$this->responseContent = curl_exec($handle);
		$this->responseHttpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		curl_close($handle);
	}

	/**
	 * Log the hoptoad response.
	 */
	protected function logResponse()
	{
	  $str = sprintf("date: %s\nhttp code: %s\nresponse:\n%s---\n",
	    date('Y-m-d H:i:s'),
	    $this->responseHttpCode,
	    $this->responseContent
	  );

	  $filename = sfConfig::get('sf_log_dir').'/lxErrorNotifierHoptoad.log';

    if (!file_exists($filename))
    {
      touch($filename);
      chmod($filename, 0777);
    }

    $handle = @fopen($filename, 'a');
    if (false !== $handle)
    {
      @fwrite($handle, $str);
      @fclose($handle);
    }
	}
}
