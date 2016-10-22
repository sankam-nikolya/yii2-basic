<?php
namespace common\modules\minify\components;

use common\modules\minify\View;

/**
 * Class MinifyComponent
 * @package common\modules\minify\components
 */
abstract class MinifyComponent
{

    /**
     * @var View
     */
    protected $view;

    /**
     * MinifyComponent constructor.
     * @param View $view
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    abstract public function export();

    /**
     * @param string $file
     * @return string
     */
    protected function getAbsoluteFilePath($file)
    {
        return \Yii::getAlias($this->view->base_path) . str_replace(\Yii::getAlias($this->view->web_path), '', $this->cleanFileName($file));
    }

    /**
     * @param string $file
     * @return string
     */
    protected function cleanFileName($file)
    {
        return (strpos($file, '?')) ? parse_url($file, PHP_URL_PATH) : $file;
    }

    /**
     * @param string $file
     * @param string $html
     * @return bool
     */
    protected function thisFileNeedMinify($file, $html)
    {
        return !$this->isUrl($file, false) && !$this->isContainsConditionalComment($html);
    }

    /**
     * @param string $url
     * @param boolean $checkSlash
     * @return bool
     */
    protected function isUrl($url, $checkSlash = true)
    {
        $schemas = array_map(function ($val) {
            return str_replace('/', '\/', $val);
        }, $this->view->schemas);

        $regexp = '#^(' . implode('|', $schemas) . ')#is';
        if ($checkSlash) {
            $regexp = '#^(/|\\\\|' . implode('|', $schemas) . ')#is';
        }

        return (bool)preg_match($regexp, $url);
    }

    /**
     * @param string $string
     * @return bool
     */
    protected function isContainsConditionalComment($string)
    {
        return strpos($string, '<![endif]-->') !== false;
    }

    /**
     * @param array $files
     * @return string
     */
    protected function _getSummaryFilesHash($files)
    {
        $result = '';
        foreach ($files as $file => $html) {
            $path = $this->getAbsoluteFilePath($file);

            if ($this->thisFileNeedMinify($file, $html) && file_exists($path)) {
                switch ($this->view->fileCheckAlgorithm) {
                    default:
                    case 'filemtime':
                        $result .= filemtime($path) . $file;
                        break;
                    case 'sha1':
                        $result .= sha1_file($path);
                        break;
                }
            }
        }

        return sha1($result);
    }
}