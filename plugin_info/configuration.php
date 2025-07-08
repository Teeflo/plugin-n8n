<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
  include_file('desktop', '404', 'php');
  die();
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
    <div class="form-group">
      <label class="col-md-4 control-label">{{URL API entrante}}
        <sup><i class="fas fa-question-circle tooltips" title="{{A utiliser dans vos workflows n8n pour envoyer des données à Jeedom}}"></i></sup>
      </label>
      <div class="col-md-6">
        <?php
          $key = config::byKey('api_key', 'n8nconnect');
          $base = 'https://' . $_SERVER['HTTP_HOST'];
          $apiUrl = $base . '/core/api/jeeApi.php?plugin=n8nconnect&type=api&apikey=' . $key . '&eqLogic_id=[ID_EQUIPEMENT]&cmd_name=[NOM_COMMANDE]&value=[VALEUR]';
        ?>
        <input type="text" class="form-control" readonly value="<?php echo $apiUrl; ?>" />
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
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
      } else {
        $('#div_alert').showAlert({message: data.result, level: 'success'});
      }
    }
  });
});
</script>
