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

        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception(__('URL invalide', __FILE__));
        }
        if ($key === '') {
            throw new Exception(__('Clé API manquante', __FILE__));
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url . '/api/v1/workflows?limit=1',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'X-N8N-API-KEY: ' . $key,
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'Jeedom-n8nconnect',
        ]);

        $resp = curl_exec($curl);
        if ($resp === false) {
            $msg = curl_error($curl);
            curl_close($curl);
            throw new Exception($msg);
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

    throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
