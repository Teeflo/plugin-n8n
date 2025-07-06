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
        $user = init('user');
        $pass = init('pass');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . '/api/v1/workflows?limit=1');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'X-N8N-API-KEY: ' . $key,
        ]);
        if ($user != '' || $pass != '') {
            curl_setopt($curl, CURLOPT_USERPWD, $user . ':' . $pass);
        }
        $resp = curl_exec($curl);
        if ($resp === false) {
            throw new Exception(curl_error($curl));
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

    if (init('action') == 'listWorkflows') {
        $data = n8nconnect::callN8n('GET', '/workflows');
        $result = [];
        if (isset($data['data'])) {
            foreach ($data['data'] as $wf) {
                $result[] = ['id' => $wf['id'], 'name' => $wf['name']];
            }
        }
        ajax::success($result);
    }

    throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
