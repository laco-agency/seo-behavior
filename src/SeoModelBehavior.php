<?php
namespace laco\seo;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use common\components\modelUploader\UploadBehaviour;
use common\components\modelUploader\storage\ModelStorage;
use common\components\modelUploader\processor\ImageProcessor;

/**
 * Class SeoModelBehavior
 *
 * @link http://laco.pro
 * @copyright Copyright (c) Laco Digital Agency
 * Date: 20.02.2017
 */
class SeoModelBehavior extends Behavior
{
    public $metaTitleAttribute = 'meta_title';
    public $metaDescriptionAttribute = 'meta_description';
    public $metaImageAttribute = 'meta_image';

    public $imageUploaderOptions = [
        'origin' => ['width' => 540, 'height' => 240, 'crop' => true],
        'thumb' => ['width' => 165, 'height' => 218, 'crop' => true],
    ];

    /**
     * @var string Имя аттрибута и которого будет браться значение. false для отключения
     */
    public $titleFromAttribute = 'title';
    /**
     * @var string Имя аттрибута и которого будет браться значение. false для отключения
     */
    public $descriptionFromAttribute = 'description';

    /**
     * @var string [Пока не работает!]Имя аттрибута и которого будет браться значение. false для отключения
     */
    public $imageFromAttribute = false;

    /**
     * Declares events and the corresponding event handler methods.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            BaseActiveRecord::EVENT_INIT => 'afterInitOwner'
        ];
    }

    public function afterInitOwner()
    {
        /** @var ActiveRecord|UploadBehaviour $owner */
        $owner = $this->owner;

        if ($this->hasUploadBehavior()) {
            $owner->uploadAttributes = array_merge(
                [
                    $this->metaImageAttribute => [
                        'storage' => [
                            'class' => ModelStorage::className(),
                            'processor' => [
                                'class' => ImageProcessor::className(),
                                'options' => $this->imageUploaderOptions
                            ]
                        ]
                    ]
                ],
                $owner->uploadAttributes
            );
        }
    }

    public function beforeValidate()
    {
        /** @var ActiveRecord|UploadBehaviour $owner */
        $owner = $this->owner;

        if ($this->metaTitleAttribute && empty($owner->{$this->metaTitleAttribute})) {
            $owner->{$this->metaTitleAttribute} = $owner->{$this->titleFromAttribute};
        }

        if ($this->metaDescriptionAttribute && empty($owner->{$this->metaDescriptionAttribute})) {
            $owner->{$this->metaDescriptionAttribute} = $owner->{$this->descriptionFromAttribute};
        }
    }

    public function getMetaTitle()
    {
        return $this->owner->{$this->metaTitleAttribute};
    }

    public function getMetaDescription()
    {
        return $this->owner->{$this->metaDescriptionAttribute};
    }

    public function getMetaImage()
    {
        /** @var ActiveRecord|UploadBehaviour $owner */
        $owner = $this->owner;

        if (!empty($owner->{$this->metaImageAttribute})) {
            if ($this->hasUploadBehavior()) {
                return $owner->getFileUrl($this->metaImageAttribute, 'origin');
            } else {
                return $this->metaImageAttribute;
            }
        } elseif ($this->imageFromAttribute) {
            if ($this->hasUploadBehavior()) {
                return $owner->getFileUrl($this->imageFromAttribute, 'origin');
            } else {
                return $this->imageFromAttribute;
            }
        }

        return '';
    }

    protected function hasUploadBehavior()
    {
        $owner = $this->owner;

        foreach ($owner->getBehaviors() as $behavior) {
            if ($behavior instanceof UploadBehaviour) {
                return true;
            }
        }

        return false;
    }
}