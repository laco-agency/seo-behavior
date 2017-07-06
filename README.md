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

Current controller:

```php
public function behaviors()
{
    return [
        SeoControllerBehavior::className()
   ];
}
```

or parent controller:

```php
public function init()
{
    $this->attachBehavior('seo', SeoControllerBehavior::className());
}
```

**Action:**

```php
public function view($slug)
{
    $model = Material::findOne(['slug' => $slug]));
    $this->setMetaTags($model);
}
```
