<?php

/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 26.03.17
 * Time: 15:07
 */
namespace App\Logger;

use Psr\Log\AbstractLogger;
use DateTime;

/**
 * Class Logger
 *
 * Component for work with logs
 * @package App\Logger
 */
class Logger extends AbstractLogger
{
    /**
     * Prefix which to use when generate name for logfile
     *
     * @var string
     */
    private $prefix = 'log_';

    /**
     * File name for logfile
     *
     * @var bool
     */
    private $filename = false;

    /**
     * Extension log file
     *
     * @var string $extension
     */
    private $extension = 'txt';

    /**
     * Path to file
     *
     * @var string
     */
    private $logFilePath;

    /**
     * This holds the file handle for this instance's log file
     *
     * @var resource $fileHandler
     */
    private $fileHandler;

    /**
     * Default permission of the log file
     *
     * @var int $permission
     */
    private $permission = 0777;

    /**
     * Date format for log message
     *
     * @var string $dateFormat
     */
    private $dateFormat = 'Y-m-d G:i:s';

    /**
     * This holds the last line logged to the logger
     *
     * @var string $lastLine
     */
    private $lastLine = '';

    /**
     * The number of lines logged in this instance's
     *
     * @var int $logLineCount
     */
    protected $logLineCount = 0;

    /**
     * Logger constructor.
     *
     * @param $logDirectory
     * @param array $options
     */
    public function __construct($logDirectory, $options = [])
    {
        // if dir do not exist create new dir /logs
        $this->setLogDir($logDirectory);

        // set path to lo file and file mode
        $this->setLogParam($logDirectory);
    }

    /**
     * Set file name for log file
     *
     * @param string $name
     */
    public function setFileName(string $name)
    {
        $this->filename = $name;
    }

    /**
     * Set data format for log message date
     *
     * @param string $format
     */
    public function setDateFormat(string $format)
    {
        $this->dateFormat = $format;
    }

    /**
     * Set extension for log file
     *
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Set permission for log file
     *
     * @param int $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    /**
     * Get the date and time in the specified format
     *
     * Format date and time set setting in $options property
     *
     * @return string
     */
    public function getTimestamp()
    {
        // generate date time
        $date = new DateTime(date('Y-m-d H:i:s')); // 2017-03-26 19:37:54.449841

        return $date->format($this->dateFormat);
    }

    /**
     * Set log param
     *
     * @param string $logDir
     */
    protected function setLogParam(string $logDir)
    {
        $this->setLogFilePath($logDir); // path to log file
        $this->setFileHandler('a');     // open only for writing
    }

    /**
     * Set directory for store file for logs
     *
     * @param string $logDir
     */
    protected function setLogDir(string $logDir)
    {
        // path to log directory
        $logDir = rtrim($logDir, DIRECTORY_SEPARATOR); // delete dir separ '/' end line

        //check exist directory
        if (!$this->checkExistFile($logDir)) {
            $this->createDir($logDir); // create new directory for logfile
        }
    }

    /**
     * Set path to save log file
     *
     * Check set custom name for log file, if name is not set generate with set prefix data, date and set extension
     *
     * @param string $logDir
     */
    protected function setLogFilePath(string $logDir)
    {
        // check set custom file name
        if ($this->filename) {
            // generate name log file if file name set custom
            $this->logFilePath = $logDir . DIRECTORY_SEPARATOR . $this->filename . '.' . $this->extension;
        } else {
            // generate name log file if filename no set
            $this->logFilePath = $logDir . DIRECTORY_SEPARATOR . $this->prefix . date('Y-m-d') . '.' . $this->extension;
        }
    }

    /**
     * Check exists select file
     *
     * @param string $path
     *
     * @return bool
     */
    public function checkExistFile(string $path)
    {
        return file_exists($path) ? true : false;
    }

    /**
     * Create new directory and set permission for new directory
     *
     * @param string $path
     */
    protected function createDir(string $path)
    {
        mkdir($path, $this->permission, true);
    }

    /**
     * Set file handler
     *
     * @param string $mode
     */
    protected function setFileHandler(string $mode)
    {
        $this->fileHandler = fopen($this->logFilePath, $mode);
    }

    /**
     * Get the file path that the log is currently writing to
     *
     * @return string
     */
    protected function getLogFilePath()
    {
        return $this->logFilePath;
    }

    /**
     * Set format log message
     *
     * @param $level
     * @param $message
     *
     * @return string
     */
    protected function formatMessage($level, $message)
    {
        $message = '[' . $this->getTimestamp() . ']' . '[' . $level . ']' . '[' . $message . ']';

        // [date time][level][message] message format
        return $message . PHP_EOL;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        // generate message log [date time][level][message]
        $message = $this->formatMessage($level, $message);

        // write massage into log file
        $this->write($message);
    }

    /**
     * Write message logs into logfile
     *
     * @param $message
     */
    protected function write($message)
    {
        if ($this->fileHandler) {
            if (!fwrite($this->fileHandler, $message)) {    // check writes the contents to the file
                $this->lastLine = trim($message);           // strip whitespace from the beginning and end of a string
                $this->logLineCount++;                      // increment log count line number
            }
        }
    }
}