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
		$this->out('Updating banks from source...');
		BanksModel::updateFromSource();
		$this->out('Done.');
	}
}

?>