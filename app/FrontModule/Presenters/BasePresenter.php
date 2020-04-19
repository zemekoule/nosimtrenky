<?php declare(strict_types=1);

namespace FrontModule;

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
	/** @var \App\Model\Config @inject */
	public $parameters;

	public function startup() {
		parent::startup();
		$this->template->param = $this->parameters;
	}
}