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

	public function format($entity, $type, $locale = null, $mask = false) {
		$mask = function($value, $before, $after) {
			return substr($value, 0, $before) . str_repeat('X', strlen($value) - $before - $after) . substr($value, -$after);
		};
		if (!$locale) {
			$locale = ($user = $entity->user()) ? $user->locale : Environment::get('locale');
		}

		if ($type == 'oneline') {
			$result = [];

			$result[] = $entity->holder;
			$result[] = $mask ? $mask($entity->iban, 4, 4) : $entity->iban;
			$result[] = $mask ? $mask($entity->bic, 2, 2) : $entity->bic;
			// Security: Do not reveal bic through bank name.

			return implode('/ ', $result);
		}
		$result = [];

		$result[] = $entity->holder;
		$result[] = 'IBAN ' . ($mask ? $mask($entity->iban, 4, 4) : $entity->iban);
		$result[] = 'BIC ' . ($mask ? $mask($entity->bic, 2, 2) : $entity->bic);
		// Security: Do not reveal bic through bank name.

		return implode("\n", $result);
	}
}

DebitCards::init();

DebitCards::applyFilter('save', function($self, $params, $chain) {
	$entity = $params['entity'];
	$data =& $params['data'];

	$normalize = function($value) {
		return strtoupper(preg_replace('/\s+/', '', $value));
	};

	if (isset($data['iban'])) {
		$data['iban'] = $normalize($data['iban']);
	}
	$entity->iban = $normalize($entity->iban);

	if (isset($data['bic'])) {
		$data['bic'] = $normalize($data['bic']);
	}
	$entity->bic = $normalize($entity->bic);

	return $chain->next($self, $params, $chain);
});

?>