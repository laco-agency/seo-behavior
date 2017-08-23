# SEO Behavior
Seo behavior for Yii2 models and meta tags helper

## Installing

```
composer require --prefer-dist laco-agency/seo-behavior
```
or add
```
"laco-agency/seo-behavior": "*"
```
to the require section of your composer.json.
## Usage

**Model:**

```php
use laco/seo/SeoModelBehavior;
```

```php
public function behaviors()
{
    return [
        [
            'class' => SeoModelBehavior::className(),
            'descriptionFromAttribute' => 'teaser',
            'metaImageAttribute' => 'image_preview'
       ]
   ]
}
```


**Controller:**

```php
use laco/seo/SeoControllerBehavior;
```

Attach behavior:

```php
public function behaviors()
{
    return [
        SeoControllerBehavior::className()
   ];
}
```

In case when parent controller already has behaviors, you can attach SeoControllerBehavior like this:

```php
public function behaviors()
{
    $behaviors = [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => []
        ],
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => []
        ]
    ];
    return array_merge(parent::behaviors(), $behaviors);
}
```
Or like this:
```php
public function init()
{
    $this->attachBehavior('seo', SeoControllerBehavior::className());
}
```

**Action:**

In the action use the method $this->setMetaTags($model) and pass $model as parameter;

```php
public function view($slug)
{
    $model = Material::findOne(['slug' => $slug]));
    $this->setMetaTags($model);
}
```
Or use an array in this format instead of model:   
```php
[
    'metaTitle' => 'Custom meta title',
    'metaDescription' => 'Custom description',
    'metaImage' => 'Custom meta image'
]
```
