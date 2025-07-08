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

    // Générer la clé API du plugin si nécessaire
    if (config::byKey('api_key', 'n8nconnect') == '') {
        $key = bin2hex(random_bytes(16));
        config::save('api_key', $key, 'n8nconnect');
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

    // Générer la clé API si elle n'existe pas (mise à jour depuis ancienne version)
    if (config::byKey('api_key', 'n8nconnect') == '') {
        $key = bin2hex(random_bytes(16));
        config::save('api_key', $key, 'n8nconnect');
    }
    
    log::add('n8nconnect', 'info', 'Mise à jour du plugin n8nconnect terminée');
}

// Fonction exécutée automatiquement après la suppression du plugin
function n8nconnect_remove() {
    log::add('n8nconnect', 'info', 'Suppression du plugin n8nconnect');
}
