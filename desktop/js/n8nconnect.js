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
  tr += '<input type="hidden" class="cmdAttr" data-l1key="id">' + init(_cmd.id)
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
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="webhook_url" placeholder="{{URL du webhook}}" title="{{URL du webhook}}" style="width:100%;">'
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="parameters" placeholder="{{Paramètres (JSON)}}" title="{{Paramètres à envoyer au webhook (JSON)}}" style="width:100%;margin-top:5px;">'
  tr += '</div>'
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
  console.log('loadWorkflows function called!');
  // Afficher un indicateur de chargement
  $('#bt_refreshWorkflow').html('<i class="fas fa-spinner fa-spin"></i>');
  
  $.ajax({
    type: 'POST',
    url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
    data: {action: 'listWorkflows'},
    dataType: 'json',
    timeout: 30000, // 30 secondes de timeout
    error: function (request, status, error) {
      
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
      
      if (data.state != 'ok') {
        var errorMessage = data.result || "{{Erreur lors de la récupération des workflows}}";
        $('#div_alert').showAlert({message: errorMessage, level: 'danger'});
        showManualWorkflowInput();
        return;
      }
      console.log('Workflows received:', data.result);
      
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
    },
    complete: function() {
      // Restaurer le bouton dans tous les cas (succès, erreur, timeout)
      $('#bt_refreshWorkflow').html('<i class="fas fa-sync"></i>');
    }
  });
}

$('#bt_refreshWorkflow').on('click', function () {
  loadWorkflows();
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
    showManualWorkflowInput();
  }
  
  // Initialisation du système de gestion des équipements Jeedom
  
  
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
      var eqLogicId = $('.eqLogicAttr[data-l1key=id]').val();
      console.log('Attempting to delete equipment with ID:', eqLogicId);

      if (!eqLogicId) {
        $('#div_alert').showAlert({message: '{{Impossible de supprimer un équipement non sauvegardé.}}', level: 'warning'});
        return;
      }

      $.ajax({
        type: 'POST',
        url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
        data: {
          action: 'remove',
          id: eqLogicId
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
          window.location.href = 'index.php?v=d&m=n8nconnect&p=n8nconnect';
        }
      });
    }
  });
  
  $('.eqLogicAction[data-action="returnToThumbnailDisplay"]').on('click', function () {
    $('.eqLogic').hide();
    $('.eqLogicThumbnailDisplay').show();
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
      },
      complete: function() {
        // Any loading indicators for loadCmd can be removed here if they exist
        // For now, just ensuring robustness.
      }
    });
  }
  // Gestionnaires d'événements pour les boutons de commande (délégation)
  $(document).on('click', '#table_cmd .cmdAction[data-action=configure]', function () {
    console.log('Configure button clicked!');
    var cmd = $(this).closest('.cmd').getValues('.cmdAttr');
    console.log('Command object for configure:', cmd);
    console.log('Type of jeedom:', typeof jeedom);
    console.log('Type of jeedom.cmd:', typeof jeedom.cmd);
    console.log('jeedom.cmd object:', jeedom.cmd);
    if (typeof jeedom.cmd !== 'undefined' && typeof jeedom.cmd.configure === 'function') {
      jeedom.cmd.configure(cmd);
    } else {
      console.error('jeedom.cmd.configure is not a function or jeedom.cmd is undefined.');
    }
  });

  $(document).on('click', '#table_cmd .cmdAction[data-action=test]', function () {
    console.log('Test button clicked!');
    var cmd = $(this).closest('.cmd').getValues('.cmdAttr');
    console.log('Command object for test:', cmd);
    console.log('Type of jeedom:', typeof jeedom);
    console.log('Type of jeedom.cmd:', typeof jeedom.cmd);
    console.log('jeedom.cmd object:', jeedom.cmd);

    var options = {};
    if (cmd.logicalId === 'run' && cmd.configuration && cmd.configuration.parameters) {
      try {
        options = JSON.parse(cmd.configuration.parameters);
      } catch (e) {
        $('#div_alert').showAlert({message: '{{Les paramètres doivent être un JSON valide.}}', level: 'danger'});
        return;
      }
    }

    if (typeof jeedom.cmd !== 'undefined' && typeof jeedom.cmd.test === 'function') {
      jeedom.cmd.test(cmd, options);
    } else {
      console.error('jeedom.cmd.test is not a function or jeedom.cmd is undefined.');
    }
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    if (e.target.hash == '#executionhistorytab') {
      loadWorkflowExecutions();
    }
  });

  function loadWorkflowExecutions() {
    var eqLogic_id = $('.eqLogicAttr[data-l1key=id]').val();
    var workflow_id = $('.eqLogicAttr[data-l1key=configuration][data-l2key=workflow_id]').val();

    console.log('eqLogic_id:', eqLogic_id);
    console.log('workflow_id:', workflow_id);

    if (!eqLogic_id || !workflow_id) {
      $('#div_alert').showAlert({message: "{{Veuillez sauvegarder l'équipement et sélectionner un workflow d'abord.}}", level: 'warning'});
      return;
    }

    $('#table_executions tbody').empty().append('<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> {{Chargement...}}</td></tr>');

    $.ajax({
      type: 'POST',
      url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
      data: {
        action: 'getWorkflowExecutions',
        workflow_id: workflow_id
      },
      dataType: 'json',
      error: function (request, status, error) {
        console.error('Erreur AJAX lors du chargement des exécutions:', request.responseText);
        $('#div_alert').showAlert({message: "{{Erreur lors du chargement de l'historique des exécutions.}}", level: 'danger'});
        $('#table_executions tbody').empty().append('<tr><td colspan="6" class="text-center">{{Erreur de chargement.}}</td></tr>');
      },
      success: function (data) {
        $('#table_executions tbody').empty();
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'});
          $('#table_executions tbody').append('<tr><td colspan="6" class="text-center">' + data.result + '</td></tr>');
          return;
        }

        if (data.result && data.result.data && data.result.data.length > 0) {
          $.each(data.result.data, function (i, execution) {
            var row = '<tr>';
            row += '<td>' + execution.id + '</td>';
            row += '<td>' + execution.status + '</td>';
            row += '<td>' + (execution.startedAt ? new Date(execution.startedAt).toLocaleString() : '') + '</td>';
            row += '<td>' + (execution.stoppedAt ? new Date(execution.stoppedAt).toLocaleString() : '') + '</td>';
            row += '<td>' + (execution.stoppedAt && execution.startedAt ? (new Date(execution.stoppedAt).getTime() - new Date(execution.startedAt).getTime()) : '') + '</td>';
            row += '<td>' + execution.mode + '</td>';
            row += '</tr>';
            $('#table_executions tbody').append(row);
          });
        } else {
          $('#table_executions tbody').append('<tr><td colspan="6" class="text-center">{{Aucune exécution trouvée pour ce workflow.}}</td></tr>');
        }
      }
    });
  };


})
