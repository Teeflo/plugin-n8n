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
  tr += '<span class="cmdAttr" data-l1key="id"></span>'
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
  tr += '<td>'
  tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>'
  tr += '</td>'
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
      jeedom.cmd.changeType(tr, init(_cmd.subType))
    }
  })
}

function showManualWorkflowInput () {
  $('#in_workflow_id').show()
  $('#sel_workflow').hide()
}

function hideManualWorkflowInput () {
  $('#in_workflow_id').hide()
  $('#sel_workflow').show()
}

function loadWorkflows () {
  // Afficher un indicateur de chargement
  $('#bt_refreshWorkflow').html('<i class="fas fa-spinner fa-spin"></i>')

  $.ajax({
    type: 'POST',
    url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
    data: {action: 'listWorkflows'},
    dataType: 'json',
    timeout: 30000, // 30 secondes de timeout
    error: function (request, status, error) {
      // Restaurer le bouton
      $('#bt_refreshWorkflow').html('<i class="fas fa-sync"></i>')

      var errorMessage = "{{Impossible de récupérer la liste des workflows.}}"

      if (status === 'timeout') {
        errorMessage = "{{Délai d'attente dépassé. Vérifiez que votre instance n8n est accessible.}}"
      } else if (request.status === 401) {
        errorMessage = "{{Erreur d'authentification. Vérifiez votre clé API.}}"
      } else if (request.status === 404) {
        errorMessage = "{{URL de l'instance n8n incorrecte.}}"
      } else if (request.status >= 500) {
        errorMessage = "{{Erreur serveur n8n. Vérifiez l'état de votre instance.}}"
      }

      $('#div_alert').showAlert({message: errorMessage, level: 'warning'})
      showManualWorkflowInput()

      // Log de l'erreur pour debug
      console.error('Erreur lors du chargement des workflows:', status, error, request.responseText)
    },
    success: function (data) {
      // Restaurer le bouton
      $('#bt_refreshWorkflow').html('<i class="fas fa-sync"></i>')

      if (data.state != 'ok') {
        var errorMessage = data.result || "{{Erreur lors de la récupération des workflows}}"
        $('#div_alert').showAlert({message: errorMessage, level: 'danger'})
        showManualWorkflowInput()
        return
      }

      var select = $('#sel_workflow')
      select.empty()

      if (data.result && data.result.length > 0) {
        $.each(data.result, function (i, wf) {
          select.append('<option value="' + wf.id + '">' + wf.name + '</option>')
        })
        hideManualWorkflowInput()
        select.val($('.eqLogicAttr[data-l1key=configuration][data-l2key=workflow_id]').val())

        // Message de succès
        $('#div_alert').showAlert({message: "{{Liste des workflows récupérée avec succès}}", level: 'success'})
      } else {
        $('#div_alert').showAlert({message: "{{Aucun workflow trouvé dans votre instance n8n}}", level: 'info'})
        showManualWorkflowInput()
      }
    }
  })
}

$('#bt_refreshWorkflow').on('click', function () {
  loadWorkflows()
})

$(document).ready(function () {
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
        console.error('Erreur AJAX:', request.responseText)
        $('#div_alert').showAlert({message: '{{Erreur lors de la création}}', level: 'danger'})
      },
      success: function (data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'})
          return
        }
        $('.eqLogic').setValues(data.result, '.eqLogicAttr')
        $('.eqLogicThumbnailDisplay').hide()
        $('.eqLogic').show()
        loadCmd()
        if ($('#bt_refreshWorkflow').length) {
          loadWorkflows()
        }
      }
    })
  })

  $('.eqLogicAction[data-action="save"]').on('click', function () {
    var eqLogic = $('.eqLogic').getValues('.eqLogicAttr')[0]

    // Validation basique
    if (!eqLogic.name || eqLogic.name.trim() === '') {
      $('#div_alert').showAlert({message: '{{Le nom de l\'équipement est obligatoire}}', level: 'warning'})
      return
    }

    $.ajax({
      type: 'POST',
      url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
      data: {
        action: 'save',
        eqLogic: json_encode(eqLogic)
      },
      dataType: 'json',
      error: function (request, status, error) {
        console.error('Erreur AJAX:', request.responseText)
        $('#div_alert').showAlert({message: '{{Erreur lors de la sauvegarde}}', level: 'danger'})
      },
      success: function (data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'})
          return
        }
        $('#div_alert').showAlert({message: '{{Équipement sauvegardé}}', level: 'success'})
        if (!eqLogic.id && data.result && data.result.id) {
          $('.eqLogicAttr[data-l1key=id]').val(data.result.id)
        }
      }
    })
  })

  $('.eqLogicAction[data-action="remove"]').on('click', function () {
    if (confirm('{{Êtes-vous sûr de vouloir supprimer cet équipement ?}}')) {
      var eqLogic = $('.eqLogic').getValues('.eqLogicAttr')[0]
      $.ajax({
        type: 'POST',
        url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
        data: {
          action: 'remove',
          id: eqLogic.id
        },
        dataType: 'json',
        error: function (request, status, error) {
          console.error('Erreur AJAX:', request.responseText)
          $('#div_alert').showAlert({message: '{{Erreur lors de la suppression}}', level: 'danger'})
        },
        success: function (data) {
          if (data.state != 'ok') {
            $('#div_alert').showAlert({message: data.result, level: 'danger'})
            return
          }
          $('.eqLogic').hide()
          $('.eqLogicThumbnailDisplay').show()
          location.reload()
        }
      })
    }
  })

  $('.eqLogicAction[data-action="returnToThumbnailDisplay"]').on('click', function () {
    $('.eqLogic').hide()
    $('.eqLogicThumbnailDisplay').show()
  })

  $('.eqLogicAction[data-action="gotoPluginConf"]').on('click', function () {
    window.location.href = 'index.php?v=d&p=plugin&id=n8nconnect&plugin=n8nconnect'
  })

  $('.eqLogicDisplayCard').on('click', function () {
    var eqLogic_id = $(this).attr('data-eqLogic_id')
    $.ajax({
      type: 'POST',
      url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
      data: {
        action: 'get',
        id: eqLogic_id
      },
      dataType: 'json',
      error: function (request, status, error) {
        console.error('Erreur AJAX:', request.responseText)
        $('#div_alert').showAlert({message: '{{Erreur lors du chargement}}', level: 'danger'})
      },
      success: function (data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'})
          return
        }
        $('.eqLogic').setValues(data.result, '.eqLogicAttr')
        $('.eqLogicThumbnailDisplay').hide()
        $('.eqLogic').show()
        loadCmd()
        if ($('#bt_refreshWorkflow').length) {
          loadWorkflows()
        }
      }
    })
  })

  function loadCmd() {
    var eqLogic_id = $('.eqLogicAttr[data-l1key=id]').val()
    if (!eqLogic_id) return

    $.ajax({
      type: 'POST',
      url: 'plugins/n8nconnect/core/ajax/n8nconnect.ajax.php',
      data: {
        action: 'getCmd',
        id: eqLogic_id
      },
      dataType: 'json',
      error: function (request, status, error) {
        console.error('Erreur AJAX:', request.responseText)
        $('#div_alert').showAlert({message: '{{Erreur lors du chargement des commandes}}', level: 'danger'})
      },
      success: function (data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'})
          return
        }
        $('#table_cmd tbody').empty()
        $.each(data.result, function (i, cmd) {
          addCmdToTable(cmd)
        })
      }
    })
  }
})
