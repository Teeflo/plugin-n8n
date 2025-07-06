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
        if ($key !== '') {
            $key = utils::decrypt($key);
        }
        if ($base == '' || $key == '') {
            throw new Exception(__('Configuration n8n incomplète', __FILE__));
        }
        $url = $base . '/api/v1' . $endpoint;
        log::add('n8nconnect', 'debug', 'API ' . $method . ' ' . $url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
            'X-N8N-API-KEY: ' . $key,
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
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
        log::add('n8nconnect', 'debug', 'HTTP ' . $code . ' reçu');
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
        return self::callN8n('POST', '/workflows/' . $id . '/run');
    }

    public function activate() {
        $id = $this->getConfiguration('workflow_id');
        if (!ctype_digit((string) $id)) {
            throw new Exception(__('ID de workflow invalide', __FILE__));
        }
        self::callN8n('POST', '/workflows/' . $id . '/activate');
    }

    public function deactivate() {
        $id = $this->getConfiguration('workflow_id');
        if (!ctype_digit((string) $id)) {
            throw new Exception(__('ID de workflow invalide', __FILE__));
        }
        self::callN8n('POST', '/workflows/' . $id . '/deactivate');
    }

    public function postSave() {
        $cmds = [
            'run' => __('Lancer', __FILE__),
            'activate' => __('Activer', __FILE__),
            'deactivate' => __('Désactiver', __FILE__),
        ];
        foreach ($cmds as $logical => $name) {
            $cmd = $this->getCmd(null, $logical);
            if (!is_object($cmd)) {
                $cmd = new n8nconnectCmd();
                $cmd->setEqLogic_id($this->getId());
                $cmd->setLogicalId($logical);
                $cmd->setType('action');
                $cmd->setSubType('other');
            }
            $cmd->setName($name);
            $cmd->save();
        }
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
