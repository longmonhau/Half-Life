<?php namespace lOngmon\Hau\usr\controller\admin;

include ROOT_PATH.'/var/HyperDown.php';

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Factory;

class Markdown2html extends Control
{
	public function printHtml()
	{
		$post = [];
		$md = $this->post("md");
		$post['title'] = $this->post('title');
		if( $post['title'] == '' )
		{
			$post['title'] = "无题";
		}
		$HyperDown = new \HyperDown\Parser;
		$post['html'] = $HyperDown->makeHtml($md);
		
		$this->assign("Post", $post);
		$this->display("PostPreView.html");
	}
}