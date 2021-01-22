<?php

namespace beehamchoi\google1tap\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * Class Google1tap
 *
 * @package beehamchoi\google1tap\widgets
 */
class Google1tap extends Widget{

	public $client_id;

	public $login_uri;

	public $plugin_options = [];

	private $default_options;

	/**
	 * {@inheritDoc}
	 */
	public function init(){
		$this->default_options = [
			'id'             => 'g_id_onload',
			'data-client_id' => $this->client_id,
			'data-login_uri' => $this->login_uri,
		];
	}

	/**
	 * @return string|void
	 * @throws \yii\base\InvalidConfigException
	 */
	public function run(){
		$this->view->registerJsFile('https://accounts.google.com/gsi/client',
			[
				'async'    => '',
				'defer'    => '',
				'position' => View::POS_HEAD
			], 'google1tap');
		$options = ArrayHelper::merge($this->plugin_options, $this->default_options);

		return Html::tag('div', '', $options);
	}
}
