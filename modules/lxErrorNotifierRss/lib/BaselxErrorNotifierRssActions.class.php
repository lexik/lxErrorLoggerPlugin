<?php

/**
 * @package    lxErrorLoggerPlugin
 * @subpackage actions
 * @author     Lexik <c.girard@lexik.fr>
 */
class BaselxErrorNotifierRssActions extends sfActions
{
  /**
   * Don't use sf_format param to render content as xml, because all partial used
   * in the view must be like *.xml.php even lxErrorLogger/show -_-
   *
   * @param sfWebRequest $request
   */
  public function executeRss(sfWebRequest $request)
  {
    $notifierConfig = sfConfig::get('app_lx_error_logger_plugin_notifier', array());
    $rssConfig = sfConfig::get('app_lx_error_logger_plugin_rss', array());

    $this->forward404Unless($request->getParameter('token') == $rssConfig['token']);

    $this->datas = null;
    $this->partial = null;

    if(isset($notifierConfig['db'], $notifierConfig['db']['enabled']) && $notifierConfig['db']['enabled'])
    {
      $this->partial = 'itemDb';
      $this->datas = Doctrine::getTable('lxErrorLogger')->getLatest($rssConfig['items']);
    }
    else if(isset($notifierConfig['xml'], $notifierConfig['xml']['enabled']) && $notifierConfig['xml']['enabled'])
    {
      $this->partial = 'itemXml';
      $this->datas = lxErrorLogger::getLatestFromXml(
        $notifierConfig['xml']['options']['file_path'].'/'.$notifierConfig['xml']['options']['file_name'],
        $rssConfig['items']
      );
    }

    if(null == $this->partial)
    {
      return sfView::NONE;
    }

    $this->getResponse()->setContentType('text/xml');
    $this->setLayout(false);
  }
}
