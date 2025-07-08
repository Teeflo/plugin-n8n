# Prompt d'Audit de Code - Plugin n8n Connect

Agis en tant qu'Auditeur de Code Expert et Ingénieur Logiciel Senior. Ta mission est de réaliser un audit complet et détaillé du code source d'un plugin que je vais te fournir.

Tu dois analyser le code sous plusieurs angles pour identifier les problèmes potentiels, les améliorations possibles et t'assurer de sa qualité générale.

## Contexte du Plugin

**Nom du Plugin :** n8n Connect

**Plateforme/CMS Cible et Version :** Jeedom 4.2.0 ou supérieure, PHP 8.1+

**Objectif Principal du Plugin :** Ce plugin permet de piloter et de superviser vos workflows n8n directement depuis votre interface domotique Jeedom. Il offre une intégration simple et efficace pour lancer des workflows, les activer/désactiver, et vérifier leur état.

**Dépendances Externes :**
- Instance n8n fonctionnelle et accessible
- API REST n8n activée
- Extension PHP cURL
- Clé API n8n valide avec permissions pour lister, activer/désactiver les workflows

## Code Source Complet :

### --- FILE: plugin_info/info.json ---
```json
{
    "id" : "n8nconnect",
    "name" : "n8n Connect",
    "description" : {
        "fr_FR" : "Plugin pour piloter des workflows n8n depuis Jeedom"
    },
    "licence" : "AGPL",
    "author" : "Jeedom Community",
    "require" : "4.2.0",
    "category" : "communication",
    "hasDependency" : false,
    "hasOwnDeamon" : false,
    "maxDependancyInstallTime" : 0,
    "language": ["fr_FR","en_US"],
    "compatibility": ["miniplus","smart","rpi","docker","diy","mobile","v4","atlas"]
}
```

### --- FILE: plugin_info/install.php ---
```php
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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

// Fonction exécutée automatiquement après l'installation du plugin
function n8nconnect_install() {
    // Vérifier que cURL est disponible
    if (!function_exists('curl_init')) {
        throw new Exception('L\'extension cURL de PHP est requise pour ce plugin');
    }
    
    // Créer le dossier de logs s'il n'existe pas
    $logDir = dirname(__FILE__) . '/../../../log/n8nconnect';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    log::add('n8nconnect', 'info', 'Installation du plugin n8nconnect terminée');
}

// Fonction exécutée automatiquement après la mise à jour du plugin
function n8nconnect_update() {
    // Vérifier que cURL est disponible
    if (!function_exists('curl_init')) {
        throw new Exception('L\'extension cURL de PHP est requise pour ce plugin');
    }
    
    // Créer le dossier de logs s'il n'existe pas
    $logDir = dirname(__FILE__) . '/../../../log/n8nconnect';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    log::add('n8nconnect', 'info', 'Mise à jour du plugin n8nconnect terminée');
}

// Fonction exécutée automatiquement après la suppression du plugin
function n8nconnect_remove() {
    log::add('n8nconnect', 'info', 'Suppression du plugin n8nconnect');
}
?>
```

### --- FILE: plugin_info/pre_install.php ---
```php
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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

// Fonction exécutée automatiquement avant la mise à jour du plugin
function n8nconnect_pre_update() {
}
?>
```

### --- FILE: plugin_info/packages.json ---
```json
{
  "pre-install": {
  },
  "apt": {
  },
  "pip3": {
  },
  "npm": {
  },
  "yarn": {
  },
  "plugin": {
  },
  "post-install": {
  }
}
```

### --- FILE: plugin_info/configuration.php ---
```php
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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<form class="form-horizontal">
  <fieldset>
    <div class="form-group">
      <label class="col-md-4 control-label">{{URL de l'instance n8n}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Adresse de base de votre instance n8n}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="n8n_url" placeholder="https://mon.n8n.local"/>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label">{{Clé API}}
        <sup><i class="fas fa-question-circle tooltips" title="{{Clé API pour l'accès REST à n8n}}"></i></sup>
      </label>
      <div class="col-md-4">
        <input class="configKey form-control" data-l1key="n8n_api_key" type="password" data-password="true"/>
      </div>
      <div class="col-md-2">
        <a class="btn btn-default" id="bt_testN8N"><i class="fas fa-check"></i> {{Tester}}</a>
      </div>
    </div>
  </fieldset>
</form>
<script>
$('#bt_testN8N').on('click', function(){
  var url = $('.configKey[data-l1key=n8n_url]').val();
  var key = $('.configKey[data-l1key=n8n_api_key]').val();
  jeedomUtils.hideAlert();
  $.ajax({
    type: 'POST',
    url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
    data: {
      action: 'test',
      url: url,
      key: key
    },
    dataType: 'json',
    error: function (request, status, error) {
      console.error('Erreur AJAX:', request.responseText);
      $('#div_alert').showAlert({message: '{{Erreur lors du test de connexion}}', level: 'danger'});
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
        return;
      }
      $('#div_alert').showAlert({message: data.result, level: 'success'});
    }
  });
});
</script>
```


### --- FILE: core/php/n8nconnect.inc.php ---
```php
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
/*
*
* Fichier d'inclusion si vous avez plusieurs fichiers de class ou 3rdParty à inclure
*
*/
```

### --- FILE: core/js/plugin.n8nconnect.js ---
```javascript
// Fichier JavaScript vide - peut être utilisé pour des extensions futures
```

### --- FILE: core/class/n8nconnect.class.php ---
```php
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
```


### --- FILE: core/ajax/n8nconnect.ajax.php ---
```php
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
        
        // Log des données sauvegardées pour debug
        $savedData = utils::o2a($eqLogic);
        log::add('n8nconnect', 'debug', 'Données sauvegardées : ' . json_encode($savedData));
        log::add('n8nconnect', 'debug', 'Workflow ID sauvegardé : ' . $eqLogic->getConfiguration('workflow_id'));
        
        ajax::success($savedData);
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
    
    if (init('action') == 'executeCmd') {
        $cmdId = init('id');
        if (empty($cmdId)) {
            throw new Exception(__('ID de commande manquant', __FILE__));
        }
        $cmd = cmd::byId($cmdId);
        if (!is_object($cmd)) {
            throw new Exception(__('Commande introuvable', __FILE__));
        }
        $result = $cmd->execute();
        ajax::success($result);
    }

    throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
?>
```


### --- FILE: desktop/php/n8nconnect.php ---
```php
<?php
if (!isConnect('admin')) {
throw new Exception('{{401 - Accès non autorisé}}');
}
// Déclaration des variables obligatoires
$plugin = plugin::byId('n8nconnect');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
<!-- Page d'accueil du plugin -->
<div class="col-xs-12 eqLogicThumbnailDisplay">
<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
<!-- Boutons de gestion du plugin -->
<div class="eqLogicThumbnailContainer">
<div class="cursor eqLogicAction logoPrimary" data-action="add">
<i class="fas fa-plus-circle"></i>
<br>
<span>{{Ajouter}}</span>
</div>
<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
<i class="fas fa-wrench"></i>
<br>
<span>{{Configuration}}</span>
</div>
</div>
<legend><i class="fas fa-table"></i> {{Mes workflows}}</legend>
<?php
if (count($eqLogics) == 0) {
echo '<br><div class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement n8n trouvé, cliquer sur "Ajouter" pour commencer}}</div>';
} else {
// Champ de recherche
echo '<div class="input-group" style="margin:5px;">';
echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
echo '<div class="input-group-btn">';
echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
echo '</div>';
echo '</div>';
// Liste des équipements du plugin
echo '<div class="eqLogicThumbnailContainer">';
foreach ($eqLogics as $eqLogic) {
$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
echo '<img src="' . $eqLogic->getImage() . '"/>';
echo '<br>';
echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
echo '<span class="hiddenAsCard displayTableRight hidden">';
echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
echo '</span>';
echo '</div>';
}
echo '</div>';
}
?>
</div> <!-- /.eqLogicThumbnailDisplay -->

<!-- Page de présentation de l'équipement -->
<div class="col-xs-12 eqLogic" style="display: none;">
<!-- barre de gestion de l'équipement -->
<div class="input-group pull-right" style="display:inline-flex;">
<span class="input-group-btn">
<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span></a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs">  {{Dupliquer}}</span></a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
</span>
</div>
<!-- Onglets -->
<ul class="nav nav-tabs" role="tablist">
<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
<li role="presentation"><a href="#commandtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-list"></i> {{Commandes}}</a></li>
</ul>
<div class="tab-content">
<!-- Onglet de configuration de l'équipement -->
<div role="tabpanel" class="tab-pane active" id="eqlogictab">
<form class="form-horizontal">
<fieldset>
<div class="col-lg-6">
<legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
<div class="form-group">
<label class="col-sm-4 control-label">{{Nom de l'équipement}}</label>
<div class="col-sm-6">
<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display:none;">
<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}">
</div>
</div>
<div class="form-group">
<label class="col-sm-4 control-label">{{Objet parent}}</label>
<div class="col-sm-6">
<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
<option value="">{{Aucun}}</option>
<?php
$options = '';
foreach ((jeeObject::buildTree(null, false)) as $object) {
$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
}
echo $options;
?>
</select>
</div>
</div>
<div class="form-group">
<label class="col-sm-4 control-label">{{Catégorie}}</label>
<div class="col-sm-6">
<?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
echo '<label class="checkbox-inline">';
echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" >' . $value['name'];
echo '</label>';
}
?>
</div>
</div>
<div class="form-group">
<label class="col-sm-4 control-label"></label>
<div class="col-sm-6">
<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
</div>
</div>
</div>

<div class="col-lg-6">
<legend><i class="fas fa-cogs"></i> {{Paramètres spécifiques}}</legend>
<div class="form-group">
<label class="col-sm-4 control-label">{{Workflow n8n}}
<sup><i class="fas fa-question-circle tooltips" title="{{Sélectionnez le workflow n8n à contrôler}}"></i></sup>
</label>
<div class="col-sm-6">
<div class="input-group">
<select id="sel_workflow_ui" class="form-control" style="display:none;">
<option value="">{{Sélectionner un workflow}}</option>
</select>
<input type="text" id="in_workflow_id_ui" class="form-control" placeholder="{{ID du workflow n8n}}">
<input type="hidden" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="workflow_id">
<span class="input-group-btn">
<button id="bt_refreshWorkflow" class="btn btn-default" type="button" title="{{Actualiser la liste des workflows}}"><i class="fas fa-sync"></i></button>
</span>
</div>
</div>
</div>
<div class="form-group">
<label class="col-sm-4 control-label">{{Webhook URL}}
<sup><i class="fas fa-question-circle tooltips" title="{{URL du webhook pour déclencher le workflow (optionnel)}}"></i></sup>
</label>
<div class="col-sm-6">
<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="webhook_url" placeholder="https://n8n.example.com/webhook/...">
</div>
</div>
<div class="form-group">
<label class="col-sm-4 control-label">{{Auto-actualisation}}
<sup><i class="fas fa-question-circle tooltips" title="{{Fréquence de rafraîchissement des commandes infos de l'équipement}}"></i></sup>
</label>
<div class="col-sm-6">
<div class="input-group">
<input type="text" class="eqLogicAttr form-control roundedLeft" data-l1key="configuration" data-l2key="autorefresh" placeholder="{{Cliquer sur ? pour afficher l'assistant cron}}">
<span class="input-group-btn">
<a class="btn btn-default cursor jeeHelper roundedRight" data-helper="cron" title="Assistant cron">
<i class="fas fa-question-circle"></i>
</a>
</span>
</div>
</div>
</div>
</div>
</fieldset>
</form>
</div>

<!-- Onglet des commandes de l'équipement -->
<div role="tabpanel" class="tab-pane" id="commandtab">
<a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une commande}}</a><br><br>
<div class="table-responsive">
<table id="table_cmd" class="table table-bordered table-condensed">
<thead>
<tr>
<th class="hidden-xs" style="min-width:50px;width:70px;">ID</th>
<th style="min-width:200px;width:350px;">{{Nom}}</th>
<th>{{Type}}</th>
<th style="min-width:260px;">{{Options}}</th>
<th>{{Etat}}</th>
<th style="min-width:80px;width:200px;">{{Actions}}</th>
</tr>
</thead>
<tbody>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>

<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'cmd', 'js');?>
<!-- Inclusion du fichier javascript du plugin -->
<?php include_file('desktop', 'n8nconnect', 'js', 'n8nconnect');?>
<?php include_file('core', 'plugin.n8nconnect', 'js');?>
```

### --- FILE: desktop/js/n8nconnect.js ---
```javascript
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

/* Permet la réorganisation des commandes dans l'équipement */
$("#table_cmd").sortable({
  axis: "y",
  cursor: "move",
  items: ".cmd",
  placeholder: "ui-state-highlight",
  tolerance: "intersect",
  forcePlaceholderSize: true
})

/* Fonction permettant l'affichage des commandes dans l'équipement */
function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    var _cmd = {configuration: {}}
  }
  if (!isset(_cmd.configuration)) {
    _cmd.configuration = {}
  }
  var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">' 
  tr += '<td class="hidden-xs">'
  tr += '<input type="hidden" class="cmdAttr" data-l1key="id">'
  tr += '</td>'
  tr += '<td>'
  tr += '<div class="input-group">'
  tr += '<input class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom de la commande}}">'
  tr += '<span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>'
  tr += '<span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;"></span>'
  tr += '</div>'
  tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display:none;margin-top:5px;" title="{{Commande info liée}}">'
  tr += '<option value="">{{Aucune}}</option>'
  tr += '</select>'
  tr += '</td>'
  tr += '<td>'
  tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>'
  tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>'
  tr += '</td>'
  tr += '<td>'
  tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/>{{Afficher}}</label> '
  tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isHistorized" checked/>{{Historiser}}</label> '
  tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label> '
  tr += '<div style="margin-top:7px;">'
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
  tr += '</div>'
  tr += '</td>'
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>'; 
  tr += '</td>';
  tr += '<td>'
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> '
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> Tester</a>'
  }
  tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove" title="{{Supprimer la commande}}"></i></td>'
  tr += '</tr>'
  $('#table_cmd tbody').append(tr)
  var tr = $('#table_cmd tbody tr').last()
  jeedom.eqLogic.buildSelectCmd({
    id:  $('.eqLogicAttr[data-l1key=id]').val(),
    filter: {type: 'info'},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (result) {
      tr.find('.cmdAttr[data-l1key=value]').append(result)
      tr.setValues(_cmd, '.cmdAttr')
      tr.find('input[data-l1key="logicalId"]').val(_cmd.logicalId);
      jeedom.cmd.changeType(tr, init(_cmd.subType))
    }
  })
}

function showManualWorkflowInput () {
  $('#in_workflow_id_ui').show()
  $('#sel_workflow_ui').hide()
}

function hideManualWorkflowInput () {
  $('#in_workflow_id_ui').hide()
  $('#sel_workflow_ui').show()
}

function loadWorkflows () {
  // Afficher un indicateur de chargement
  $('#bt_refreshWorkflow').html('<i class="fas fa-spinner fa-spin"></i>');
  
  $.ajax({
    type: 'POST',
    url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
    data: {action: 'listWorkflows'},
    dataType: 'json',
    timeout: 30000, // 30 secondes de timeout
    error: function (request, status, error) {
      // Restaurer le bouton
      $('#bt_refreshWorkflow').html('<i class="fas fa-sync"></i>');
      
      var errorMessage = "{{Impossible de récupérer la liste des workflows.}}";
      
      if (status === 'timeout') {
        errorMessage = "{{Délai d'attente dépassé. Vérifiez que votre instance n8n est accessible.}}";
      } else if (request.status === 401) {
        errorMessage = "{{Erreur d'authentification. Vérifiez votre clé API.}}";
      } else if (request.status === 404) {
        errorMessage = "{{URL de l'instance n8n incorrecte.}}";
      } else if (request.status >= 500) {
        errorMessage = "{{Erreur serveur n8n. Vérifiez l'état de votre instance.}}";
      }
      
      $('#div_alert').showAlert({message: errorMessage, level: 'warning'});
      showManualWorkflowInput();
      
      // Log de l'erreur pour debug
      console.error('Erreur lors du chargement des workflows:', status, error, request.responseText);
    },
    success: function (data) {
      // Restaurer le bouton
      $('#bt_refreshWorkflow').html('<i class="fas fa-sync"></i>');
      
      if (data.state != 'ok') {
        var errorMessage = data.result || "{{Erreur lors de la récupération des workflows}}";
        $('#div_alert').showAlert({message: errorMessage, level: 'danger'});
        showManualWorkflowInput();
        return;
      }
      
      var select = $('#sel_workflow_ui');
      select.empty();
      
      if (data.result && data.result.length > 0) {
        $.each(data.result, function (i, wf) {
          select.append('<option value="' + wf.id + '">' + wf.name + '</option>');
        });
        hideManualWorkflowInput();
        
        // Récupérer la valeur actuelle du workflow_id
        var currentWorkflowId = $('.eqLogicAttr[data-l1key=configuration][data-l2key=workflow_id]').val();
        console.log('Workflow ID actuel:', currentWorkflowId);
        
        // Sélectionner le workflow si une valeur est définie
        if (currentWorkflowId && currentWorkflowId !== '') {
          select.val(currentWorkflowId);
          console.log('Workflow sélectionné:', currentWorkflowId);
        }
        
        // Message de succès
        $('#div_alert').showAlert({message: "{{Liste des workflows récupérée avec succès}}", level: 'success'});
      } else {
        $('#div_alert').showAlert({message: "{{Aucun workflow trouvé dans votre instance n8n}}", level: 'info'});
        showManualWorkflowInput();
      }
    }
  });
}

$('#bt_refreshWorkflow').on('click', function () {
  loadWorkflows()
})

$(document).on('change', '#sel_workflow_ui', function () {
  $('.eqLogicAttr[data-l1key=configuration][data-l2key=workflow_id]').val($(this).val())
})

$(document).on('input', '#in_workflow_id_ui', function () {
  $('.eqLogicAttr[data-l1key=configuration][data-l2key=workflow_id]').val($(this).val())
})

$(document).ready(function () {
  // Initialisation des workflows si on est sur la page d'équipement
  if ($('#bt_refreshWorkflow').length) {
    showManualWorkflowInput()
    loadWorkflows()
  }
  
  // Initialisation du système de gestion des équipements Jeedom
  $('.eqLogicAction[data-action="add"]').on('click', function () {
    $.ajax({
      type: 'POST',
      url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
      data: {action: 'add'},
      dataType: 'json',
      error: function (request, status, error) {
        console.error('Erreur AJAX:', request.responseText);
        $('#div_alert').showAlert({message: '{{Erreur lors de la création}}', level: 'danger'});
      },
      success: function (data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'});
          return;
        }
        $('.eqLogic').setValues(data.result, '.eqLogicAttr');
        $('.eqLogicThumbnailDisplay').hide();
        $('.eqLogic').show();
        loadCmd();
        // Recharger les workflows quand on crée un nouvel équipement
        if ($('#bt_refreshWorkflow').length) {
          loadWorkflows();
        }
      }
    });
  });
  
  $('.eqLogicAction[data-action="save"]').on('click', function () {
    var eqLogic = $('.eqLogic').getValues('.eqLogicAttr')[0];
    
    // Log des données avant envoi
    console.log('Données à sauvegarder:', eqLogic);
    console.log('Workflow ID avant sauvegarde:', eqLogic.configuration ? eqLogic.configuration.workflow_id : 'non défini');
    
    // Validation basique
    if (!eqLogic.name || eqLogic.name.trim() === '') {
      $('#div_alert').showAlert({message: '{{Le nom de l\'équipement est obligatoire}}', level: 'warning'});
      return;
    }
    
    $.ajax({
      type: 'POST',
      url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
      data: {
        action: 'save',
        eqLogic: JSON.stringify(eqLogic)
      },
      dataType: 'json',
      error: function (request, status, error) {
        console.error('Erreur AJAX:', request.responseText);
        $('#div_alert').showAlert({message: '{{Erreur lors de la sauvegarde}}', level: 'danger'});
      },
      success: function (data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'});
          return;
        }
        
        console.log('Réponse de sauvegarde:', data.result);
        console.log('Workflow ID après sauvegarde:', data.result.configuration ? data.result.configuration.workflow_id : 'non défini');
        
        $('#div_alert').showAlert({message: '{{Équipement sauvegardé}}', level: 'success'});
        
        // Mettre à jour les données de l'équipement avec la réponse du serveur
        if (data.result) {
          $('.eqLogic').setValues(data.result, '.eqLogicAttr');
          
          // Si c'est un nouvel équipement, mettre à jour l'ID
          if (!eqLogic.id && data.result.id) {
            $('.eqLogicAttr[data-l1key=id]').val(data.result.id);
          }
          
          // Recharger les workflows pour s'assurer que la sélection est correcte
          if ($('#bt_refreshWorkflow').length) {
            loadWorkflows();
          }
        }
      }
    });
  });
  
  $('.eqLogicAction[data-action="remove"]').on('click', function () {
    if (confirm('{{Êtes-vous sûr de vouloir supprimer cet équipement ?}}')) {
      var eqLogic = $('.eqLogic').getValues('.eqLogicAttr')[0];
      $.ajax({
        type: 'POST',
        url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
        data: {
          action: 'remove',
          id: eqLogic.id
        },
        dataType: 'json',
        error: function (request, status, error) {
          console.error('Erreur AJAX:', request.responseText);
          $('#div_alert').showAlert({message: '{{Erreur lors de la suppression}}', level: 'danger'});
        },
        success: function (data) {
          if (data.state != 'ok') {
            $('#div_alert').showAlert({message: data.result, level: 'danger'});
            return;
          }
          $('.eqLogic').hide();
          $('.eqLogicThumbnailDisplay').show();
          location.reload(); // Recharger seulement après suppression
        }
      });
    }
  });
  
  $('.eqLogicAction[data-action="returnToThumbnailDisplay"]').on('click', function () {
    $('.eqLogic').hide();
    $('.eqLogicThumbnailDisplay').show();
  });
  
  $('.eqLogicAction[data-action="gotoPluginConf"]').on('click', function () {
    window.location.href = 'index.php?v=d&p=plugin&id=n8nconnect&plugin=n8nconnect';
  });
  
  $('.eqLogicDisplayCard').on('click', function () {
    var eqLogic_id = $(this).attr('data-eqLogic_id');
    $.ajax({
      type: 'POST',
      url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
      data: {
        action: 'get',
        id: eqLogic_id
      },
      dataType: 'json',
      error: function (request, status, error) {
        console.error('Erreur AJAX:', request.responseText);
        $('#div_alert').showAlert({message: '{{Erreur lors du chargement}}', level: 'danger'});
      },
      success: function (data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'});
          return;
        }
        $('.eqLogic').setValues(data.result, '.eqLogicAttr');
        $('.eqLogicThumbnailDisplay').hide();
        $('.eqLogic').show();
        loadCmd();
        // Recharger les workflows quand on ouvre un équipement
        if ($('#bt_refreshWorkflow').length) {
          loadWorkflows();
        }
      }
    });
  });
  
  function loadCmd() {
    var eqLogic_id = $('.eqLogicAttr[data-l1key=id]').val();
    if (!eqLogic_id) return;
    
    $.ajax({
      type: 'POST',
      url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
      data: {
        action: 'getCmd',
        id: eqLogic_id
      },
      dataType: 'json',
      error: function (request, status, error) {
        console.error('Erreur AJAX:', request.responseText);
        $('#div_alert').showAlert({message: '{{Erreur lors du chargement des commandes}}', level: 'danger'});
      },
      success: function (data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'});
          return;
        }
        $('#table_cmd tbody').empty();
        $.each(data.result, function (i, cmd) {
          addCmdToTable(cmd);
        });
      }
    });
  }
  
  // Gestionnaires d'événements pour les boutons de commande (délégation)
  $(document).on('click', '#table_cmd .cmdAction[data-action=configure]', function () {
    var cmd = $(this).closest('.cmd').getValues('.cmdAttr');
    if (typeof jeedom.cmd !== 'undefined' && typeof jeedom.cmd.configure === 'function') {
      jeedom.cmd.configure(cmd);
    } else {
      console.error('jeedom.cmd.configure is not a function or jeedom.cmd is undefined.');
    }
  });

  $(document).on('click', '#table_cmd .cmdAction[data-action=test]', function () {
    var cmd = $(this).closest('.cmd').getValues('.cmdAttr');
    if (typeof jeedom.cmd !== 'undefined' && typeof jeedom.cmd.test === 'function') {
      jeedom.cmd.test(cmd);
    } else {
      console.error('jeedom.cmd.test is not a function or jeedom.cmd is undefined.');
    }
  });
})
```

## Instructions pour l'Audit (Processus Étape par Étape) :

### 1. Analyse de la Version et des Dépendances :

Sur la base des informations fournies, vérifie si les versions des dépendances ou de la plateforme cible sont obsolètes.
Signale toute dépendance connue pour avoir des vulnérabilités de sécurité.

### 2. Recherche de Bugs et d'Erreurs (Analyse Statique) :

Examine le code pour des erreurs de syntaxe, des variables non définies, des appels de fonction incorrects.
Identifie les failles de sécurité courantes (Ex: injection SQL, XSS, CSRF, gestion incorrecte des permissions). Sois très spécifique dans tes recommandations.
Recherche les conditions de concurrence ("race conditions") ou les "deadlocks" potentiels.
Vérifie que la gestion des erreurs est robuste (utilisation de blocs try-catch, gestion des exceptions, etc.).

### 3. Analyse de la Logique et de la Cohérence :

Évalue si la logique du code correspond à l'objectif principal du plugin décrit.
Identifie toute section du code qui semble illogique, redondante ou inutilement complexe ("code smell").
Vérifie la cohérence des conventions de nommage et du style de code.

### 4. Recommandations d'Optimisation et de Bonnes Pratiques :

Suggère des améliorations de performance (Ex: requêtes de base de données inefficaces, boucles lourdes).
Recommande des refactorisations pour améliorer la lisibilité, la maintenabilité et le respect des principes SOLID.
Assure-toi que le code respecte les bonnes pratiques et les standards de la plateforme cible (Ex: WordPress Coding Standards).

## Format de Sortie Attendu :

Génère un rapport d'audit structuré en Markdown. Utilise le format suivant :

# Rapport d'Audit du Plugin : n8n Connect

**Note Globale :** [Donne une note de A (Excellent) à F (Critique) avec une justification brève.]

## 1. Résumé Exécutif

**Points Forts :** [Liste 2-3 points positifs du code.]
**Points Critiques :** [Liste les 2-3 problèmes les plus urgents à corriger.]

## 2. Audit de Sécurité (Gravité : Élevée)

**Vulnérabilité 1 :** [Description du problème, fichier et ligne concernés, et suggestion de correction.]
**Vulnérabilité 2 :** [...]

## 3. Bugs et Erreurs Potentielles (Gravité : Moyenne)

**Bug 1 :** [Description du bug, fichier et ligne concernés, et comment le reproduire/corriger.]
**Bug 2 :** [...]

## 4. Incohérences Logiques et "Code Smells" (Gravité : Faible)

**Problème 1 :** [Description de la logique confuse ou du code redondant, et suggestion de refactorisation.]
**Problème 2 :** [...]

## 5. Recommandations d'Optimisation et de Bonnes Pratiques

**Performance :** [Suggestion pour améliorer la vitesse ou l'efficacité.]
**Maintenabilité :** [Suggestion pour rendre le code plus facile à maintenir.]
**Conformité :** [Suggestion pour respecter les standards de la plateforme.]

## Instructions Négatives :

- Ne pas inventer d'informations si le code n'est pas fourni.
- Ne pas commenter la mise en forme du code (indentation, etc.) sauf si elle nuit gravement à la lisibilité.
- Ne pas exécuter le code. L'analyse doit être purement statique.
