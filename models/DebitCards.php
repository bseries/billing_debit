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
use League\Csv\Reader;

// BIC Download
// http://www.bundesbank.de/Navigation/DE/Aufgaben/Unbarer_Zahlungsverkehr/SEPA/SCL_Directory/scl_directory.html
// http://www.bundesbank.de/Redaktion/DE/Downloads/Aufgaben/Unbarer_Zahlungsverkehr/SEPA/verzeichnis_der_erreichbaren_zahlungsdienstleister.csv?__blob=publicationFile
class DebitCards extends \cms_core\models\Base {

	protected $_meta = [
		'source' => 'billing_debit_cards'
	];

	public function iban($entity) {
		return new IBAN($entity->iban);
	}

	public function bank($entity) {
	}
}

?>