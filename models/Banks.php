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

namespace billing_debit\models;

use League\Csv\Reader;
use SplTempFileObject;
use temporary\Manager as Temporary;
use lithium\analysis\Logger;

class Banks extends \base_core\models\Base {

	protected $_meta = [
		'source' => 'billing_banks'
	];

	public static function updateFromSource() {
		Logger::debug('Updating banks from source...');

		$file = Temporary::file();
		$stream = fopen($file, 'w+');

		$url = 'http://www.bundesbank.de/Redaktion/DE/Downloads/Aufgaben/Unbarer_Zahlungsverkehr/SEPA/verzeichnis_der_erreichbaren_zahlungsdienstleister.csv?__blob=publicationFile';

		Logger::debug('Downloading SEPA clearers CSV...');
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_FILE, $stream);
		curl_exec($curl);
		curl_close($curl);

		fclose($stream);

		$reader = new Reader($file);
		$reader->setDelimiter(';');

		// Skip 2-line-header, indexing is 0-based.
		$reader->setOffset(2);

		static::pdo()->beginTransaction();

		Logger::debug('Removing current banks.');
		static::remove();

		Logger::debug('Starting insertion.');
		$reader->each(function($row) {
			if (!isset($row[0], $row[1])) {
				Logger::debug('Skipping over invalid row: ' . var_export($row, true));
				return true;
			}
			$item = static::create([
				'bic' => trim($row[0]),
				'name' => trim($row[1])
			]);
			if (!$item->save()) {
				static::pdo()->rollback();
				throw new Exception('Failed to insert.');
			}
			return true; // Must return true to continue iterating.
		});

		Logger::debug('Finalizing banks update.');
		static::pdo()->commit();
		Logger::debug('Banks update finished.');
	}
}

?>