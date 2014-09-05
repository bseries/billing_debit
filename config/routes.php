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

use lithium\net\http\Router;

$persist = ['persist' => ['admin', 'controller']];

Router::connect('/admin/billing/debit-cards/{:id:[0-9]+}', [
	'controller' => 'DebitCards', 'library' => 'billing_debit', 'action' => 'view', 'admin' => true
], $persist);
Router::connect('/admin/billing/debit-cards/{:action}', [
	'controller' => 'DebitCards', 'library' => 'billing_debit', 'admin' => true
], $persist);
Router::connect('/admin/billing/debit-cards/{:action}/{:id:[0-9]+}', [
	'controller' => 'DebitCards', 'library' => 'billing_debit', 'admin' => true
], $persist);
Router::connect('/admin/billing/debit-cards/{:id:[0-9]+}/status/{:status}', [
	'controller' => 'DebitCards', 'action' => 'update_status', 'library' => 'billing_debit', 'admin' => true
], $persist);

?>