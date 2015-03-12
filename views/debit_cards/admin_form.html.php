<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'billing_debit', 'default' => $message]);
};

$this->set([
	'page' => [
		'type' => 'single',
		'title' => $item->iban,
		'empty' => $t('n/a'),
		'object' => $t('debit card')
	]
]);

?>
<article class="view-<?= $this->_config['controller'] . '-' . $this->_config['template'] ?> section-spacing">

	<?=$this->form->create($item) ?>
		<div class="grid-row">
			<div class="grid-column-left">
				<div class="compound-users">
					<?php
						$user = $item->exists() ? $item->user() : false;
					?>
					<?= $this->form->field('user_id', [
						'type' => 'select',
						'label' => $t('User'),
						'list' => $users,
						'class' => !$user || !$user->isVirtual() ? null : 'hide'
					]) ?>
					<?= $this->form->field('virtual_user_id', [
						'type' => 'select',
						'label' => false,
						'list' => $virtualUsers,
						'class' => $user && $user->isVirtual() ? null : 'hide'
					]) ?>
					<?= $this->form->field('user.is_real', [
						'type' => 'checkbox',
						'label' => $t('real user'),
						'checked' => $user ? !$user->isVirtual() : true
					]) ?>
				</div>
			</div>
			<div class="grid-column-right">
				<?= $this->form->field('has_accepted_direct_debit', [
					'type' => 'checkbox',
					'label' => $t('user has accepted direct debit'),
					'checked' => (boolean) $item->user_has_accepted_direct_debit,
					'value' => 1
				]) ?>
			</div>
		</div>
		<div class="grid-row">
			<div class="grid-column-left">
				<?= $this->form->field('holder', [
					'type' => 'text',
					'label' => $t('Holder')
				]) ?>
				<?= $this->form->field('iban', [
					'type' => 'text',
					'label' => $t('IBAN'),
					'class' => 'use-for-title',
					'style' => 'text-transform: uppercase;'
				]) ?>
				<?= $this->form->field('bic', [
					'type' => 'text',
					'label' => $t('BIC'),
					'style' => 'text-transform: uppercase;'
				]) ?>
				<?= $this->form->field('bank.name', [
					'type' => 'text',
					'label' => $t('Bank'),
					'disabled' => true,
					'value' => $item->exists() && ($bank = $item->bank()) ? $bank->name : null
				]) ?>
			</div>
			<div class="grid-column-right">
			</div>
		</div>


		<div class="bottom-actions">
			<?= $this->form->button($t('save'), ['type' => 'submit', 'class' => 'button large']) ?>
		</div>

	<?=$this->form->end() ?>
</article>