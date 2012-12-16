<?php
class template
{
	private $controller;
	private $view;
	private $content;
	public $render;
	public $baseRender;
	function __construct($action,$view)
	{
		if(is_null($action))
			$this->controller ='index';
		else		
			$this->controller = $action;
		if(is_null($view))
			$this->view='index';
		else			
			$this->view=$view;		
	}

	public function view()
	{
		require_once('library/Raspina/Template/Twig/Autoloader.php');
		Twig_Autoloader::register();
		
		$dirTemplate1='application/theme/default/';
		$dirTemplate2='application/view/'.$this->controller.'/';
		
		$load=new Twig_Loader_Filesystem(array($dirTemplate1,$dirTemplate2));
		$twig = new Twig_Environment($load, array(
			'cache' => 'library/Raspina/Template/Cache',
			'auto_reload' => true
		));

		$template=$twig->loadTemplate($this->view.'.php');
		$tmp=$template->render($this->render);

		$escaper = new Twig_Extension_Escaper(true);
		$twig->addExtension($escaper);
		
		$base=$twig->loadTemplate('default.php');
		$this->baseRender['content']=$tmp;
		$base->display($this->baseRender);
	}
}