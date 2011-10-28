<?php 
/**
 * Yii-Cart module
 * 
 * @author Mikhail Mangushev <mishamx@gmail.com> 
 * @link http://code.google.com/p/yii-cart/
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @version $Id: CartModule.php 358 2010-11-29 16:49:27Z misha $
 */

class CartModule extends CWebModule {
	
	public $widgetPath = 'application.modules.user.components';
	public $assetPath = 'application.modules.user.views.asset';
	
	/*
	 * @var string
	 * @see http://php.net/manual/en/function.iconv.php
	 */
	public $importPathPhotos = 'import';
	public $importCSVCharset = 'UTF-8';
	public $importAjaxSleep = 0; // milliseconds
	public $mediaPath = 'assets/photos/';
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'cart.models.*',
			'cart.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	/**
	 * @param $str
	 * @param $params
	 * @param $dic
	 * @return string
	 */
	public static function t($str,$dic='',$params=array()) {
		if (strpos($dic,'.php')) {
			$dic = str_replace('_modules_cart_','',str_replace('.php','',str_replace('\\','_',str_replace('/','_',str_replace(Yii::app()->basePath,'',$dic)))));
		}
		if (!$dic) $dic = 'base';
		return Yii::t("CartModule.".$dic, $str, $params);
	}
}
