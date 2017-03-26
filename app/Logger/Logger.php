<?php

/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 26.03.17
 * Time: 15:07
 */
namespace App\Logger;

use Psr\Log\AbstractLogger;
use RuntimeException;
use Psr\Log\LogLevel;
use DateTime;


/**
 * Class Logger
 * @package App\Logger
 */
class Logger extends AbstractLogger
{
    /**
     * Core options include the log file path and the log threshold
     *
     * @var array $options
     */
    protected $options = array (
        'extension'      => 'txt',          // default extension
        'dateFormat'     => 'Y-m-d G:i:s',  // default datetime format
        'filename'       => false,
        'flushFrequency' => false,
        'prefix'         => 'log_',         // prefix filename
        'logFormat'      => false,
        'appendContext'  => true,
    );

    /**
     * Path to file
     *
     * @var string
     */
    private $logFilePath;

    /**
     * @var string
     */
    protected $logLevelThreshold = LogLevel::DEBUG;

    /**
     * @var int
     */
    protected $logLineCount = 0;

    /**
     * Log level
     *
     * @var array
     */
    protected $logLevels = [
        LogLevel::EMERGENCY => 0,
        LogLevel::ALERT     => 1,
        LogLevel::CRITICAL  => 2,
        LogLevel::ERROR     => 3,
        LogLevel::WARNING   => 4,
        LogLevel::NOTICE    => 5,
        LogLevel::INFO      => 6,
        LogLevel::DEBUG     => 7
    ];

    private $fileHandler;

    /**
     * @var string
     */
    private $lastLine = '';

    /**
     * Default permission of the log file
     *
     * @var int
     */
    private $defaultPermission = 0777;

    /**
     * Logger constructor.
     *
     * @param $logDirectory
     * @param string $logLevelThreshold
     * @param array $options
     */
    public function __construct($logDirectory, $logLevelThreshold = LogLevel::DEBUG, $options = [])
    {
        $this->logLevelThreshold = $logLevelThreshold;

        $this->options = array_merge($this->options, $options); // combine default and new options

        $logDirectory = rtrim($logDirectory, DIRECTORY_SEPARATOR); // delete dir separ '/' end line

        // if dir do not exist create new dir /logs
        $this->setLogDir($logDirectory);

        $this->setLoggerWritable($logDirectory);
    }

    /**
     * @param $logDirectory
     */
    public function setLoggerWritable($logDirectory)
    {
        if (strpos($logDirectory, 'php://') === 0) {
            $this->setLogToStdOut($logDirectory);
            $this->setFileHandler('w+');
        } else {
            $this->setLogFilePath($logDirectory);

            $this->setFileHandler('a');
        }
    }
    /**
     * Set directory for store file for logs
     *
     * @param $dir
     */
    public function setLogDir($dir)
    {
        if (!$this->checkExistFile($dir)) {
            $this->createDir($dir);
        }
    }

    public function setLogToStdOut(string $stdOutPath)
    {
        $this->logFilePath = $stdOutPath;
    }

    /**
     * Set path to save log file
     *
     * @param string $logDirectory
     */
    public function setLogFilePath($logDirectory) {
        if ($this->options['filename']) { // check set default extension

            $logExtension = strpos($this->options['filename'], '.log');
            $txtExtension = strpos($this->options['filename'], '.txt');

            if ($logExtension || $txtExtension) {
                $this->logFilePath = $logDirectory . DIRECTORY_SEPARATOR . $this->options['filename'];
            } else {
                $this->logFilePath = $logDirectory . DIRECTORY_SEPARATOR . $this->options['filename'] . '.' . $this->options['extension'];
            }

        } else {
            $this->logFilePath = $logDirectory . DIRECTORY_SEPARATOR . $this->options['prefix'] . date('Y-m-d') . '.' . $this->options['extension'];
        }
    }

    /**
     * Check exists select file
     *
     * @param string $path
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
    public function createDir(string $path)
    {
        mkdir($path, $this->defaultPermission, true);
    }

    /**
     * Set file handler
     *
     * @param string $writeMode
     */
    public function setFileHandler(string $writeMode)
    {
        $this->fileHandler = fopen($this->logFilePath, $writeMode);
    }

    /**
     * Check writable select file
     *
     * @param string $file
     * @return bool
     */
    public function isWritableFile(string $file)
    {
        return is_writable($file);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->fileHandler) {
            fclose($this->fileHandler);
        }
    }

    /**
     * Set data format
     *
     * @param string $format
     */
    public function setDateFormat(string $format)
    {
        $this->options['dateFormat'] = $format;
    }

    /**
     * Set log level
     *
     * @param $logLevel
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
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
    public function log($level, $message, array $context = array())
    {
        if ($this->logLevels[$this->logLevelThreshold] < $this->logLevels[$level]) {
            return;
        }

        // generate message log
        $message = $this->formatMessage($level, $message, $context);

        $this->write($message);
    }

    /**
     * Write message logs into logfile
     *
     * @param $message
     */
    public function write($message)
    {
        if ($this->fileHandler !== null) {
            if (fwrite($this->fileHandler, $message) === true) {

                $this->lastLine = trim($message);
                $this->logLineCount++;

                if ($this->options['flushFrequency'] && $this->logLineCount) {
                    fflush($this->fileHandler);
                }
            }
        }
    }

    /**
     * Get the file path that the log is currently writing to
     *
     * @return string
     */
    public function getLogFilePath()
    {
        return $this->logFilePath;
    }

    /**
     * Get the last line logged to the log file
     *
     * @return string
     */
    public function getLastLogLine()
    {
        return $this->lastLine;
    }

    /**
     * Set format log message
     *
     * @param $level
     * @param $message
     * @param $context
     * @return string
     */
    public function formatMessage($level, $message, $context)
    {
        if ($this->options['logFormat']) {

            $padding = str_repeat(' ', 9 - strlen($level));
            $priority = $this->logLevels[$level];
            $context = json_encode($context);
            $level = strtoupper($level);


            $parts = array(
                'date'          => $this->getTimestamp(),
                'level'         => $level,
                'level-padding' => $padding,
                'priority'      => $level,
                'message'       => $message,
                'context'       => $context,
            );

            $message = $this->options['logFormat'];

            foreach ($parts as $part => $value) {
                $message = str_replace($part, $value, $message);
            }

        } else {
            $message = '[' . $this->getTimestamp() . ']' . '[' . $level . ']' . '[' . $message . ']';
        }

        if ($this->options['appendContext'] && !empty($context)) {
            $message = $message . PHP_EOL . $this->indent($this->contextToString($context));
        }

        return $message . PHP_EOL;
    }

    /**
     * Get the date and time in the specified format
     *
     * Format date and time set setting in $options property
     *
     * @return string
     */
    private function getTimestamp()
    {
        // generate date time
        $date = new DateTime(date('Y-m-d H:i:s')); // 2017-03-26 19:37:54.449841

        return $date->format($this->options['dateFormat']);
    }

    /**
     * @param $context
     * @return mixed
     */
    protected function contextToString($context)
    {
        $export = '';

        foreach ($context as $key => $value) {

            $export = $export . $key . ': ';
            $export = $export . preg_replace(
                [
                    '/=>\s+([a-zA-Z])/im',
                    '/array\(\s+\)/im',
                    '/^  |\G  /m'
                ], [
                    '=> $1',
                    'array()',
                    '    '
                ], str_replace('array (', 'array(', var_export($value, true)));

            $export = $export . PHP_EOL;
        }

        return str_replace(['\\\\', '\\\''], ['\\', '\''], rtrim($export));
    }

    /**
     * Set indent
     *
     * @param $string
     * @param string $indent
     * @return string
     */
    protected function indent($string, $indent = '')
    {
        return $indent . str_replace("\n", "\n" . $indent, $string);
    }
}