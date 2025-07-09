# Changelog n8n Connect

## 0.1.0
- Primera versión del plugin n8n Connect para Jeedom.
  Este plugin le permite controlar y supervisar sus flujos de trabajo de n8n directamente desde su interfaz de domótica Jeedom. Ofrece una integración simple y efectiva para lanzar flujos de trabajo, activarlos/desactivarlos y verificar su estado.

  Características incluidas:
  - Configuración de instancia de n8n: Conecte fácilmente su Jeedom a su instancia de n8n a través de su URL y una clave API.
  - Gestión de flujos de trabajo: Cree equipos de Jeedom para cada flujo de trabajo de n8n que desee controlar.
  - Comandos de acción:
    - Activar/Desactivar: Cambie el estado de ejecución de sus flujos de trabajo de n8n.
    - Lanzar (a través de Webhook): Active un flujo de trabajo de n8n enviando una solicitud a su URL de webhook configurada.
  - Comando de información:
    - Estado: Obtenga el estado (activo/inactivo) de su flujo de trabajo de n8n.
  - Notificaciones de error de flujo de trabajo: Reciba notificaciones en Jeedom cuando un flujo de trabajo de n8n falle.
  - Selección simplificada: Elija sus flujos de trabajo de n8n a través de una lista desplegable o ingrese manualmente su ID.
  - Registro detallado: Registros precisos para facilitar el diagnóstico en caso de problemas.

## 0.1.1
- Descripción italiana corregida en info.json.
- Corregido error de sintaxis JSON en info.json (coma extra).
