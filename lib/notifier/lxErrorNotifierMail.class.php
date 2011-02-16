<?php

/**
 * Notify error via email.
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage lib
 * @author     Lexik <j.barthe@lexik.fr>
 */
class lxErrorNotifierMail extends lxErrorNotifierBase
{
  /**
   * (non-PHPdoc)
   * @see lxErrorNotifierBase::notify()
   */
  public function notify()
  {
    if (!$this->error->getSimilarErrorId() || $this->configuration['always_send'])
    {
      $to = $this->configuration['to'];

      if(is_array($to))
      {
        $to = implode(', ', $to);
      }

      $headers  = 'MIME-Version: 1.0' . "\r\n"
                . 'Content-type: text/html; charset=utf-8' . "\r\n"
                . 'From: ' . $to . "\r\n"
                . 'Reply-To: ' . $to . "\r\n"
                . 'X-Mailer: PHP/' . phpversion();

      $content = $this->error->toHtml();

      if(!empty($content))
      {
        mail($to, $this->configuration['subject'], $content, $headers);
      }
    }
  }
}
