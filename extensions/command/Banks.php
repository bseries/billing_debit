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

namespace billing_debit\extensions\command;

use billing_debit\models\Banks as BanksModel;
use League\Csv\Reader;

class Banks extends \lithium\console\Command {

	public function init() {
		return $this->update();
	}

	public function update() {
		$this->out('Download CSV...');

		$file = fopen('php://temp', 'w');
		$url = 'http://www.bundesbank.de/Redaktion/DE/Downloads/Aufgaben/Unbarer_Zahlungsverkehr/SEPA/verzeichnis_der_erreichbaren_zahlungsdienstleister.csv?__blob=publicationFile';

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_FILE, $file);
		curl_exec($curl);
		curl_close($curl);

		rewind($file);

		$reader = new Reader($file);
		$reader->setDelimiter(';');

		// Skip 2-line-header, indexing is 0-based.
		$reader->setOffset(1);

		BanksModel::pdo()->beginTransaction();

		$this->out('Dropping table contents...');
		BanksModel::remove();

		$this->out('Download CSV...');
		$reader->each(function($row) {
			$item = BanksModel::create([
				'bic' => $row[0],
				'name' => $row[1]
			]);
			if (!$item->save()) {
				BanksModel::pdo()->rollback();
				$this->err('Failed to insert row; aborting.');
				return false;
			}

			return true;
		});

		BanksModel::pdo()->commit();
		fclose($file);
	}
}

?>