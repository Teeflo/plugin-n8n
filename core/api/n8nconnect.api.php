<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../class/n8nconnect.class.php';

header('Content-Type: application/json');

if (jeedom::getApiKey('n8nconnect') !== init('apikey')) {
    echo json_encode(['state' => 'nok', 'error' => 'Invalid API key']);
    exit;
}

$eqId = init('eqLogic_id');
$cmdName = init('cmd_name');
$value = init('value');

if ($eqId == '' || $cmdName == '') {
    echo json_encode(['state' => 'nok', 'error' => 'Missing parameters']);
    exit;
}

$eqLogic = n8nconnect::byId($eqId);
if (!is_object($eqLogic)) {
    echo json_encode(['state' => 'nok', 'error' => 'Unknown equipment']);
    exit;
}

$cmd = $eqLogic->getCmd(null, $cmdName);
if (!is_object($cmd)) {
    echo json_encode(['state' => 'nok', 'error' => 'Command not found']);
    exit;
}

$cmd->checkAndUpdateCmd($value);

echo json_encode(['state' => 'ok']);
