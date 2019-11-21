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

namespace billing_debit\extensions\command;

use billing_debit\models\Banks as BanksModel;
use League\Csv\Reader;

class Banks extends \lithium\console\Command {

	public function init() {
		return $this->update();
	}

	public function update() {
		$this->out('Updating banks from source...');
		BanksModel::updateFromSource();
		$this->out('Done.');
	}
}

?>