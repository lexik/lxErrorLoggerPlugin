<?php

/**
 * Notify error on a xml file.
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage lib
 * @author     Lexik <c.girard@lexik.fr>
 */
class lxErrorNotifierXml extends lxErrorNotifierBase
{
  /**
   * @var string
   */
  protected $file = null;

  /**
   * @var DOMDocument
   */
  protected $document = null;

  /**
   * Construct.
   *
   * @param lxError $error
   * @param array $configuration
   */
  public function __construct(lxError $error, array $configuration = array())
  {
    parent::__construct($error, $configuration);

    $this->file = $this->configuration['file_path'] . DIRECTORY_SEPARATOR . $this->configuration['file_name'];
    $this->document = new DOMDocument('1.0', 'UTF-8');
  }

  /**
   * (non-PHPdoc)
   * @see lxErrorNotifierBase::notify()
   */
  public function notify()
  {
    if (!file_exists($this->file))
    {
      if (!file_exists($this->configuration['file_path']))
      {
        mkdir($this->configuration['file_path'], 0777, true);
      }

      $this->document->appendChild($this->document->createElement('errors'));
    }
    else
    {
      $this->document->load($this->file);
    }

    if (isset($this->configuration['similar_error']) && $this->configuration['similar_error'])
    {
      $this->error->setSimilarErrorId($this->getSimilarErrorId());
    }

    $this->addErrorNode();

    $this->document->save($this->file);
  }

  /**
   * Add a new error node to the DOM document.
   *
   */
  protected function addErrorNode()
  {
    $datas = $this->error->toArray();

    $errorNode = $this->document->createElement('error');

    $id = $this->document->createAttribute('id');
    $id->appendChild($this->document->createTextNode(md5(time().rand(10000, 99999))));
    $errorNode->appendChild($id);

    $errorNode->appendChild($this->document->createElement('environment', $datas['environment']));
    $errorNode->appendChild($this->document->createElement('type', $datas['type']));
    $errorNode->appendChild($this->document->createElement('code', $datas['code']));
    $errorNode->appendChild($this->document->createElement('class', $datas['class']));
    $errorNode->appendChild($this->document->createElement('message', $datas['message']));
    $errorNode->appendChild($this->document->createElement('file', $datas['file']));
    $errorNode->appendChild($this->document->createElement('line', $datas['line']));
    $errorNode->appendChild($this->document->createElement('module', $datas['module']));
    $errorNode->appendChild($this->document->createElement('action', $datas['action']));
    $errorNode->appendChild($this->document->createElement('trace', $datas['trace']));
    $errorNode->appendChild($this->document->createElement('user_agent', $datas['user_agent']));
    $errorNode->appendChild($this->document->createElement('server', $datas['server']));
    $errorNode->appendChild($this->document->createElement('session', $datas['session']));
    $errorNode->appendChild($this->document->createElement('url', $datas['url']));
    $errorNode->appendChild($this->document->createElement('similarError', $datas['similar_error']));
    $errorNode->appendChild($this->document->createElement('created_at', $datas['created_at']));

    $this->document->getElementsByTagName('errors')->item(0)->appendChild($errorNode);
  }

  /**
   * Returns the similar error md5 id.
   *
   * @return string
   */
  protected function getSimilarErrorId()
  {
    $similar = 'none';
    $datas = $this->error->toArray();

    $query = sprintf('/errors/error[url="%s"][type="%s"][code=%s][message="%s"][file="%s"][line=%s][similarError="none"]%s',
      $datas['url'],
      $datas['type'],
      $datas['code'],
      $datas['message'],
      $datas['file'],
      $datas['line'],
      !empty($datas['class']) ? sprintf('[class="%s"]', $datas['class']) : ''
    );

    $xpath = new DOMXPath($this->document);
    $nodeList = $xpath->query($query);

    if($nodeList->length > 0)
    {
      $node = $nodeList->item(0)->attributes->getNamedItem('id');
      if($node instanceof DOMNode)
      {
        $similar = $node->nodeValue;
      }
    }

    return $similar;
  }
}