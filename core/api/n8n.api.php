<?php
require_once __DIR__ . '/../../../../core/php/core.inc.php';

header('Content-Type: application/json');

$apiKey = init('apikey');
if ($apiKey !== jeedom::getApiKey('n8nconnect')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Invalid API key']);
    exit;
}

$eqLogicId = init('eqLogic_id');
$cmdName = init('cmd_name');
$value = init('value');

if ($eqLogicId == '' || $cmdName == '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

$eqLogic = n8nconnect::byId($eqLogicId);
if (!is_object($eqLogic)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Equipment not found']);
    exit;
}

$cmd = $eqLogic->getCmd(null, $cmdName);
if (!is_object($cmd) || $cmd->getType() != 'info') {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Command not found']);
    exit;
}

$cmd->event($value);

echo json_encode(['success' => true]);
?>
