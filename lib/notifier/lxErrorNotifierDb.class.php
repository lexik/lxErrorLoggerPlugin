<?php

/**
 * Notify error on a database.
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage lib
 * @author     Lexik <j.barthe@lexik.fr>
 */
class lxErrorNotifierDb extends lxErrorNotifierBase
{
  /**
   * @var PDO
   */
  protected $dbh;

  /**
   * (non-PHPdoc)
   * @see lxErrorNotifierBase::notify()
   */
  public function notify()
  {
    try
    {
      if(isset($this->configuration['dsn']))
      {
        $this->dbh = new PDO($this->configuration['dsn'], $this->configuration['username'], $this->configuration['password'], $this->configuration['driver_options']);
      }
      else
      {
        $this->dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
      }

      if(isset($this->configuration['similar_error']) && $this->configuration['similar_error'])
      {
        $this->error->setSimilarErrorId($this->getSimilarErrorId());
      }

      $sql = "INSERT INTO lx_error_logger
      				(`environment`, `type`, `url`, `code`, `class`, `message`, `file`, `line`, `module`, `action`, `trace`, `user_agent`, `server`, `session`, `similar_error`, `created_at`, `updated_at`)
      				VALUES (:environment, :type, :url, :code, :class, :message, :file, :line, :module, :action, :trace, :user_agent, :server, :session, :similar_error, :created_at, :updated_at)";

      $sth = $this->dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $sth->execute($this->error->toArray());
    }
    catch (Exception $e)
    {
      //var_dump($e); exit;
    }
  }

  /**
   * Returns the first similar error with current error.
   *
   * @return mixed Integer or false
   */
  protected function getSimilarErrorId()
  {
    $error = $this->error->toArray();

    $sql = sprintf("SELECT l.id AS id
            				FROM lx_error_logger l
            				WHERE l.url = :url
            				AND l.type = :type
            				AND l.code = :code
            				%s
            				AND l.message = :message
            				AND l.file = :file
            				AND l.line = :line
            				ORDER BY l.created_at
            				LIMIT 1",
      (null !== $error['class']) ? "AND l.class = :class" : ""
    );

    $sth = $this->dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array_merge(array(
      'url'     => $error['url'],
    	'type'    => $error['type'],
      'code'    => $error['code'],
    	'message' => $error['message'],
      'file'    => $error['file'],
      'line'    => $error['line'],
    ), (null !== $error['class']) ? array('class' => $error['class']) : array()));

    return $sth->fetchColumn();
  }
}
