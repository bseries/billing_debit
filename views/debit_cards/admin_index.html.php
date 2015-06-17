<?php

use lithium\g11n\Message;

$t = function($message, array $options = []) {
	return Message::translate($message, $options + ['scope' => 'billing_debit', 'default' => $message]);
};

$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('debit cards')
	]
]);

?>
<article
	class="use-rich-index"
	data-endpoint="<?= $this->url([
		'action' => 'index',
		'page' => '__PAGE__',
		'orderField' => '__ORDER_FIELD__',
		'orderDirection' => '__ORDER_DIRECTION__',
		'filter' => '__FILTER__'
	]) ?>"
>

	<div class="top-actions">
		<?= $this->html->link($t('debit card'), ['action' => 'add', 'library' => 'billing_debit'], ['class' => 'button add']) ?>
	</div>

	<?php if ($data->count()): ?>
		<table>
			<thead>
				<tr>
					<td data-sort="user.number" class="user table-sort"><?= $t('User') ?>
					<td data-sort="holder" class="holder table-sort"><?= $t('Holder') ?>
					<td data-sort="iban" class="iban table-sort"><?= $t('IBAN') ?>
					<td data-sort="bic" class="bic table-sort"><?= $t('BIC') ?>
					<td data-sort="bank" class="bank table-sort"><?= $t('Bank') ?>
					<td data-sort="user-has-accepted-direct-debit" class="direct-debit flag table-sort"><?= $t('Direct Debit?') ?>
					<td data-sort="modified" class="date modified table-sort desc"><?= $t('Modified') ?>
					<td class="actions">
						<?= $this->form->field('search', [
							'type' => 'search',
							'label' => false,
							'placeholder' => $t('Filter'),
							'class' => 'table-search',
							'value' => $this->_request->filter
						]) ?>
			</thead>
			<tbody>
				<?php foreach ($data as $item): ?>
					<?php $user = $item->user() ?>
				<tr data-id="<?= $item->id ?>">
					<td class="user">
					<?php if ($user): ?>
						<?= $this->html->link($user->number, [
							'controller' => $user->isVirtual() ? 'VirtualUsers' : 'Users',
							'action' => 'edit', 'id' => $user->id,
							'library' => 'base_core'
						]) ?>
					<?php else: ?>
						–
					<?php endif ?>
					<td class="holder emphasize"><?= $item->holder ?>
					<td class="iban emphasize"><?= $item->iban ?>
					<td class="bic"><?= $item->bic ?>
					<td class="bank"><?= $item->bank()->name ?>
					<td class="direct-debit flag"><?= $item->user_has_accepted_direct_debit ? '✓ ' : '×' ?>
					<td class="date modified">
						<time datetime="<?= $this->date->format($item->modified, 'w3c') ?>">
							<?= $this->date->format($item->modified, 'date') ?>
						</time>
					<td class="actions">
						<?= $this->html->link($t('delete'), ['id' => $item->id, 'action' => 'delete', 'library' => 'billing_debit'], ['class' => 'button delete']) ?>
						<?= $this->html->link($t('open'), ['id' => $item->id, 'action' => 'edit', 'library' => 'billing_debit'], ['class' => 'button']) ?>
				<?php endforeach ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="none-available"><?= $t('No items available, yet.') ?></div>
	<?php endif ?>

	<?=$this->view()->render(['element' => 'paging'], compact('paginator'), ['library' => 'base_core']) ?>
</article>