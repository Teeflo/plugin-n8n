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
        throw new Exception(__('L\'extension cURL de PHP est requise pour ce plugin', __FILE__));
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
        throw new Exception(__('L\'extension cURL de PHP est requise pour ce plugin', __FILE__));
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
    log::add('n8nconnect', 'info', __('Suppression du plugin n8nconnect', __FILE__));
}
