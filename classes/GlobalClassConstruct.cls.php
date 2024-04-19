<?php
class GlobalClassConstruct {
	public $files;
	public $request;
	public $session;
	public $dbobj;
	public $post;
	public $get;

	function __construct ()	{	
		global $_FILES, $_REQUEST, $_SESSION, $_GET, $_POST;
		$this->files   = &$_FILES;
		$this->request = &$_REQUEST;
		$this->session = &$_SESSION;
		$this->post    = &$_POST;
		$this->get     = &$_GET;
	}
}
?>