Google One Tap
==============
Google One Tap Extension

Installation
------------
Either run

```
composer require beehamchoi/yii2-google1tap ^1.0
```

```
php composer.phar require beehamchoi/yii2-google1tap "^1.0"
```

or add

```
"beehamchoi/yii2-google1tap": "^1.0"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

`views/index.php`

* Note: 
    * Must be https
    * You can change controller or action
    * Example: SiteController and goole-onetap action

```
<?= \beehamchoi\google1tap\widgets\Google1tap::widget([
	'client_id' => @your client id,
	'login_uri' => Yii::$app->urlManager->createAbsoluteUrl(['/site/google-onetap'], 'https')
]) ?>
```

`controllers/SiteController.php`
```
    public function actions(){
		return [
			'google-onetap' => [
				'class'           => Google1tapLogin::class,
				'successCallback' => function ($client){
				//Your code here. Can do any thing with $client. 
                //$client is infor of google user
				}
			]
		];
	}
```
