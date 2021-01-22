<?php

namespace beehamchoi\google1tap\actions;

use Exception;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\httpclient\Client;

/**
 * Class Google1tapLogin
 *
 * @package beehamchoi\google1tap\actions
 */
class Google1tapLogin extends Action{

	const STATUS_RESPONSE_OK = 200;

	public $successCallback;

	public $urlToken = 'https://oauth2.googleapis.com/tokeninfo';

	/**
	 * @var Client
	 */
	private $httpClient;

	/**
	 * {@inheritDoc}
	 */
	public function init(){
		$this->controller->enableCsrfValidation = FALSE;
		$this->httpClient                       = new Client();
		parent::init();
	}

	/**
	 * @return \yii\web\Response
	 * @throws \yii\base\InvalidConfigException
	 * @throws \Exception
	 */
	public function run(){
		$post = Yii::$app->request->post();

		if (empty($post['g_csrf_token'])
		    || empty($_COOKIE['g_csrf_token'])
		    || ($post['g_csrf_token'] !== $_COOKIE['g_csrf_token'])){
			return $this->controller->goHome();
		}

		$credential = $post['credential'] ?? '';
		if ($credential){
			/** @var yii\httpclient\Response $response */
			$response = $this->httpClient->createRequest()
			                             ->setUrl($this->urlToken)
			                             ->setMethod('POST')
			                             ->setData(['id_token' => $credential])
			                             ->send();
			if ($response->statusCode == self::STATUS_RESPONSE_OK){
				try{
					$client = Json::decode($response->content);

					return $this->authSuccess($client);
				}catch (Exception $exception){
					throw $exception;
				}
			}
		}

		return $this->controller->goHome();
	}

	/**
	 * @param $client
	 *
	 * @return mixed
	 * @throws \yii\base\InvalidConfigException
	 */
	protected function authSuccess($client){
		if (!is_callable($this->successCallback)){
			throw new InvalidConfigException('"' . get_class($this) . '::$successCallback" should be a valid callback.');
		}

		return call_user_func($this->successCallback, $client);
	}
}
