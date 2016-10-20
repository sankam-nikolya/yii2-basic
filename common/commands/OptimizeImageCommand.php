<?php

namespace common\commands;

use Yii;
use yii\base\Object;
use common\models\FileStorageItem;
use common\models\TinyPng;
use trntv\bus\interfaces\SelfHandlingCommand;

use Kinglozzer\TinyPng\Compressor;
use Kinglozzer\TinyPng\Exception\AuthorizationException;
use Kinglozzer\TinyPng\Exception\InputException;
use Kinglozzer\TinyPng\Exception\LogicException;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class OptimizeImageCommand extends Object implements SelfHandlingCommand
{
    /**
     * @var string
     */
    public $image;

    /**
     * @param OptimizeImageCommand $command
     * @return bool
     */
    public function handle($command)
    {
        $this->optimize();
    }

    private function Optimize()
    {
        //$filesystem = Yii::$app->fileStorage->filesystem;

        $api_key = $this->getKey();

        if(!$api_key) {
            return false;
        }

        $compressor = new Compressor($api_key);

        $path = $this->image->getPath();

        try {
            $result = $compressor->compress($this->image->read(), true);

            $body = $result->getResponseData();

            if (! isset($body['output']['url'])) {
                // throw new \Exception('Compressed image URL missing from response body');
                return false;
            }

            $result->getRequest()->setUrl($body['output']['url']);
            $response = $result->getRequest()->send();
            $size = $result->getCompressedFileSize();
            $image = $response->getBody();

            if($this->image->put($image)) {
                $model = FileStorageItem::findOne(['path' => $path]);
                $model->size = $size;
                $model->optimized = 1;
                $model->save();
            }

        } catch (AuthorizationException $e) {
            $this->setInvalidKey($api_key);
            $this->optimize();
        } catch (InputException $e) {
            $model = FileStorageItem::findOne(['path' => $path]);
            $model->optimized = 3;
            $model->save();
        } catch (Exception $e) {
            // Unknown error
        }
    }

    private function getKey()
    {
        $item = TinyPng::find()->where(['<=', 'valid_from', strtotime(date('d.m.Y') . ' 00:00:00')])->active()->one();
        if(!empty($item)) {
            return $item->key;
        } else {
            return false;
        }
    }

    private function setInvalidKey($key)
    {
        $item = TinyPng::findOne(['key' => $key]);

        if(!empty($item)) {
            $item->valid_from = date("d.m.Y", strtotime("+1 month", strtotime(date("d.m.Y"))));
            return $item->save();
        } else {
            return false;
        }
    }
}
