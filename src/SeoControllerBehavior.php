<?php

namespace laco\seo;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class SeoControllerBehavior
 *
 * @link http://laco.pro
 * @copyright Copyright (c) Laco Digital Agency
 * Date: 06.07.2017
 */
class SeoControllerBehavior extends Behavior
{
    /**
     * @param $data array|ActiveRecord with SeoModelBehavior
     *
     * Array format:
     * [
     *   'metaTitle' => '',
     *   'metaDescription' => '',
     *   'metaImage' => ''
     * ]
     */
    public function setMetaTags($data)
    {
        if ($this->hasUploadBehavior($data)) {
            $data = [
                'metaTitle' => $data->getMetaTitle(),
                'metaDescription' => $data->getMetaDescription(),
                'metaImage' => $data->getMetaImage()
            ];
        }

        Yii::$app->view->title = $data['metaTitle'];

        Yii::$app->view->registerMetaTag([
            'name' => 'title',
            'content' => $data['metaTitle'],
        ]);
        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $data['metaDescription'],
        ]);
        Yii::$app->view->registerMetaTag([
            'name' => 'image_src',
            'content' => $data['metaImage'],
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'og:title',
            'content' => $data['metaTitle'],
        ]);
        Yii::$app->view->registerMetaTag([
            'name' => 'og:description',
            'content' => $data['metaDescription'],
        ]);
        Yii::$app->view->registerMetaTag([
            'name' => 'og:image',
            'content' => $data['metaImage'],
        ]);
        Yii::$app->view->registerMetaTag([
            'name' => 'og:url',
            'content' => Yii::$app->request->getAbsoluteUrl(),
        ]);
    }

    protected function hasUploadBehavior($data)
    {
        if (!($data instanceof ActiveRecord)) {
            return false;
        }
        foreach ($data->getBehaviors() as $behavior) {
            if ($behavior instanceof SeoModelBehavior) {
                return true;
            }
        }

        return false;
    }
}