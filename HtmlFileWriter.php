<?php
/**
 * Html Log File Writer
 *
 * Use this custom log writer to output log messages
 * to a daily, weekly, monthly, or yearly log file about
 * the users operations in fresh HTML. Log files will
 * inherently rotate based on the specified log file
 * name format and the current time.
 *
 * USAGE
 *
 * $app = new \Slim\Slim(array(
 *     'log.writer' => new \Slim\Logger\HtmlFileWriter()
 * ));
 *
 * SETTINGS
 *
 * You may customize this log writer by passing an array of
 * settings into the class constructor. Available options
 * are shown above the constructor method below.
 *
 * @author      Davide Pastore <pasdavide@gmail.com>
 * @copyright   2014 Davide Pastore
 * @version     0.1.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Slim\Logger;

class HtmlFileWriter
{
    /**
     * @const string
     */
    const VERSION = '0.1.0';
    
    /**
     * @var \DOMDocument
     */
    protected $doc;

    /**
     * @var array
     */
    protected $settings;

    /**
     * The base html skeleton.
     * @var unknown
     */
    protected $htmlSkeleton = '<html><head><title>Logger</title></head><body><table id="log"><thead><tr><th>Label</th><th>Date</th><th>Message</th></tr></thead><tbody id="log-body"></tbody></table></body></html>';
    
    /**
     * Constructor
     *
     * Prepare this log writer. Available settings are:
     *
     * path:
     * (string) The relative or absolute filesystem path to a writable directory.
     *
     * name_format:
     * (string) The log file name format; parsed with `date()`.
     *
     * date_format:
     * (string) The log message date format; parsed with `date()`.
     *
     * extension:
     * (string) The file extension to append to the `filename`.
     *
     * @param   array $settings
     * @return  void
     */
    public function __construct($settings = array())
    {
        //Merge user settings
        $this->settings = array_merge(array(
            'path' => './logs',
            'name_format' => 'Y-m-d',
            'extension' => 'html',
            'date_format' => 'c'
        ), $settings);

        //Remove trailing slash from log path
        $this->settings['path'] = rtrim($this->settings['path'], DIRECTORY_SEPARATOR);
    }

    /**
     * Write to log
     *
     * @param   mixed $object
     * @param   int   $level
     * @return  void
     */
    public function write($object, $level)
    {
        //Determine label
        $label = 'DEBUG';
        switch ($level) {
            case \Slim\Log::EMERGENCY:
                $label = 'EMERGENCY';
                break;
            case \Slim\Log::ALERT:
                $label = 'ALERT';
                break;
            case \Slim\Log::CRITICAL:
                $label = 'CRITICAL';
                break;
            case \Slim\Log::ERROR:
                $label = 'ERROR';
                break;
            case \Slim\Log::WARN:
                $label = 'WARN';
                break;
            case \Slim\Log::NOTICE:
                $label = 'NOTICE';
                break;
            case \Slim\Log::INFO:
                $label = 'INFO';
                break;
        }
        
        $filename = date($this->settings['name_format']);
        if (! empty($this->settings['extension'])) {
            $filename .= '.' . $this->settings['extension'];
        }
        
        $filePath = $this->settings['path'] . DIRECTORY_SEPARATOR . $filename;

        //Open document handle to log file
        if (!$this->doc) {
            //Creating the DOMDocument
            $this->doc = new \DOMDocument('1.0');
            
            //Add the skeleton
            if(file_exists($filePath)){
                $this->doc->loadHTML(file_get_contents($filePath));
            }
            else{
                $this->doc->loadHTML($this->htmlSkeleton);
            }
        }
        
        //Create the row
        $table = $this->doc->getElementById('log-body');
        
        $tr = $this->doc->createElement('tr');
        $table->appendChild($tr);

        $td = $this->doc->createElement('td', $label);
        $tr->appendChild($td);

        $td = $this->doc->createElement('td', date($this->settings['date_format']));
        $tr->appendChild($td);
        
        $td = $this->doc->createElement('td', (string)$object);
        $tr->appendChild($td);
        
        $table->appendChild($tr);
        
        $this->doc->saveHTMLFile($filePath);
    }
}
