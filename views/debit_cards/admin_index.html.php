<?php

$this->set([
	'page' => [
		'type' => 'multiple',
		'object' => $t('debit cards')
	]
]);

?>
<article class="view-<?= $this->_config['controller'] . '-' . $this->_config['template'] ?> use-list">

	<div class="top-actions">
		<?= $this->html->link($t('new debit card'), ['action' => 'add', 'library' => 'billing_debit'], ['class' => 'button add']) ?>
	</div>

		<?php if ($data->count()): ?>
		<table>
			<thead>
				<tr>
					<td data-sort="iban" class="iban list-sort"><?= $t('IBAN') ?>
					<td data-sort="bic" class="bic list-sort"><?= $t('BIC') ?>
					<td data-sort="bank" class="bank list-sort"><?= $t('Bank') ?>
					<td data-sort="holder" class="holder list-sort"><?= $t('Holder') ?>
					<td data-sort="user" class="user list-sort"><?= $t('User') ?>
					<td data-sort="created" class="date created list-sort desc"><?= $t('Created') ?>
					<td class="actions">
						<?= $this->form->field('search', [
							'type' => 'search',
							'label' => false,
							'placeholder' => $t('Filter'),
							'class' => 'list-search'
						]) ?>
			</thead>
			<tbody class="list">
				<?php foreach ($data as $item): ?>
					<?php $user = $item->user() ?>
				<tr data-id="<?= $item->id ?>">
					<td class="iban"><?= $item->iban ?>
					<td class="bic"><?= $item->bic ?>
					<td class="bank"><?= $item->bank()->name ?>
					<td class="holder"><?= $item->holder ?>
					<td class="user">
					<?php if ($user): ?>
						<?= $this->html->link($user->number, [
							'controller' => $user->isVirtual() ? 'VirtualUsers' : 'Users',
							'action' => 'edit', 'id' => $user->id,
							'library' => 'cms_core'
						]) ?>
					<?php else: ?>
						–
					<?php endif ?>
					<td class="date created">
						<time datetime="<?= $this->date->format($item->created, 'w3c') ?>">
							<?= $this->date->format($item->created, 'date') ?>
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
</article>