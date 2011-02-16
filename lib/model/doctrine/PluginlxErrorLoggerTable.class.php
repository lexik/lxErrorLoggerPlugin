<?php

/**
 * PluginlxErrorLoggerTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage model
 * @author     Lexik <j.barthe@lexik.fr>
 */
class PluginlxErrorLoggerTable extends Doctrine_Table
{
	/**
   * Returns an instance of this class.
   *
   * @return object lxErrorLoggerTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('lxErrorLogger');
  }

  /**
   * Returns the right connection to use with this compoment.
   *
   * @return PDO
   */
  public function getErrorLoggerConnection()
  {
    $notifierConfig = sfConfig::get('app_lx_error_logger_plugin_notifier', array());

    if(isset($notifierConfig['db']['options'], $notifierConfig['db']['options']['dsn']))
    {
      $config = $notifierConfig['db']['options'];

      return new PDO($config['dsn'], $config['username'], $config['password'], $config['driver_options']);
    }
    else
    {
      return Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    }
  }

  /**
   * Returns the $limit latest error as array.
   *
   * @param int $limit
   * @return array
   */
  public function getLatest($limit = 20)
  {
    $dbh = $this->getErrorLoggerConnection();

    $sql = sprintf('SELECT lel.* FROM %s lel ORDER BY lel.created_at DESC LIMIT %d', $this->getTableName(), (int) $limit);

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    return $result;
  }
}
