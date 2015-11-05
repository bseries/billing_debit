<?php
/**
 * Billing Debit
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * Licensed under the AD General Software License v1.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *
 * You should have received a copy of the AD General Software
 * License. If not, see http://atelierdisko.de/licenses.
 */

namespace billing_debit\config;

use billing_core\models\PaymentMethods;
use lithium\g11n\Message;

extract(Message::aliases());

PaymentMethods::register('banque.localDebit', [
	'title' => function() use ($t) {
		return $t('Direct Debit', ['scope' => 'billing_debit']);
	},
	'info' => function($context, $format) use ($t) {
		$intro = $t('The total amount will be automatically debited from your bank account.', [
			'scope' => 'billing_debit'
		]);
		if ($format === 'html') {
			return "<p>{$intro}</p>";
		}
		return "\n{$intro}\n\n";
	}
]);

?>