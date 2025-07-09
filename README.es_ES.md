# n8n Connect para Jeedom

Este plugin le permite controlar y supervisar sus flujos de trabajo de **n8n** directamente desde su interfaz de domótica Jeedom. Ofrece una integración simple y efectiva para lanzar flujos de trabajo, activarlos/desactivarlos y verificar su estado.

## Características

*   **Configuración de instancia de n8n:** Conecte fácilmente su Jeedom a su instancia de n8n a través de su URL y una clave API.
*   **Gestión de flujos de trabajo:** Cree equipos de Jeedom para cada flujo de trabajo de n8n que desee controlar.
*   **Comandos de acción:**
    *   **Activar/Desactivar:** Cambie el estado de ejecución de sus flujos de trabajo de n8n.
    *   **Lanzar (a través de Webhook):** Active un flujo de trabajo de n8n enviando una solicitud a su URL de webhook configurada. Este comando solo aparece si se configura un webhook para el equipo.
*   **Comando de información:**
    *   **Estado:** Obtenga el estado (activo/inactivo) de su flujo de trabajo de n8n.
*   **Notificaciones de error de flujo de trabajo:** Reciba notificaciones en Jeedom cuando un flujo de trabajo de n8n falle, lo que permite una gestión proactiva de los problemas.
*   **Selección simplificada:** Elija sus flujos de trabajo de n8n a través de una lista desplegable o ingrese manualmente su ID.
*   **Registro detallado:** Registros precisos para facilitar el diagnóstico en caso de problemas.

## Requisitos previos

1.  Una instancia de [n8n](https://n8n.io/) funcional y accesible desde su Jeedom.
2.  La API REST de n8n debe estar habilitada en su instancia.
3.  Una clave API de n8n válida con los permisos necesarios para administrar flujos de trabajo.
4.  Jeedom versión 4.2.0 o superior.
5.  La extensión PHP `cURL` debe estar instalada y habilitada en su sistema Jeedom.

## Instalación

1.  Instale el plugin "n8n Connect" directamente desde el Market de Jeedom.
2.  Después de la instalación, active el plugin en **Plugins > Gestión de plugins**.

## Configuración

### 1. Configuración global del plugin

Acceda a la configuración global del plugin a través de **Plugins > Gestión de plugins > n8n Connect > Configuración**.

*   **URL de la instancia de n8n:** Ingrese la dirección completa de su instancia de n8n (ej: `https://mi.n8n.local` o `http://192.168.1.100:5678`).
*   **Clave API:** Ingrese su clave API de n8n, generada en n8n (**Configuración > API**).
*   Haga clic en el botón **"Probar"** para verificar la conexión a su instancia de n8n.

### 2. Configuración de equipos (flujos de trabajo)

Para cada flujo de trabajo de n8n que desee controlar:

1.  Vaya a **Plugins > n8n Connect**.
2.  Haga clic en **"Agregar"** para crear un nuevo equipo.
3.  **Nombre del equipo:** Asigne un nombre significativo a su equipo de Jeedom (ej: "Flujo de trabajo Luces de la sala").
4.  **Flujo de trabajo:**
    *   Haga clic en el botón de actualización (<i class="fas fa-sync"></i>) para cargar la lista de sus flujos de trabajo de n8n disponibles.
    *   Seleccione el flujo de trabajo deseado de la lista desplegable.
    *   Si la lista no se carga (por ejemplo, debido a un problema de conexión API), aparecerá un campo de entrada manual del ID del flujo de trabajo. Puede encontrar el ID de su flujo de trabajo en la interfaz de n8n.
5.  **URL del Webhook (Opcional):** Si desea activar este flujo de trabajo a través de un comando "Lanzar", pegue aquí la URL del webhook de su flujo de trabajo de n8n. Esta URL es proporcionada por el nodo "Webhook" de su flujo de trabajo de n8n.
6.  Configure los **Parámetros generales** (Objeto padre, Categoría, Activar/Visible) según sus necesidades.
7.  Haga clic en **"Guardar"**. Los comandos "Activar", "Desactivar" y "Estado" se crearán automáticamente. El comando "Lanzar" se agregará si se ha proporcionado una URL de webhook.

## Comandos disponibles

Una vez configurado el equipo, los siguientes comandos estarán disponibles:

*   **Activar:** Activa el flujo de trabajo correspondiente en n8n.
*   **Desactivar:** Desactiva el flujo de trabajo correspondiente en n8n.
*   **Lanzar:** Envía una solicitud HTTP POST a la URL del webhook configurada para el flujo de trabajo. Este comando solo es visible si se proporciona una "URL del Webhook" en la configuración del equipo.
*   **Estado:** Un comando de información binario que indica si el flujo de trabajo está activo (1) o inactivo (0) en n8n.

## Solución de problemas

### Error HTTP 401 "unauthorized"

Este error indica un problema de autenticación al intentar conectarse a la API de n8n.

*   **Verifique su configuración:** Asegúrese de que la **URL de la instancia de n8n** y la **Clave API** estén correctamente ingresadas en la configuración global del plugin.
*   **Pruebe la conexión:** Use el botón **"Probar"** en esta misma página para validar sus credenciales.
*   **Verifique n8n:**
    *   Asegúrese de que la API REST esté habilitada en n8n (**Configuración > API**).
    *   Verifique que su clave API de n8n sea válida y no haya caducado, y que tenga los permisos necesarios.
    *   Asegúrese de que su instancia de n8n esté iniciada y accesible desde Jeedom.
*   **Conectividad de red:** Verifique los firewalls o problemas de red que puedan impedir que Jeedom se comunique con n8n.

### Mensajes de error comunes

*   **"Falta la URL del webhook":** El comando "Lanzar" se ejecutó, pero no hay ninguna URL de webhook configurada para este equipo.
*   **"Error de webhook: El webhook solicitado ... no está registrado":** El flujo de trabajo no está activo en n8n, o la URL del webhook es incorrecta. Asegúrese de que el flujo de trabajo esté activado en n8n y que la URL sea exacta.
*   **"Tiempo de espera agotado":** Jeedom no pudo comunicarse con su instancia de n8n dentro del tiempo asignado. Asegúrese de que n8n esté en línea y accesible.
*   **"Respuesta de la API de n8n no válida":** La API de n8n devolvió una respuesta inesperada.

### Registros de diagnóstico

Para obtener información más detallada, consulte los registros del plugin:
1.  Vaya a **Herramientas > Registros**.
2.  Seleccione el plugin **n8nconnect**.
3.  Busque los mensajes de error recientes para identificar la causa del problema.

## Notificaciones de error de n8n a Jeedom

Para recibir notificaciones de error de sus flujos de trabajo de n8n directamente en Jeedom, puede configurar un "Flujo de trabajo de error" global en n8n que enviará una solicitud HTTP a Jeedom.

### Configuración en n8n

1.  **Cree un nuevo flujo de trabajo** en n8n (o use un flujo de trabajo existente dedicado a errores).
2.  Agregue un nodo **"Webhook"** como disparador. Configúrelo para escuchar las solicitudes `POST`.
3.  Agregue un nodo **"Solicitud HTTP"** después del nodo "Webhook".
    *   **Método:** `POST`
    *   **URL:** `http://SU_IP_JEEDOM/plugins/n8nconnect/core/ajax/n8nconnect.ajax.php?action=receiveErrorNotification`
        *   Reemplace `SU_IP_JEEDOM` con la dirección IP o el nombre de dominio de su instalación de Jeedom.
    *   **Tipo de contenido del cuerpo:** `JSON`
    *   **Cuerpo JSON:** Puede enviar cualquier dato JSON relevante. Por ejemplo, para enviar información de error del flujo de trabajo que falló, puede usar una expresión como:
        ```json
        {
          "workflowName": "{{ $json.workflow.name }}",
          "workflowId": "{{ $json.workflow.id }}",
          "executionId": "{{ $json.id }}",
          "error": "{{ $json.error.message }}",
          "stackTrace": "{{ $json.error.stack }}"
        }
        ```
        Estas variables (`$json.workflow.name`, etc.) están disponibles en el contexto de un flujo de trabajo de error de n8n.
4.  **Active este flujo de trabajo** en n8n.
5.  **Configure este flujo de trabajo como un "Flujo de trabajo de error" global:**
    *   En n8n, vaya a **Configuración > Manejo de errores de flujo de trabajo**.
    *   Seleccione el flujo de trabajo que acaba de crear de la lista desplegable "Flujo de trabajo de error".

### Procesamiento en Jeedom

El plugin n8n Connect recibirá estas notificaciones y las registrará en los registros del plugin (`Herramientas > Registros > n8nconnect`). Luego, puede usar los escenarios de Jeedom para analizar estos registros y activar acciones (notificaciones, alertas, etc.) según el contenido de los mensajes de error.