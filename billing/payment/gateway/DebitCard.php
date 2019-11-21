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

namespace billing_debit\billing\payment\gateway;

use billing_debit\billing\payment\storage\DebitCard as Storage;

class DebitCard extends \billing_payment\billing\payment\Gateway {

	public function storage() {
		return new Storage();
	}
}

?>