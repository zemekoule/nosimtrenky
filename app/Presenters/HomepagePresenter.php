<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SendException;
use Nette\Mail\SendmailMailer;
use Nette\Utils\Html;
use Tracy\Debugger;


final class HomepagePresenter extends BasePresenter
{
	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentOrderForm(): Form {
		$form = new Form();

		$form->addInteger('orderNumber', Html::el()->setHtml("Číslo objednávky<span class='red'>*</span>"))
			->setRequired('Vyplňte číslo objednávky.')
			->addRule($form::MIN_LENGTH, 'Číslo objednávky musí mít 5 čísel', 5);

		$form->addText('email', Html::el()->setHtml("Email <span class='red'>*</span>"))
			->addRule(Form::EMAIL, 'Zadejte platnou email adresu.');

		$form->addText('phone', Html::el()->setHtml("Telefon <span class='red'>*</span> (nutné vyplnit minimálně jeden z údajů Email/Telefon)"))
			->addConditionOn($form['email'], $form::BLANK)
				->setRequired('Musíte vyplnit telefonní číslo nebo e-mail adresu.');

		$form->addText('firstName', Html::el()->setHtml("Křestní jméno <span class='red'>*</span>"))
			->setRequired('Vyplňte křestní jméno.');
		$form->addText('lastName', Html::el()->setHtml("Přijmení <span class='red'>*</span>"))
			->setRequired('Vyplňte příjmení');

		$form->addButton('widget', 'Vybrat pobočku');
		$form->addHidden('branchId');
		$form->addHidden('branchName');

		$form->addSubmit('send', 'Odeslat');
		$form->onValidate[] = [$this, 'orderFormValidate'];
		$form->onSuccess[] = [$this, 'orderFormSucceeded'];

		return $form;
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 */
	public function orderFormValidate(Form $form) {
		$values = $form->getValues();
		if(!$values->branchId) {
			$form->addError('Vyberte si prosím pobočku Zásilkovny!');
		}
	}

	/**
	 * @param \Nette\Application\UI\Form $form
	 *
	 * @throws \Nette\Application\AbortException
	 */
	public function orderFormSucceeded(Form $form) {

		$values = $form->getValues();

		$latte = new \Latte\Engine;
		$params = [
			'branchId' => $values->branchId,
			'branchName' => $values->branchName,
			'orderNumber' => $values->orderNumber,
			'name' => $values->name,
			'email' => $values->email,
		];

		$mail = new Message;
		$mail->setFrom($this->parameters->getEmailFrom())
			->addReplyTo($values->email)
			->setHtmlBody($latte->renderToString(__DIR__ . '/templates/email.latte', $params));

		foreach ($this->parameters->getEmailTo() as $email) {
			$mail->addTo($email);
		}

		Debugger::log(sprintf('Zákazník %s, email: %s, objednávka %d, Pobočka: %d', $values->name, $values->email, $values->orderNumber, $values->branchId));
		$mailer = new SendmailMailer;
		try {
			$mailer->send($mail);
			$this->flashMessage('Formulář byl v pořádku odeslán.','alert-primary');

		} catch (SendException $e) {
			$this->flashMessage('Formulář se bohužel nepodařilo odeslat! Kontaktujte prosím prodejce!','alert-danger');
		}

		$this->redirect('default');
	}


}
