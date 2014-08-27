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

namespace billing_debit\models;

use IBAN\Core\IBAN;
use billing_debit\models\Banks;
use cms_core\models\Users;
use cms_core\models\VirtualUsers;

class DebitCards extends \cms_core\models\Base {

	protected $_meta = [
		'source' => 'billing_debit_cards'
	];

	public function iban($entity) {
		return new IBAN($entity->iban);
	}

	public function bank($entity) {
		return Banks::find('first', [
			'conditions' => [
				'bic' => $entity->bic
			]
		]);
	}

	public function user($entity) {
		if ($entity->user_id) {
			return Users::find('first', ['conditions' => ['id' => $entity->user_id]]);
		}
		return VirtualUsers::find('first', ['conditions' => ['id' => $entity->virtual_user_id]]);
	}
}

?>