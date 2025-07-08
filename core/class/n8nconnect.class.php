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
        
        // Log de debug pour voir la valeur de la clé
        log::add('n8nconnect', 'debug', 'URL configurée : ' . $base);
        log::add('n8nconnect', 'debug', 'Clé API configurée : ' . (empty($key) ? 'VIDE' : substr($key, 0, 10) . '...'));
        
        // Vérification de la configuration
        if ($base == '') {
            throw new Exception(__('URL de l\'instance n8n manquante dans la configuration', __FILE__));
        }
        if ($key == '') {
            throw new Exception(__('Clé API n8n manquante dans la configuration', __FILE__));
        }
        
        // Vérifier si la clé commence par "crypt:" (cryptée par Jeedom)
        if (strpos($key, 'crypt:') === 0) {
            log::add('n8nconnect', 'error', 'La clé API est cryptée par Jeedom. Problème de configuration.');
            throw new Exception(__('La clé API est cryptée. Veuillez la reconfigurer dans les paramètres du plugin.', __FILE__));
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
            'X-N8N-API-KEY: ' . $key,
        ];
        if ($data !== null) {
            $headers[] = 'Content-Type: application/json';
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
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
        // Ne créer les commandes que si l'équipement a un ID valide et qu'il n'y a pas déjà de commandes
        if ($this->getId() == '') {
            return;
        }

        $commands = [
            'activate' => ['name' => __('Activer', __FILE__), 'type' => 'action', 'subType' => 'other'],
            'deactivate' => ['name' => __('Désactiver', __FILE__), 'type' => 'action', 'subType' => 'other'],
            'state' => ['name' => __('État', __FILE__), 'type' => 'info', 'subType' => 'binary']
        ];

        if ($this->getConfiguration('webhook_url') != '') {
            $commands['run'] = ['name' => __('Lancer', __FILE__), 'type' => 'action', 'subType' => 'other'];
        }

        foreach ($commands as $logical => $info) {
            $cmd = $this->getCmd(null, $logical);
            if (!is_object($cmd)) {
                $cmd = new n8nconnectCmd();
                $cmd->setLogicalId($logical);
                $cmd->setEqLogic_id($this->getId());
            }
            $cmd->setType($info['type']);
            $cmd->setSubType($info['subType']);
            $cmd->setName($info['name']);
            $cmd->setIsVisible(1);
            $cmd->setIsHistorized(($logical === 'state') ? 1 : 0);
            $cmd->save();
        }

        // Supprimer la commande "Lancer" si le webhook n'est plus configuré
        if ($this->getConfiguration('webhook_url') == '') {
            $cmd = $this->getCmd(null, 'run');
            if (is_object($cmd)) {
                $cmd->remove();
            }
        }

        $this->refreshInfo();
    }

    public function refreshInfo() {
        $id = $this->getConfiguration('workflow_id');
        log::add('n8nconnect', 'debug', 'refreshInfo: Tentative de rafraîchissement pour workflow ID: ' . $id);
        if (empty($id)) {
            log::add('n8nconnect', 'debug', 'refreshInfo: ID de workflow vide, annulation.');
            return;
        }
        try {
            $info = self::callN8n('GET', '/workflows/' . $id);
            log::add('n8nconnect', 'debug', 'refreshInfo: Réponse API n8n pour workflow ID ' . $id . ': ' . json_encode($info));
            $active = isset($info['active']) && $info['active'] ? 1 : 0;
        } catch (Exception $e) {
            $active = 0;
            log::add('n8nconnect', 'error', 'refreshInfo: Erreur lors de la récupération du statut pour workflow ID ' . $id . ': ' . $e->getMessage());
        }
        log::add('n8nconnect', 'debug', 'refreshInfo: Statut final pour workflow ID ' . $id . ': ' . $active);
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
        $webhook_url = $this->getConfiguration('webhook_url');
        if (empty($webhook_url)) {
            throw new Exception(__('URL de webhook manquante', __FILE__));
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $webhook_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response === false) {
            log::add('n8nconnect', 'error', 'Erreur cURL : ' . $error);
            throw new Exception('Erreur de connexion : ' . $error);
        }

        if ($code < 200 || $code >= 300) {
            log::add('n8nconnect', 'error', 'Erreur webhook : ' . $response);
            throw new Exception('Erreur webhook : ' . $response);
        }

        return $response;
    }

    public function activate() {
        $id = $this->getConfiguration('workflow_id');
        if (empty($id)) {
            throw new Exception(__('ID de workflow invalide', __FILE__));
        }
        self::callN8n('POST', '/workflows/' . $id . '/activate');
        $this->refreshInfo();
    }

    public function deactivate() {
        $id = $this->getConfiguration('workflow_id');
        if (empty($id)) {
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
