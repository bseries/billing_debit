<?php
/**
 * Billing Debit
 *
 * Copyright (c) 2016 Atelier Disko - All rights reserved.
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

namespace billing_debit\billing\payment\gateway;

use billing_debit\billing\payment\storage\DebitCard as Storage;

class DebitCard extends \billing_payment\billing\payment\Gateway {

	public function storage() {
		return new Storage();
	}
}

?>