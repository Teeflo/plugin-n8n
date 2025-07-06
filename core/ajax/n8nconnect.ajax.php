<?php
try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

    require_once dirname(__FILE__) . '/../class/n8nconnect.class.php';

    ajax::init();

    if (init('action') == 'test') {
        $url = rtrim(init('url'), '/');
        $key = init('key');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '/api/v1/workflows?limit=1');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'X-N8N-API-KEY: ' . $key,
        ]);
        $resp = curl_exec($curl);
        if ($resp === false) {
            throw new Exception(curl_error($curl));
        }
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($code === 200) {
            ajax::success(__('Connexion réussie', __FILE__));
        }
        $msg = __('Code réponse', __FILE__) . ' ' . $code;
        $decoded = json_decode($resp, true);
        if (is_array($decoded) && isset($decoded['message'])) {
            $msg .= ' - ' . $decoded['message'];
        }
        throw new Exception($msg);
    }

    if (init('action') == 'listWorkflows') {
        try {
            $data = n8nconnect::callN8n('GET', '/workflows');
            $result = [];
            if (isset($data['data'])) {
                foreach ($data['data'] as $wf) {
                    $result[] = ['id' => $wf['id'], 'name' => $wf['name']];
                }
            }
            ajax::success($result);
        } catch (Exception $e) {
            log::add('n8nconnect', 'error', 'Erreur lors de la récupération des workflows : ' . $e->getMessage());
            ajax::error($e->getMessage());
        }
    }

    // Gestion des actions d'équipement standard de Jeedom
    if (init('action') == 'add') {
        $eqLogic = new n8nconnect();
        $eqLogic->setName(__('Nouveau workflow n8n', __FILE__));
        $eqLogic->setEqType_name('n8nconnect');
        $eqLogic->setIsEnable(1);
        $eqLogic->setIsVisible(1);
        $eqLogic->save();
        ajax::success(utils::o2a($eqLogic));
    }
    
    if (init('action') == 'get') {
        $eqLogic = n8nconnect::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('Équipement introuvable', __FILE__));
        }
        ajax::success(utils::o2a($eqLogic));
    }
    
    if (init('action') == 'save') {
        $eqLogicData = json_decode(init('eqLogic'), true);
        log::add('n8nconnect', 'debug', 'Données reçues pour sauvegarde : ' . json_encode($eqLogicData));
        
        if (!is_array($eqLogicData)) {
            throw new Exception(__('Données d\'équipement invalides', __FILE__));
        }
        
        // Si l'ID est vide, créer un nouvel équipement
        if (empty($eqLogicData['id'])) {
            log::add('n8nconnect', 'debug', 'Création d\'un nouvel équipement');
            $eqLogic = new n8nconnect();
        } else {
            log::add('n8nconnect', 'debug', 'Modification de l\'équipement ID : ' . $eqLogicData['id']);
            $eqLogic = n8nconnect::byId($eqLogicData['id']);
            if (!is_object($eqLogic)) {
                throw new Exception(__('Équipement introuvable', __FILE__));
            }
        }
        
        utils::a2o($eqLogic, jeedom::fromHumanReadable($eqLogicData));
        $eqLogic->save();
        log::add('n8nconnect', 'debug', 'Équipement sauvegardé avec succès, ID : ' . $eqLogic->getId());
        ajax::success(utils::o2a($eqLogic));
    }
    
    if (init('action') == 'remove') {
        $eqLogic = n8nconnect::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('Équipement introuvable', __FILE__));
        }
        $eqLogic->remove();
        ajax::success();
    }
    
    if (init('action') == 'getAll') {
        $eqLogics = n8nconnect::byType('n8nconnect');
        $result = [];
        foreach ($eqLogics as $eqLogic) {
            $result[] = utils::o2a($eqLogic);
        }
        ajax::success($result);
    }
    
    if (init('action') == 'getCmd') {
        $eqLogic = n8nconnect::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('Équipement introuvable', __FILE__));
        }
        $cmds = $eqLogic->getCmd();
        $result = [];
        foreach ($cmds as $cmd) {
            $result[] = utils::o2a($cmd);
        }
        ajax::success($result);
    }
    
    throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
