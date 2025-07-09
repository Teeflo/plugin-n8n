# n8n Connect für Jeedom

Dieses Plugin ermöglicht es Ihnen, Ihre **n8n**-Workflows direkt über Ihre Jeedom-Hausautomatisierungsoberfläche zu steuern und zu überwachen. Es bietet eine einfache und effektive Integration, um Workflows zu starten, zu aktivieren/deaktivieren und ihren Status zu überprüfen.

## Funktionen

*   **n8n-Instanzkonfiguration:** Verbinden Sie Ihr Jeedom einfach mit Ihrer n8n-Instanz über deren URL und einen API-Schlüssel.
*   **Workflow-Verwaltung:** Erstellen Sie Jeedom-Geräte für jeden n8n-Workflow, den Sie steuern möchten.
*   **Aktionsbefehle:**
    *   **Aktivieren/Deaktivieren:** Ändern Sie den Ausführungsstatus Ihrer n8n-Workflows.
    *   **Starten (über Webhook):** Lösen Sie einen n8n-Workflow aus, indem Sie eine Anfrage an seine konfigurierte Webhook-URL senden. Dieser Befehl wird nur angezeigt, wenn ein Webhook für das Gerät konfiguriert ist.
*   **Informationsbefehl:**
    *   **Status:** Erhalten Sie den Status (aktiv/inaktiv) Ihres n8n-Workflows.
*   **Workflow-Fehlerbenachrichtigungen:** Erhalten Sie Benachrichtigungen in Jeedom, wenn ein n8n-Workflow fehlschlägt, was eine proaktive Problembehandlung ermöglicht.
*   **Vereinfachte Auswahl:** Wählen Sie Ihre n8n-Workflows über eine Dropdown-Liste aus oder geben Sie deren ID manuell ein.
*   **Detaillierte Protokollierung:** Präzise Protokolle zur Erleichterung der Diagnose bei Problemen.

## Voraussetzungen

1.  Eine funktionierende und von Ihrem Jeedom aus zugängliche n8n-Instanz.
2.  Die n8n REST API muss auf Ihrer Instanz aktiviert sein.
3.  Ein gültiger n8n API-Schlüssel mit den erforderlichen Berechtigungen zur Verwaltung von Workflows.
4.  Jeedom Version 4.2.0 oder höher.
5.  Die PHP-Erweiterung `cURL` muss auf Ihrem Jeedom-System installiert und aktiviert sein.

## Installation

1.  Installieren Sie das Plugin "n8n Connect" direkt über den Jeedom Market.
2.  Nach der Installation aktivieren Sie das Plugin unter **Plugins > Plugin-Verwaltung**.

## Konfiguration

### 1. Globale Plugin-Konfiguration

Greifen Sie auf die globale Plugin-Konfiguration zu über **Plugins > Plugin-Verwaltung > n8n Connect > Konfiguration**.

*   **n8n-Instanz-URL:** Geben Sie die vollständige Adresse Ihrer n8n-Instanz ein (z. B. `https://mein.n8n.local` oder `http://192.168.1.100:5678`).
*   **API-Schlüssel:** Geben Sie Ihren n8n API-Schlüssel ein, der in n8n generiert wurde (**Einstellungen > API**).
*   Klicken Sie auf die Schaltfläche **"Testen"**, um die Verbindung zu Ihrer n8n-Instanz zu überprüfen.

### 2. Geräte (Workflows) Konfiguration

Für jeden n8n-Workflow, den Sie steuern möchten:

1.  Gehen Sie zu **Plugins > n8n Connect**.
2.  Klicken Sie auf **"Hinzufügen"**, um ein neues Gerät zu erstellen.
3.  **Gerätename:** Geben Sie Ihrem Jeedom-Gerät einen aussagekräftigen Namen (z. B. "Wohnzimmerbeleuchtung Workflow").
4.  **Workflow:**
    *   Klicken Sie auf die Schaltfläche "Aktualisieren" (<i class="fas fa-sync"></i>), um die Liste Ihrer verfügbaren n8n-Workflows zu laden.
    *   Wählen Sie den gewünschten Workflow aus der Dropdown-Liste aus.
    *   Wenn die Liste nicht geladen wird (z. B. aufgrund eines API-Verbindungsproblems), wird ein Feld zur manuellen Eingabe der Workflow-ID angezeigt. Sie finden Ihre Workflow-ID in der n8n-Oberfläche.
5.  **Webhook-URL (Optional):** Wenn Sie diesen Workflow über einen "Starten"-Befehl auslösen möchten, fügen Sie hier die Webhook-URL Ihres n8n-Workflows ein. Diese URL wird vom "Webhook"-Knoten Ihres n8n-Workflows bereitgestellt.
6.  Konfigurieren Sie die **Allgemeinen Parameter** (Übergeordnetes Objekt, Kategorie, Aktivieren/Sichtbar) nach Bedarf.
7.  Klicken Sie auf **"Speichern"**. Die Befehle "Aktivieren", "Deaktivieren" und "Status" werden automatisch erstellt. Der Befehl "Starten" wird hinzugefügt, wenn eine Webhook-URL angegeben wurde.

## Verfügbare Befehle

Sobald das Gerät konfiguriert ist, stehen die folgenden Befehle zur Verfügung:

*   **Aktivieren:** Aktiviert den entsprechenden Workflow in n8n.
*   **Deaktivieren:** Deaktiviert den entsprechenden Workflow in n8n.
*   **Starten:** Sendet eine HTTP-POST-Anfrage an die für den Workflow konfigurierte Webhook-URL. Dieser Befehl ist nur sichtbar, wenn eine "Webhook-URL" in der Gerätekonfiguration angegeben ist.
*   **Status:** Ein binärer Informationsbefehl, der angibt, ob der Workflow in n8n aktiv (1) oder inaktiv (0) ist.

## Fehlerbehebung

### HTTP 401 "unauthorized" Fehler

Dieser Fehler weist auf ein Authentifizierungsproblem beim Versuch hin, eine Verbindung zur n8n-API herzustellen.

*   **Überprüfen Sie Ihre Konfiguration:** Stellen Sie sicher, dass die **n8n-Instanz-URL** und der **API-Schlüssel** in der globalen Plugin-Konfiguration korrekt eingegeben wurden.
*   **Testen Sie die Verbindung:** Verwenden Sie die Schaltfläche **"Testen"** in der globalen Konfiguration, um Ihre Anmeldeinformationen zu überprüfen.
*   **Überprüfen Sie n8n:**
    *   Stellen Sie sicher, dass die REST-API in n8n aktiviert ist (**Einstellungen > API**).
    *   Überprüfen Sie, ob Ihr n8n API-Schlüssel gültig und nicht abgelaufen ist und über die erforderlichen Berechtigungen verfügt.
    *   Stellen Sie sicher, dass Ihre n8n-Instanz gestartet und von Jeedom aus zugänglich ist.
*   **Netzwerkkonnektivität:** Überprüfen Sie Firewalls oder Netzwerkprobleme, die Jeedom daran hindern könnten, mit n8n zu kommunizieren.

### Häufige Fehlermeldungen

*   **"Webhook-URL fehlt":** Der Befehl "Starten" wurde ausgeführt, aber für dieses Gerät ist keine Webhook-URL konfiguriert.
*   **"Webhook-Fehler: Der angeforderte Webhook ... ist nicht registriert":** Der Workflow ist in n8n nicht aktiv, oder die Webhook-URL ist falsch. Stellen Sie sicher, dass der Workflow in n8n aktiviert ist und die URL korrekt ist.
*   **"Zeitüberschreitung":** Jeedom konnte Ihre n8n-Instanz innerhalb der vorgegebenen Zeit nicht erreichen. Stellen Sie sicher, dass n8n online und zugänglich ist.
*   **"Ungültige n8n API-Antwort":** Die n8n-API hat eine unerwartete Antwort zurückgegeben.

### Diagnoseprotokolle

Weitere detaillierte Informationen finden Sie in den Plugin-Protokollen:
1.  Gehen Sie zu **Werkzeuge > Protokolle**.
2.  Wählen Sie das Plugin **n8nconnect** aus.
3.  Suchen Sie nach aktuellen Fehlermeldungen, um die Ursache des Problems zu identifizieren.

## n8n-Fehlerbenachrichtigungen an Jeedom

Um n8n-Workflow-Fehlerbenachrichtigungen direkt in Jeedom zu erhalten, können Sie einen globalen "Fehler-Workflow" in n8n konfigurieren, der eine HTTP-Anfrage an Jeedom sendet.

### Konfiguration in n8n

1.  **Erstellen Sie einen neuen Workflow** in n8n (oder verwenden Sie einen vorhandenen Workflow, der Fehlern gewidmet ist).
2.  Fügen Sie einen **"Webhook"**-Knoten als Auslöser hinzu. Konfigurieren Sie ihn so, dass er auf `POST`-Anfragen lauscht.
3.  Fügen Sie einen **"HTTP-Anfrage"**-Knoten nach dem "Webhook"-Knoten hinzu.
    *   **Methode:** `POST`
    *   **URL:** `http://IHRE_JEEDOM_IP/plugins/n8nconnect/core/ajax/n8nconnect.ajax.php?action=receiveErrorNotification`
        *   Ersetzen Sie `IHRE_JEEDOM_IP` durch die IP-Adresse oder den Domainnamen Ihrer Jeedom-Installation.
    *   **Body Content Type:** `JSON`
    *   **JSON Body:** Sie können beliebige relevante JSON-Daten senden. Um beispielsweise Fehlerinformationen aus dem fehlgeschlagenen Workflow zu senden, können Sie einen Ausdruck wie diesen verwenden:
        ```json
        {
          "workflowName": "{{ $json.workflow.name }}",
          "workflowId": "{{ $json.workflow.id }}",
          "executionId": "{{ $json.id }}",
          "error": "{{ $json.error.message }}",
          "stackTrace": "{{ $json.error.stack }}"
        }
        ```
        Diese Variablen (`$json.workflow.name`, etc.) sind im Kontext eines n8n-Fehler-Workflows verfügbar.
4.  **Aktivieren Sie diesen Workflow** in n8n.
5.  **Konfigurieren Sie diesen Workflow als globalen "Fehler-Workflow":**
    *   Gehen Sie in n8n zu **Einstellungen > Workflow-Fehlerbehandlung**.
    *   Wählen Sie den soeben erstellten Workflow aus der Dropdown-Liste "Fehler-Workflow" aus.

### Verarbeitung in Jeedom

Das n8n Connect-Plugin empfängt diese Benachrichtigungen und speichert sie in den Plugin-Protokollen (`Werkzeuge > Protokolle > n8nconnect`). Sie können dann Jeedom-Szenarien verwenden, um diese Protokolle zu analysieren und Aktionen (Benachrichtigungen, Warnungen usw.) basierend auf dem Inhalt der Fehlermeldungen auszulösen.