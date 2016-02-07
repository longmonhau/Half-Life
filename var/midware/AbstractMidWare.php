<?php namespace lOngmon\Hau\core\midware;

abstract class AbstractMidWare
{
	public function run()
	{
		if( $this->assert() )
		{
			return $this->next();
		} else
		{
			return $this->falseHandler();
		}
	}

	abstract protected function assert();
	abstract protected function next();
	abstract protected function falseHandler();
}