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
        
        // Vérifications préliminaires
        if (empty($url)) {
            throw new Exception(__('URL de l\'instance n8n manquante', __FILE__));
        }
        if (empty($key)) {
            throw new Exception(__('Clé API manquante', __FILE__));
        }
        
        // Test de connectivité de base
        $testUrl = $url . '/api/v1/workflows?limit=1';
        log::add('n8nconnect', 'debug', 'Test de connexion vers : ' . $testUrl);
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $testUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'X-N8N-API-KEY: ' . $key,
        ]);
        
        $resp = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($resp === false) {
            log::add('n8nconnect', 'error', 'Erreur cURL lors du test : ' . $error);
            throw new Exception(__('Erreur de connexion : ', __FILE__) . $error);
        }
        
        log::add('n8nconnect', 'debug', 'Code de réponse du test : ' . $code);
        
        if ($code === 200) {
            $decoded = json_decode($resp, true);
            if (is_array($decoded)) {
                log::add('n8nconnect', 'info', 'Test de connexion réussi');
                ajax::success(__('Connexion réussie', __FILE__));
            } else {
                throw new Exception(__('Réponse invalide de l\'API n8n', __FILE__));
            }
        } else {
            $errorMsg = __('Code réponse', __FILE__) . ' ' . $code . ' : ';
            $decoded = json_decode($resp, true);
            if (is_array($decoded) && isset($decoded['message'])) {
                $errorMsg .= $decoded['message'];
            } else {
                $errorMsg .= $resp;
            }
            
            // Messages d'erreur spécifiques
            switch ($code) {
                case 401:
                    $errorMsg = __('Erreur d\'authentification (401) : Vérifiez votre clé API', __FILE__);
                    break;
                case 403:
                    $errorMsg = __('Accès interdit (403) : Vérifiez les permissions de votre clé API', __FILE__);
                    break;
                case 404:
                    $errorMsg = __('Endpoint non trouvé (404) : Vérifiez l\'URL de votre instance n8n', __FILE__);
                    break;
                case 500:
                    $errorMsg = __('Erreur serveur n8n (500) : Vérifiez l\'état de votre instance n8n', __FILE__);
                    break;
            }
            
            log::add('n8nconnect', 'error', 'Test de connexion échoué : ' . $errorMsg);
            throw new Exception($errorMsg);
        }
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
            // Log détaillé de l'erreur
            log::add('n8nconnect', 'error', 'Erreur lors de la récupération des workflows : ' . $e->getMessage());
            
            // Message d'erreur plus informatif pour l'utilisateur
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, '401') !== false) {
                $errorMsg = __('Impossible de se connecter à n8n. Vérifiez votre configuration : URL et clé API.', __FILE__);
            } elseif (strpos($errorMsg, '404') !== false) {
                $errorMsg = __('URL de l\'instance n8n incorrecte ou instance n8n non accessible.', __FILE__);
            } elseif (strpos($errorMsg, 'timeout') !== false) {
                $errorMsg = __('Délai d\'attente dépassé. Vérifiez que votre instance n8n est accessible.', __FILE__);
            }
            
            ajax::error($errorMsg);
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
