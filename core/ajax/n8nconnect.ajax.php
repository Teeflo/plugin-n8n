<?php
try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

    ajax::init();

    if (init('action') == 'test') {
        $url = rtrim(init('url'), '/');
        $key = init('key');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '/api/v1/workflows');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
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
        if ($code >= 200 && $code < 300) {
            ajax::success(true);
        }
        throw new Exception(__('Réponse invalide', __FILE__));
    }

    throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
