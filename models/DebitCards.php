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
use IBAN\Validation\IBANValidator;
use billing_debit\models\Banks;
use lithium\g11n\Message;
use lithium\util\Validator;

class DebitCards extends \base_core\models\Base {

	use \base_core\models\UserTrait;

	protected $_meta = [
		'source' => 'billing_debit_cards'
	];

	protected static $_actsAs = [
		'base_core\extensions\data\behavior\Timestamp'
	];

	public static function init() {
		extract(Message::aliases());
		$model = static::_object();

		$model->validates['holder'] = [
			[
				'notEmpty',
				'message' => $t('This field cannot be empty.')
			]
		];

		$model->validates['iban'] = [
			[
				'notEmpty',
				'message' => $t('This field cannot be empty.'),
				'last' => true
			],
			[
				'ibanFormat',
				'message' => $t('The IBAN is not correctly formatted.')
			]
		];
		Validator::add('ibanFormat', function($value, $format, $options) {
			$validator = new IBANValidator();
			return $validator->validate($value);
		});

		$model->validates['bic'] = [
			[
				'notEmpty',
				'message' => $t('This field cannot be empty.'),
				'last' => true
			],
			[
				'bicFormat',
				'message' => $t('The BIC is not correctly formatted.')
			]
		];
		Validator::add('bicFormat', function($value, $format, $options) {
			return (boolean) Banks::find('count', [
				'conditions' => [
					'bic' => $value
				]
			]);
		});
	}

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
}

DebitCards::init();

DebitCards::applyFilter('save', function($self, $params, $chain) {
	$data =& $params['data'];

	if (isset($data['iban'])) {
		$data['iban'] = strtoupper(preg_replace('/\s+/', '', $data['iban']));
	}
	if (isset($data['bic'])) {
		$data['bic'] = strtoupper(preg_replace('/\s+/', '', $data['bic']));
	}
	return $chain->next($self, $params, $chain);
});

?>