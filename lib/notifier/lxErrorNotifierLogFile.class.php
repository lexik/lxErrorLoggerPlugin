<?php

/**
 * Notify error on a log file.
 *
 * @package    lxErrorLoggerPlugin
 * @subpackage lib
 * @author     Lexik <c.girard@lexik.fr>
 */
class lxErrorNotifierLogFile extends lxErrorNotifierBase
{
  /**
   * (non-PHPdoc)
   * @see lxErrorNotifierBase::notify()
   */
  public function notify()
  {
    $line = $this->generateLogLine();
    $this->writeLogLine($line);
  }

  /**
   * Generate the error log line.
   *
   * @return string
   */
  protected function generateLogLine()
  {
    $values = array('%separator%' => '|');

    foreach ($this->error->toArray() as $key => $value)
    {
      $values[sprintf('%%%s%%', $key)] = $value;
    }

    return strtr($this->configuration['line_pattern'], $values) . "\n";
  }

  /**
   * Write the log line into the file.
   *
   * @param string $line
   */
  protected function writeLogLine($line)
  {
    $file = $this->configuration['file_path'] . DIRECTORY_SEPARATOR . $this->configuration['file_name'];

    if (!file_exists($file))
    {
      touch($file);
      chmod($file, 0777);
    }

    $handle = @fopen($file, 'a');
    if (false !== $handle)
    {
      @fwrite($handle, $line);
      @fclose($handle);
    }
  }
}