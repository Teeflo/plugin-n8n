<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once __DIR__  . '/../../../../core/php/core.inc.php';

class n8nconnect extends eqLogic {

    public static function callN8n($method, $endpoint, $data = null) {
        $base = trim(config::byKey('n8n_url', 'n8nconnect'), '/');
        $key = config::byKey('n8n_api_key', 'n8nconnect');
        $user = config::byKey('n8n_user', 'n8nconnect');
        $pass = config::byKey('n8n_pass', 'n8nconnect');
        
        // Vérification de la configuration
        if ($base == '') {
            throw new Exception(__('URL de l\'instance n8n manquante dans la configuration', __FILE__));
        }
        if ($key == '') {
            throw new Exception(__('Clé API n8n manquante dans la configuration', __FILE__));
        }
        
        // Nettoyage de l'URL de base
        if (substr($base, -7) === '/api/v1') {
            $base = substr($base, 0, -7);
        }
        $url = $base . '/api/v1' . $endpoint;
        
        // Log pour debug
        log::add('n8nconnect', 'debug', 'Appel n8n : ' . $method . ' ' . $url);
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
            'X-N8N-API-KEY: ' . $key,
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        // Ajout de l'authentification Basic si configurée
        if ($user != '' || $pass != '') {
            curl_setopt($curl, CURLOPT_USERPWD, $user . ':' . $pass);
            log::add('n8nconnect', 'debug', 'Authentification Basic configurée pour l\'utilisateur : ' . $user);
        }
        
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        
        if ($data !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        // Gestion des erreurs cURL
        if ($response === false) {
            log::add('n8nconnect', 'error', 'Erreur cURL : ' . $error);
            throw new Exception('Erreur de connexion : ' . $error);
        }
        
        // Gestion des codes d'erreur HTTP
        if ($code < 200 || $code >= 300) {
            $errorMsg = 'HTTP ' . $code . ' : ';
            
            // Tentative de décodage de la réponse d'erreur
            $decoded = json_decode($response, true);
            if (is_array($decoded) && isset($decoded['message'])) {
                $errorMsg .= $decoded['message'];
            } else {
                $errorMsg .= $response;
            }
            
            // Messages d'erreur spécifiques selon le code
            switch ($code) {
                case 401:
                    $errorMsg = __('Erreur d\'authentification (401) : Vérifiez votre clé API et vos identifiants Basic Auth', __FILE__);
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
            
            log::add('n8nconnect', 'error', 'Erreur API n8n : ' . $errorMsg . ' (URL: ' . $url . ')');
            throw new Exception($errorMsg);
        }
        
        // Décodage de la réponse JSON
        $decoded = json_decode($response, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            log::add('n8nconnect', 'error', 'Réponse JSON invalide : ' . $response);
            throw new Exception('Réponse JSON invalide de l\'API n8n');
        }
        
        log::add('n8nconnect', 'debug', 'Appel n8n réussi : ' . $method . ' ' . $endpoint);
        return $decoded;
    }

    public function postSave() {
        $commands = [
            'run' => ['name' => __('Lancer', __FILE__), 'type' => 'action', 'subType' => 'other'],
            'activate' => ['name' => __('Activer', __FILE__), 'type' => 'action', 'subType' => 'other'],
            'deactivate' => ['name' => __('Désactiver', __FILE__), 'type' => 'action', 'subType' => 'other'],
            'state' => ['name' => __('État', __FILE__), 'type' => 'info', 'subType' => 'binary']
        ];
        foreach ($commands as $logical => $info) {
            $cmd = $this->getCmd(null, $logical);
            if (!is_object($cmd)) {
                $cmd = new n8nconnectCmd();
                $cmd->setLogicalId($logical);
                $cmd->setEqLogic_id($this->getId());
                $cmd->setType($info['type']);
                $cmd->setSubType($info['subType']);
            }
            $cmd->setName($info['name']);
            $cmd->setIsVisible($logical !== 'state');
            $cmd->save();
        }
        $this->refreshInfo();
    }

    public function refreshInfo() {
        $id = $this->getConfiguration('workflow_id');
        if (!ctype_digit((string) $id)) {
            return;
        }
        try {
            $info = self::callN8n('GET', '/workflows/' . $id);
            $active = isset($info['active']) && $info['active'] ? 1 : 0;
        } catch (Exception $e) {
            $active = 0;
            log::add('n8nconnect', 'error', 'Erreur lors de la récupération du statut : ' . $e->getMessage());
        }
        $cmd = $this->getCmd(null, 'state');
        if (is_object($cmd)) {
            $cmd->event($active);
        }
    }

    public static function cron() {
        foreach (self::byType('n8nconnect') as $eq) {
            $eq->refreshInfo();
        }
    }

    public function launch() {
        $id = $this->getConfiguration('workflow_id');
        if ($id == '') {
            throw new Exception(__('ID de workflow manquant', __FILE__));
        }
        if (!ctype_digit((string) $id)) {
            throw new Exception(__('ID de workflow invalide', __FILE__));
        }
        return self::callN8n('POST', '/workflows/' . $id . '/run');
    }

    public function activate() {
        $id = $this->getConfiguration('workflow_id');
        if (!ctype_digit((string) $id)) {
            throw new Exception(__('ID de workflow invalide', __FILE__));
        }
        self::callN8n('POST', '/workflows/' . $id . '/activate');
        $this->refreshInfo();
    }

    public function deactivate() {
        $id = $this->getConfiguration('workflow_id');
        if (!ctype_digit((string) $id)) {
            throw new Exception(__('ID de workflow invalide', __FILE__));
        }
        self::callN8n('POST', '/workflows/' . $id . '/deactivate');
        $this->refreshInfo();
    }
}

class n8nconnectCmd extends cmd {
    public function execute($_options = array()) {
        switch ($this->getLogicalId()) {
            case 'run':
                $this->getEqLogic()->launch();
                return;
            case 'activate':
                $this->getEqLogic()->activate();
                return;
            case 'deactivate':
                $this->getEqLogic()->deactivate();
                return;
            default:
                throw new Exception(__('Commande inconnue', __FILE__));
        }
    }
}

?>
