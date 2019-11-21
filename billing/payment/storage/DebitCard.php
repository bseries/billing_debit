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

namespace billing_debit\billing\payment\storage;

use billing_debit\models\DebitCards as Model;

// $key is IBAN
class DebitCard extends \billing_payment\billing\payment\Storage {

	public function write($key, $data) {
		$entity = Model::create();
		return $entity->save(['iban' => $key] + $data);
	}

	public function read($key) {
		$entity = Model::find('first', [
			'conditions' => [
				'iban' => $key
			]
		]);
		if (!$entity) {
			return false;
		}
		return $entity->data();
	}

	public function delete($key) {
		$entity = Model::find('first', [
			'conditions' => [
				'iban' => $key
			]
		]);
		if (!$entity) {
			return false;
		}
		return $entity->delete();
	}
}

?>