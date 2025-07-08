<?php
require_once __DIR__ . '/../../../../core/php/core.inc.php';

// Vérification de la clé API
$apiKey = init('apikey');
$expected = config::byKey('api_key', 'n8nconnect');
if ($apiKey == '' || $expected == '' || $apiKey !== $expected) {
    echo json_encode(['state' => 'nok', 'error' => 'Invalid API key']);
    exit;
}

$eqId = init('eqLogic_id');
$cmdName = init('cmd_name');
$value = init('value');

if ($eqId == '' || $cmdName == '') {
    echo json_encode(['state' => 'nok', 'error' => 'Missing parameter']);
    exit;
}

$eqLogic = n8nconnect::byId($eqId);
if (!is_object($eqLogic)) {
    echo json_encode(['state' => 'nok', 'error' => 'Equipment not found']);
    exit;
}

$cmd = null;
foreach ($eqLogic->getCmd('info') as $c) {
    if ($c->getName() == $cmdName) {
        $cmd = $c;
        break;
    }
}

if (!is_object($cmd)) {
    echo json_encode(['state' => 'nok', 'error' => 'Command not found']);
    exit;
}

$cmd->checkAndUpdateCmd($value);

echo json_encode(['state' => 'ok']);

