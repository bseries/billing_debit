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

	protected $_meta = [
		'source' => 'billing_debit_cards'
	];

	public $belongsTo = [
		'User' => [
			'to' => 'base_core\models\Users',
			'key' => 'user_id'
		],
		'VirtualUser' => [
			'to' => 'base_core\models\VirtualUsers',
			'key' => 'virtual_user_id'
		]
	];

	protected $_actsAs = [
		'base_core\extensions\data\behavior\User',
		'base_core\extensions\data\behavior\Timestamp',
		'base_core\extensions\data\behavior\Searchable' => [
			'fields' => [
				'holder',
				'iban',
				'bic'
			]
		]
	];

	public static function init() {
		extract(Message::aliases());
		$model = static::_object();

		$model->validates['holder'] = [
			[
				'notEmpty',
				'message' => $t('This field cannot be empty.', ['scope' => 'billing_debit'])
			]
		];

		$model->validates['iban'] = [
			[
				'notEmpty',
				'message' => $t('This field cannot be empty.', ['scope' => 'billing_debit']),
				'last' => true
			],
			[
				'ibanFormat',
				'message' => $t('The IBAN is not correctly formatted.', ['scope' => 'billing_debit'])
			]
		];
		Validator::add('ibanFormat', function($value, $format, $options) {
			$validator = new IBANValidator();
			return $validator->validate(static::_normalize($value));
		});

		$model->validates['bic'] = [
			[
				'notEmpty',
				'message' => $t('This field cannot be empty.', ['scope' => 'billing_debit']),
				'last' => true
			],
			[
				'lengthBetween',
				'min' => 11, 'max' => 11,
				'message' => $t('Only 11-digit BICs are supported (i.e. PBNKDEFFXXX instead of PBNKDEFF).', ['scope' => 'billing_debit']),
				'last' => true
			],
			[
				'bicFormat',
				'message' => $t('The BIC is not correctly formatted.', ['scope' => 'billing_debit'])
			]
		];
		Validator::add('bicFormat', function($value, $format, $options) {
			return (boolean) Banks::find('count', [
				'conditions' => [
					'bic' => static::_normalize($value)
				]
			]);
		});

		static::applyFilter('save', function($self, $params, $chain) {
			$entity = $params['entity'];
			$data =& $params['data'];

			if (isset($data['iban'])) {
				$data['iban'] = static::_normalize($data['iban']);
			}
			$entity->iban = static::_normalize($entity->iban);

			if (isset($data['bic'])) {
				$data['bic'] = static::_normalize($data['bic']);
			}
			$entity->bic = static::_normalize($entity->bic);
			return $chain->next($self, $params, $chain);
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

	protected static function _normalize($value) {
		return strtoupper(preg_replace('/\s+/', '', $value));
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

?>