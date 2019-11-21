<?php
/**
 * Billing Debit
 *
 * Copyright (c) 2014 David Persson - All rights reserved.
 * Copyright (c) 2016 Atelier Disko - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace billing_debit\config;

use base_core\extensions\cms\Panes;
use lithium\g11n\Message;

extract(Message::aliases());

Panes::register('billing.debitCards', [
	'title' => $t('Debit Cards'),
	'url' => ['controller' => 'DebitCards', 'action' => 'index', 'library' => 'billing_debit', 'admin' => true],
	'weight' => 70
]);

?>