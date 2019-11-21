<?php
namespace Test;


class View
{
	private $data = array();
	private $full = true;
	private $render = FALSE;
	private $render_header = FALSE;
	private $render_footer = FALSE;

	public function __construct($template,$data=[],$full = true)
	{
		$this->full = $full;
		try {
			$file_header = dirname(__DIR__) . '/view/header.php';
			$file_footer = dirname(__DIR__) . '/view/footer.php';
			$file = dirname(__DIR__) . '/view/' . strtolower($template) . '.php';

			if (file_exists($file)) {
				$this->render = $file;
			} else {
				throw new \Exception('Template ' . $template . ' not found!');
			}
			if ($this->full) {
				if ( file_exists( $file_header ) ) {
					$this->render_header = $file_header;
				} else {
					throw new \Exception( 'Template header not found!' );
				}
				if ( file_exists( $file_footer ) ) {
					$this->render_footer = $file_footer;
				} else {
					throw new \Exception( 'Template footer not found!' );
				}
			}
		}
		catch (\Exception $e) {
			echo $e->getMessage();
		}
		$this->data = $data;
	}

	public function assign($variable, $value)
	{
		$this->data[$variable] = $value;
	}

	public function __destruct()
	{
		extract($this->data);
		if ($this->full)
			include($this->render_header);
		include($this->render);
		if ($this->full)
			include($this->render_footer);
	}
}