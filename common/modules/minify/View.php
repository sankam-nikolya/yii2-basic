<?php
namespace common\modules\minify;

use yii\helpers\FileHelper;

/**
 * Class View
 * @package common\modules\minify
 */
class View extends \yii\web\View
{

    /**
     * @var bool
     */
    public $enableMinify = true;

    /**
     * @var string filemtime or sha1
     */
    public $fileCheckAlgorithm = 'sha1';

    /**
     * @var bool
     */
    public $concatCss = true;

    /**
     * @var bool
     */
    public $minifyCss = true;

    /**
     * @var bool
     */
    public $concatJs = true;

    /**
     * @var bool
     */
    public $minifyJs = true;

    /**
     * @var bool
     */
    public $pack_js = false;

    /**
     * @var bool
     */
    public $removeComments = true;

    /**
     * @var string path alias to web base (in url)
     */
    public $web_path = '@web';

    /**
     * @var string path alias to web base (absolute)
     */
    public $base_path = '@webroot';

    /**
     * @var string path alias to save minify result
     */
    public $minify_path = '@webroot/minify';

    /**
     * @var array positions of js files to be minified
     */
    public $js_position = [self::POS_END, self::POS_HEAD];

    /**
     * @var bool|string charset forcibly assign, otherwise will use all of the files found charset
     */
    public $force_charset = false;

    /**
     * @var bool whether to change @import on content
     */
    public $expand_imports = true;

    /**
     * @var int
     */
    public $css_linebreak_pos = 2048;

    /**
     * @var int|bool chmod of minified file. If false chmod not set
     */
    public $file_mode = 0664;

    /**
     * @var array schemes that will be ignored during normalization url
     */
    public $schemas = ['//', 'http://', 'https://', 'ftp://'];

    /**
     * @var bool do I need to compress the result html page.
     */
    public $compress_output = false;

    /**
     * @var array options for compressing output result
     *   * extra - use more compact algorithm
     *   * no-comments - cut all the html comments
     */
    public $compress_options = ['extra' => true];

    /**
     * @throws \rmrevin\yii\minify\Exception
     */
    public function init()
    {
        parent::init();

        $minify_path = $this->minify_path = (string)\Yii::getAlias($this->minify_path);
        if (!file_exists($minify_path)) {
            FileHelper::createDirectory($minify_path);
        }

        if (!is_readable($minify_path)) {
            throw new Exception('Directory for compressed assets is not readable.');
        }

        if (!is_writable($minify_path)) {
            throw new Exception('Directory for compressed assets is not writable.');
        }

        if (true === $this->compress_output) {
            \Yii::$app->response->on(\yii\web\Response::EVENT_BEFORE_SEND, function (\yii\base\Event $Event) {
                /** @var \yii\web\Response $Response */
                $Response = $Event->sender;
                if ($Response->format === \yii\web\Response::FORMAT_HTML) {
                    if (!empty($Response->data)) {
                        $Response->data = HtmlCompressor::compress($Response->data, $this->compress_options);
                    }

                    if (!empty($Response->content)) {
                        $Response->content = HtmlCompressor::compress($Response->content, $this->compress_options);
                    }
                }
            });
        }
    }

    /**
     * @inheritdoc
     */
    public function endPage($ajaxMode = false)
    {
        $this->trigger(self::EVENT_END_PAGE);

        $content = ob_get_clean();
        foreach (array_keys($this->assetBundles) as $bundle) {
            $this->registerAssetFiles($bundle);
        }

        if (true === $this->enableMinify) {
            (new components\CSS($this))->export();
            (new components\JS($this))->export();
        }

        echo strtr(
            $content,
            [
                self::PH_HEAD => $this->renderHeadHtml(),
                self::PH_BODY_BEGIN => $this->renderBodyBeginHtml(),
                self::PH_BODY_END => $this->renderBodyEndHtml($ajaxMode),
            ]
        );

        $this->clear();
    }
}
