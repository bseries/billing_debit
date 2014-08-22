<?php
/**
 * Billing Debit
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

namespace billing_debit\controllers;

use lithium\g11n\Message;
use li3_flash_message\extensions\storage\FlashMessage;
use billing_debit\models\DebitCards;

class DebitCardsController extends \cms_core\controllers\BaseController {

	use \cms_core\controllers\AdminEditTrait;
	use \cms_core\controllers\AdminDeleteTrait;

	public function admin_index() {
		$data = DebitCards::find('all', [
			'order' => ['created' => 'desc']
		]);
		return compact('data') + $this->_selects();
	}
}

?>