# Configuración y solución de problemas del plugin n8n Connect

Este documento proporciona una guía detallada para configurar el plugin n8n Connect en Jeedom, así como soluciones a problemas comunes que pueda encontrar.

## Tabla de contenidos

1.  [Requisitos previos](#1-requisitos-previos)
2.  [Instalación del plugin](#2-instalación-del-plugin)
3.  [Configuración global del plugin](#3-configuración-global-del-plugin)
    *   [Acceso a la configuración](#acceso-a-la-configuración)
    *   [Parámetros de conexión de n8n](#parámetros-de-conexión-de-n8n)
    *   [Prueba de conexión](#prueba-de-conexión)
4.  [Configuración de equipos (flujos de trabajo)](#4-configuración-de-equipos-flujos-de-trabajo)
    *   [Creación de un nuevo equipo](#creación-de-un-nuevo-equipo)
    *   [Parámetros generales del equipo](#parámetros-generales-del-equipo)
    *   [Parámetros específicos del flujo de trabajo](#parámetros-específicos-del-flujo-de-trabajo)
    *   [Guardar equipo](#guardar-equipo)
5.  [Comandos disponibles](#5-comandos-disponibles)
    *   [Comandos de acción](#comandos-de-acción)
    *   [Comandos de información](#comandos-de-información)
6.  [Solución de problemas y errores comunes](#6-solución-de-problemas-y-errores-comunes)
    *   [Error HTTP 401 "no autorizado"](#error-http-401-no-autorizado)
    *   ["Falta la URL del webhook"](#falta-la-url-del-webhook)
    *   ["Error de webhook: El webhook solicitado ... no está registrado"](#error-de-webhook-el-webhook-solicitado--no-está-registrado)
    *   ["Tiempo de espera agotado"](#tiempo-de-espera-agotado)
    *   ["Respuesta de la API de n8n no válida"](#respuesta-de-la-api-de-n8n-no-válida)
    *   [Registros de diagnóstico](#registros-de-diagnóstico)
7.  [Soporte](#7-soporte)

---

## 1. Requisitos previos

Antes de comenzar la configuración, asegúrese de que los siguientes elementos estén en su lugar:

*   **Instancia de n8n:** Una instancia de n8n funcional y accesible desde su instalación de Jeedom. Puede ser una instancia local, en una red privada o una instancia en la nube.
*   **API REST de n8n habilitada:** La API REST debe estar habilitada en la configuración de su instancia de n8n. Normalmente la encontrará en `Configuración > API`.
*   **Clave API de n8n:** Una clave API válida generada en n8n. Esta clave debe tener los permisos necesarios para:
    *   Listar flujos de trabajo.
    *   Activar/desactivar flujos de trabajo.
    *   (Opcional) Ejecutar flujos de trabajo a través de la API si utiliza este método (aunque el plugin prefiere los webhooks para el lanzamiento).
*   **Jeedom:** Una instalación de Jeedom versión 4.2.0 o superior.
*   **Extensión PHP cURL:** La extensión PHP `cURL` es esencial para que el plugin se comunique con la API de n8n. Asegúrese de que esté instalada y habilitada en su sistema Jeedom.

## 2. Instalación del plugin

1.  **A través del mercado de Jeedom:** Acceda a su interfaz de Jeedom, luego vaya a `Plugins > Gestión de plugins > Mercado`. Busque "n8n Connect" e instálelo.
2.  **Activación:** Una vez completada la instalación, el plugin aparecerá en su lista de plugins. Haga clic en el botón `Activar` (normalmente un icono de marca de verificación verde) para que funcione.

## 3. Configuración global del plugin

Este paso establece la conexión entre su Jeedom y su instancia de n8n.

### Acceso a la configuración

*   En Jeedom, navegue hasta `Plugins > Gestión de plugins`.
*   Localice "n8n Connect" en la lista y haga clic en su icono (normalmente una llave inglesa) o en el botón `Configuración`.

### Parámetros de conexión de n8n

En la página de configuración, encontrará los siguientes campos:

*   **URL de la instancia de n8n:**
    *   Introduzca la dirección completa de su instancia de n8n.
    *   **Ejemplos:**
        *   `https://mi.n8n.local` (para una instancia con SSL/TLS)
        *   `http://192.168.1.100:5678` (para una instancia local sin SSL/TLS, con el puerto predeterminado)
    *   Asegúrese de que la URL sea accesible desde el servidor de Jeedom.
*   **Clave API:**
    *   Copie y pegue la clave API que generó en su instancia de n8n (en `Configuración > API`).
    *   **Advertencia:** Nunca comparta esta clave. Otorga acceso a su instancia de n8n.

### Prueba de conexión

*   Después de introducir la URL y la clave API, haga clic en el botón **"Probar"**.
*   Jeedom intentará conectarse a su instancia de n8n y recuperar una lista de flujos de trabajo para verificar la validez de la información proporcionada.
*   Se mostrará un mensaje de éxito o error, indicando si la conexión se ha establecido correctamente.

## 4. Configuración de equipos (flujos de trabajo)

Cada equipo de Jeedom representa un flujo de trabajo de n8n específico que desea controlar.

### Creación de un nuevo equipo

1.  En Jeedom, vaya a `Plugins > n8n Connect`.
2.  Haga clic en el botón **"Agregar"** para crear un nuevo equipo.

### Parámetros generales del equipo

*   **Nombre del equipo:** Asigne un nombre claro y descriptivo a su equipo de Jeedom (ej: "Flujo de trabajo de riego del jardín", "Flujo de trabajo de notificaciones").
*   **Objeto padre:** Asocie el equipo con un objeto de Jeedom existente (ej: "Jardín", "Casa").
*   **Categoría:** Asigne una o más categorías al equipo (ej: "Luz", "Seguridad").
*   **Opciones:**
    *   **Activar:** Marque esta casilla para activar el equipo en Jeedom.
    *   **Visible:** Marque esta casilla para hacer que el equipo sea visible en el panel de control de Jeedom.

### Parámetros específicos del flujo de trabajo

*   **Flujo de trabajo:**
    *   Haga clic en el botón de actualización (<i class="fas fa-sync"></i>) junto al campo para cargar la lista de todos los flujos de trabajo disponibles en su instancia de n8n.
    *   Seleccione el flujo de trabajo de n8n deseado que este equipo debe controlar de la lista desplegable.
    *   **Caso de error:** Si la lista no se carga (por ejemplo, debido a un problema de conexión a la API o si no se encuentran flujos de trabajo), aparecerá un campo de entrada manual del ID del flujo de trabajo. Puede encontrar el ID de su flujo de trabajo en la URL del editor de n8n (ej: `https://su.instancia.n8n/workflow/SU_ID_DE_FLUJO_DE_TRABAJO`).
*   **URL del Webhook (Opcional):**
    *   Si desea poder activar este flujo de trabajo de n8n a través del comando "Lanzar" desde Jeedom, debe introducir su URL de webhook.
    *   Esta URL es generada por el nodo "Webhook" de su flujo de trabajo de n8n. Copie la URL completa (ej: `https://su.instancia.n8n/webhook/su-ruta-única`).
    *   **Importante:** Si este campo está vacío, el comando "Lanzar" no estará disponible para este equipo.
*   **Actualización automática:** (Si está disponible) Permite definir la frecuencia con la que Jeedom debe actualizar el estado del flujo de trabajo (activo/inactivo) desde n8n. Utilice el asistente cron para definir una programación.

### Guardar equipo

*   Una vez configurados todos los parámetros, haga clic en el botón **"Guardar"** en la parte superior de la página.
*   Jeedom guardará el equipo y creará automáticamente los comandos asociados (Activar, Desactivar, Estado y Lanzar si el webhook está configurado).

## 5. Comandos disponibles

Después de guardar el equipo, los siguientes comandos estarán accesibles:

### Comandos de acción

*   **Activar:** Envía una solicitud a n8n para activar el flujo de trabajo asociado a este equipo. El flujo de trabajo comenzará a ejecutarse según su configuración (por ejemplo, en un disparador).
*   **Desactivar:** Envía una solicitud a n8n para desactivar el flujo de trabajo. El flujo de trabajo dejará de ejecutarse y ya no responderá a sus disparadores.
*   **Lanzar:** (Visible solo si se configura una "URL del Webhook" para el equipo). Envía una solicitud HTTP `POST` a la URL del webhook especificada. Esto activará la ejecución del flujo de trabajo de n8n como si el webhook hubiera sido llamado externamente.

### Comandos de información

*   **Estado:** Un comando de información binario (`0` o `1`) que indica el estado actual del flujo de trabajo en n8n:
    *   `1` (Activo): El flujo de trabajo está activado y listo para ejecutarse.
    *   `0` (Inactivo): El flujo de trabajo está desactivado.
    *   Esta información se actualiza durante la actualización automática o después de una acción de activación/desactivación.

## 6. Solución de problemas y errores comunes

Aquí están los problemas más frecuentes y cómo resolverlos.

### Error HTTP 401 "no autorizado"

**Descripción:** Este error indica un problema de autenticación al intentar conectarse a la API de n8n.

**Posibles causas:**
*   Clave API faltante, incorrecta o caducada.
*   La API REST no está habilitada en n8n.
*   La URL de la instancia de n8n es incorrecta o inaccesible.
*   Problema de permisos de la clave API.

**Soluciones:**
1.  **Verifique la configuración global de su plugin:** Asegúrese de que la **URL de la instancia de n8n** y la **Clave API** estén correctamente introducidas en `Plugins > Gestión de plugins > n8n Connect > Configuración`.
2.  **Pruebe la conexión:** Utilice el botón **"Probar"** en esta misma página para validar sus credenciales y la accesibilidad de la instancia.
3.  **Verifique n8n:**
    *   En su instancia de n8n, vaya a `Configuración > API` y asegúrese de que la API REST esté habilitada.
    *   Verifique que la clave API que está utilizando sea realmente la generada aquí, que no haya caducado y que tenga los permisos necesarios (al menos `workflows.read`, `workflows.write`, `workflows.activate`, `workflows.deactivate`).
    *   Asegúrese de que su instancia de n8n esté iniciada y funcionando correctamente.
4.  **Conectividad de red:** Verifique los cortafuegos o problemas de enrutamiento de red que puedan impedir que Jeedom se comunique con n8n en el puerto especificado.

### "Falta la URL del webhook"

**Descripción:** Este mensaje aparece cuando intenta ejecutar el comando "Lanzar" para un equipo, pero el campo "URL del Webhook" está vacío en su configuración.

**Solución:**
*   Edite el equipo afectado (`Plugins > n8n Connect`, haga clic en el equipo).
*   En los parámetros específicos, introduzca la URL completa del webhook de su flujo de trabajo de n8n en el campo **"URL del Webhook"**.
*   Guarde el equipo. El comando "Lanzar" debería funcionar ahora.

### "Error de webhook: El webhook solicitado ... no está registrado"

**Descripción:** n8n indica que no puede encontrar el webhook correspondiente a la URL o que el flujo de trabajo no está activo.

**Posibles causas:**
*   El flujo de trabajo no está activado en n8n. Los webhooks de producción solo funcionan si el flujo de trabajo está activo.
*   La URL del webhook introducida en Jeedom es incorrecta (error tipográfico, ID de webhook incorrecto, etc.).
*   El nodo "Webhook" en su flujo de trabajo de n8n no está configurado para aceptar solicitudes `POST` (aunque este es el comportamiento predeterminado).

**Soluciones:**
1.  **Active el flujo de trabajo en n8n:** Abra su flujo de trabajo en n8n y asegúrese de que el botón `Activo` (arriba a la derecha del editor) esté configurado en `ON`.
2.  **Verifique la URL del webhook:** Copie la URL del webhook directamente desde el nodo "Webhook" de su flujo de trabajo de n8n y péguela de nuevo en el campo "URL del Webhook" del equipo de Jeedom para evitar errores.
3.  **Método HTTP:** El plugin envía una solicitud `POST`. Asegúrese de que su nodo "Webhook" en n8n esté configurado para aceptar solicitudes `POST` (este es el valor predeterminado para los webhooks de producción).

### "Tiempo de espera agotado"

**Descripción:** Jeedom no recibió una respuesta de n8n dentro del tiempo asignado (30 segundos por defecto).

**Posibles causas:**
*   Su instancia de n8n está detenida o no responde.
*   Problema de conectividad de red entre Jeedom y n8n (cortafuegos, enrutador, etc.).
*   La instancia de n8n está sobrecargada o responde muy lentamente.

**Soluciones:**
1.  **Verifique el estado de n8n:** Asegúrese de que su instancia de n8n esté en ejecución y sea accesible a través de un navegador o un `ping` desde el servidor de Jeedom.
2.  **Verifique la conectividad:** Pruebe la conexión de red entre su Jeedom y n8n. Por ejemplo, desde su terminal de Jeedom, intente `curl -v SU_URL_N8N`.
3.  **Rendimiento de n8n:** Si n8n está sobrecargado, considere optimizar sus flujos de trabajo o aumentar los recursos asignados a su instancia de n8n.

### Registros de diagnóstico

Para obtener información más detallada sobre los errores, consulte los registros del plugin n8n Connect:

1.  En Jeedom, vaya a `Herramientas > Registros`.
2.  En la lista desplegable, seleccione `n8nconnect`.
3.  Los registros muestran las comunicaciones entre Jeedom y n8n, incluidas las solicitudes enviadas y las respuestas recibidas, lo cual es crucial para la resolución de problemas.

## Notificaciones de error de flujo de trabajo

Para recibir notificaciones de error de sus flujos de trabajo de n8n directamente en Jeedom, puede configurar un "Flujo de trabajo de error" global en n8n que enviará una solicitud HTTP a Jeedom.

### Configuración en n8n

1.  **Cree un nuevo flujo de trabajo** en n8n (o utilice un flujo de trabajo existente dedicado a errores).
2.  Agregue un nodo **"Webhook"** como disparador. Configúrelo para escuchar las solicitudes `POST`.
3.  Agregue un nodo **"Solicitud HTTP"** después del nodo "Webhook".
    *   **Método:** `POST`
    *   **URL:** `http://SU_IP_JEEDOM/plugins/n8nconnect/core/ajax/n8nconnect.ajax.php?action=receiveErrorNotification`
        *   Reemplace `SU_IP_JEEDOM` con la dirección IP o el nombre de dominio de su instalación de Jeedom.
    *   **Tipo de contenido del cuerpo:** `JSON`
    *   **Cuerpo JSON:** Puede enviar cualquier dato JSON relevante. Por ejemplo, para enviar información de error del flujo de trabajo que falló, puede utilizar una expresión como:
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

El plugin n8n Connect recibirá estas notificaciones y las registrará en los registros del plugin (`Herramientas > Registros > n8nconnect`). Luego, puede utilizar los escenarios de Jeedom para analizar estos registros y activar acciones (notificaciones, alertas, etc.) según el contenido de los mensajes de error.

## 7. Soporte

Si encuentra problemas persistentes después de seguir esta guía, recopile la siguiente información antes de pedir ayuda:

*   Versión exacta de Jeedom (visible en `Configuración > Sistema > Configuración > General`).
*   Versión de su instancia de n8n.
*   Mensajes de error completos y exactos, copiados directamente de los registros de Jeedom de `n8nconnect`.
*   Captura de pantalla de la página de configuración global del plugin (oculte su clave API).
*   Captura de pantalla de la página de configuración del equipo de Jeedom afectado.
*   Descripción detallada de los pasos para reproducir el problema.