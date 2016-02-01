<?php

/**
 * creates an object that can conditionally write messages to the server logs for debugging / error tracking / warnings.
 * intended to be called with a global constant from config.php based on the LOGLEVEL constant - which should determine what level you want for your environment (prod vs dev).
 * 
 * @author Chris Keen 
 */
class LogUtil {
    private $logLevel, $messageType , $destination, $enableConsoleMessages = false;
    
    /**
     * constructor
     * 
     * @param  int  $loglevel  the numeric (or constant) representing the minimum severity to log.
     * @param  int  $messageType  this corresponds to PHP's built in index for errorLog()'s second parameter: http://us.php.net/manual/en/function.error-log.php
     * @param  string  $destination  file path.  only necessary if $messageType == 3 || 1
     */
    public function __construct($logLevel = LOG_ERROR_MESSAGES, $messageType = 0, $destination = 'logUtilErrorLog.txt'){
        $this->logLevel = $this->checkLogLevel($logLevel);
        $this->messageType = $messageType;
        $this->destination = $destination;
    }
    
    /**
     * writes debug messages to the log, if a debug or lower
     * @param string $message - the message to record
     */
    public function debug($message) {
        if (LOG_DEBUG_MESSAGES == $this->logLevel){
            error_log($message,$this->messageType,$this->destination);
            $this->printToConsole($message);
        }
    }
    
    /**
     * writes warnings messages if the level is warn or lower
     * @param string $message - the message to record
     */
    public function warn($message){
        if (LOG_WARNINGS >= $this->logLevel){
            error_log($message,$this->messageType,$this->destination);
            $this->printToConsole($message);
        }
    }
    
    /**
     * this will unconditionally write an error to the log, regardless of the error level.  We do not EVER want to prevent logging errors
     * @param string $message - the message to record
     */
    public function error($message) {
        error_log($message,$this->messageType,$this->destination);
        $this->printToConsole($message);
    }
    
    /**
     * setter for enabling logging via JS's console.log
     * @param bool $enable - to log or not to log, that is the param. Optional.  Defaults to true.
     */
    public function enableConsole($enable = true){
        $this->enableConsoleMessages = $enable;
    }
    
    /**
     * prints messages to Firefox's Firebug / Webkits / even IE8's console object:
     * http://getfirebug.com/console.html
     * @param string $message - the message to record
     */
    private function printToConsole($message){
        if ($this->enableConsoleMessages){
            //escape any single ticks to prevent broken strings - str_replace is less expensive than preg
            $message = str_replace("'","\\\\'",$message);
            echo "<script type=\"text/javascript\">console.log('$message')</script>\n\n";            
        }
    }
    
    /**
     * helper that checks the log level to determine how to set it from the constructor
     * @param mixed $level - the parameter passed to this class' constructor
     * @param global constant reference $logLevel - the constant set in config.php that corresponds to the type of errors we desire to log
     */
    private function checkLogLevel($level){
        if (is_numeric($level)){
            if (LOG_ERRORS == $level || LOG_DEBUG_MESSAGES == $level || LOG_WARNINGS == $level){
                return $level;
            }
            else {
                error_log("LogUtil was unable to determine the log level.  supplied: " . $level,4,WEBMASTER);
                return LOG_ERRORS;
            }
        }
        
        else if (is_string($level)){
            //FIXME: regX to parse the log level.
            return LOG_ERRORS;
        }
        
        else {
            return LOG_ERRORS;
            error_log("LogUtil was unable to determine the log level.  supplied: " . $level,4,WEBMASTER);
        } 
    }
    
}