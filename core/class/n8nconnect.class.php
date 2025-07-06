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

    private static function buildUrl($endpoint) {
        $base = trim(config::byKey('n8n_url', 'n8nconnect'), '/');
        if ($base === '' || !filter_var($base, FILTER_VALIDATE_URL)) {
            throw new Exception(__('URL n8n invalide', __FILE__));
        }
        return $base . '/api/v1' . $endpoint;
    }

    public static function callN8n($method, $endpoint, $data = null) {
        $key = config::byKey('n8n_api_key', 'n8nconnect');
        if ($key === '') {
            throw new Exception(__('Clé API n8n manquante', __FILE__));
        }

        $url = self::buildUrl($endpoint);
        log::add('n8nconnect', 'debug', 'Call ' . $method . ' ' . $url);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-N8N-API-KEY: ' . $key,
            ],
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'Jeedom-n8nconnect',
        ]);

        if ($data !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);
        if ($response === false) {
            $msg = curl_error($curl);
            curl_close($curl);
            throw new Exception('Curl error : ' . $msg);
        }

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($code < 200 || $code >= 300) {
            throw new Exception('HTTP ' . $code . ' : ' . $response);
        }

        $decoded = json_decode($response, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response');
        }

        return $decoded;
    }

    public function launch() {
        $id = $this->getConfiguration('workflow_id');
        if ($id == '') {
            throw new Exception(__('ID de workflow manquant', __FILE__));
        }
        if (!ctype_digit((string) $id)) {
            throw new Exception(__('ID de workflow invalide', __FILE__));
        }
        log::add('n8nconnect', 'info', 'Run workflow #' . $id);
        return self::callN8n('POST', '/workflows/' . $id . '/run');
    }

    public function activate() {
        $id = $this->getConfiguration('workflow_id');
        if (!ctype_digit((string) $id)) {
            throw new Exception(__('ID de workflow invalide', __FILE__));
        }
        log::add('n8nconnect', 'info', 'Activate workflow #' . $id);
        self::callN8n('POST', '/workflows/' . $id . '/activate');
    }

    public function deactivate() {
        $id = $this->getConfiguration('workflow_id');
        if (!ctype_digit((string) $id)) {
            throw new Exception(__('ID de workflow invalide', __FILE__));
        }
        log::add('n8nconnect', 'info', 'Deactivate workflow #' . $id);
        self::callN8n('POST', '/workflows/' . $id . '/deactivate');
    }
}

class n8nconnectCmd extends cmd {
    public function execute($_options = array()) {
        switch ($this->getLogicalId()) {
            case 'run':
                return $this->getEqLogic()->launch();
            case 'activate':
                $this->getEqLogic()->activate();
                return;
            case 'deactivate':
                $this->getEqLogic()->deactivate();
                return;
            default:
                log::add('n8nconnect', 'error', __('Commande inconnue', __FILE__) . ' : ' . $this->getLogicalId());
                throw new Exception(__('Commande inconnue', __FILE__));
        }
    }
}

?>
