# n8n Connect Plugin Konfiguration und Fehlerbehebung

Dieses Dokument bietet eine detaillierte Anleitung zur Konfiguration des n8n Connect Plugins in Jeedom sowie Lösungen für häufig auftretende Probleme.

## Inhaltsverzeichnis

1.  [Voraussetzungen](#1-voraussetzungen)
2.  [Plugin-Installation](#2-plugin-installation)
3.  [Globale Plugin-Konfiguration](#3-globale-plugin-konfiguration)
    *   [Zugriff auf die Konfiguration](#zugriff-auf-die-konfiguration)
    *   [n8n-Verbindungsparameter](#n8n-verbindungsparameter)
    *   [Verbindungstest](#verbindungstest)
4.  [Geräte (Workflows) Konfiguration](#4-geräte-workflows-konfiguration)
    *   [Neues Gerät erstellen](#neues-gerät-erstellen)
    *   [Allgemeine Geräteparameter](#allgemeine-geräteparameter)
    *   [Spezifische Workflow-Parameter](#spezifische-workflow-parameter)
    *   [Gerät speichern](#gerät-speichern)
5.  [Verfügbare Befehle](#5-verfügbare-befehle)
    *   [Aktionsbefehle](#aktionsbefehle)
    *   [Informationsbefehle](#informationsbefehle)
6.  [Fehlerbehebung und häufige Fehler](#6-fehlerbehebung-und-häufige-fehler)
    *   [HTTP 401 "unauthorized" Fehler](#http-401-unauthorized-fehler)
    *   ["Webhook-URL fehlt"](#webhook-url-fehlt)
    *   ["Webhook-Fehler: Der angeforderte Webhook ... ist nicht registriert"](#webhook-fehler-der-angeforderte-webhook--ist-nicht-registriert)
    *   ["Zeitüberschreitung"](#zeitüberschreitung)
    *   ["Ungültige n8n API-Antwort"](#ungültige-n8n-api-antwort)
    *   [Diagnoseprotokolle](#diagnoseprotokolle)
7.  [Support](#7-support)

---

## 1. Voraussetzungen

Bevor Sie mit der Konfiguration beginnen, stellen Sie sicher, dass die folgenden Elemente vorhanden sind:

*   **n8n-Instanz:** Eine funktionierende n8n-Instanz, die von Ihrer Jeedom-Installation aus zugänglich ist. Dies kann eine lokale Instanz, in einem privaten Netzwerk oder eine Cloud-Instanz sein.
*   **n8n REST API aktiviert:** Die REST API muss in den Einstellungen Ihrer n8n-Instanz aktiviert sein. Sie finden sie normalerweise unter `Einstellungen > API`.
*   **n8n API-Schlüssel:** Ein gültiger API-Schlüssel, der in n8n generiert wurde. Dieser Schlüssel muss die erforderlichen Berechtigungen haben, um:
    *   Workflows aufzulisten.
    *   Workflows zu aktivieren/deaktivieren.
    *   (Optional) Workflows über die API auszuführen, wenn Sie diese Methode verwenden (obwohl das Plugin Webhooks für den Start bevorzugt).
*   **Jeedom:** Eine Jeedom-Installation Version 4.2.0 oder höher.
*   **PHP cURL-Erweiterung:** Die PHP `cURL`-Erweiterung ist für die Kommunikation des Plugins mit der n8n-API unerlässlich. Stellen Sie sicher, dass sie auf Ihrem Jeedom-System installiert und aktiviert ist.

## 2. Plugin-Installation

1.  **Über den Jeedom Market:** Greifen Sie auf Ihre Jeedom-Oberfläche zu und gehen Sie dann zu `Plugins > Plugin-Verwaltung > Market`. Suchen Sie nach "n8n Connect" und installieren Sie es.
2.  **Aktivierung:** Sobald die Installation abgeschlossen ist, wird das Plugin in Ihrer Plugin-Liste angezeigt. Klicken Sie auf die Schaltfläche `Aktivieren` (normalerweise ein grünes Häkchen-Symbol), um es betriebsbereit zu machen.

## 3. Globale Plugin-Konfiguration

Dieser Schritt stellt die Verbindung zwischen Ihrem Jeedom und Ihrer n8n-Instanz her.

### Zugriff auf die Konfiguration

*   Navigieren Sie in Jeedom zu `Plugins > Plugin-Verwaltung`.
*   Suchen Sie "n8n Connect" in der Liste und klicken Sie auf das Symbol (normalerweise ein Schraubenschlüssel) oder auf die Schaltfläche `Konfiguration`.

### n8n-Verbindungsparameter

Auf der Konfigurationsseite finden Sie die folgenden Felder:

*   **n8n-Instanz-URL:**
    *   Geben Sie die vollständige Adresse Ihrer n8n-Instanz ein.
    *   **Beispiele:**
        *   `https://mein.n8n.local` (für eine Instanz mit SSL/TLS)
        *   `http://192.168.1.100:5678` (für eine lokale Instanz ohne SSL/TLS, mit dem Standardport)
    *   Stellen Sie sicher, dass die URL vom Jeedom-Server aus zugänglich ist.
*   **API-Schlüssel:**
    *   Kopieren Sie den API-Schlüssel, den Sie in Ihrer n8n-Instanz generiert haben (unter `Einstellungen > API`), und fügen Sie ihn ein.
    *   **Warnung:** Geben Sie diesen Schlüssel niemals weiter. Er gewährt Zugriff auf Ihre n8n-Instanz.

### Verbindungstest

*   Nachdem Sie die URL und den API-Schlüssel eingegeben haben, klicken Sie auf die Schaltfläche **"Testen"**.
*   Jeedom versucht, eine Verbindung zu Ihrer n8n-Instanz herzustellen und eine Liste von Workflows abzurufen, um die Gültigkeit der bereitgestellten Informationen zu überprüfen.
*   Eine Erfolgs- oder Fehlermeldung wird angezeigt, die Ihnen mitteilt, ob die Verbindung korrekt hergestellt wurde.

## 4. Geräte (Workflows) Konfiguration

Jedes Jeedom-Gerät repräsentiert einen bestimmten n8n-Workflow, den Sie steuern möchten.

### Neues Gerät erstellen

1.  Gehen Sie in Jeedom zu `Plugins > n8n Connect`.
2.  Klicken Sie auf die Schaltfläche **"Hinzufügen"**, um ein neues Gerät zu erstellen.

### Allgemeine Geräteparameter

*   **Gerätename:** Geben Sie Ihrem Jeedom-Gerät einen klaren und aussagekräftigen Namen (z. B. "Gartenbewässerungs-Workflow", "Benachrichtigungs-Workflow").
*   **Übergeordnetes Objekt:** Ordnen Sie das Gerät einem vorhandenen Jeedom-Objekt zu (z. B. "Garten", "Zuhause").
*   **Kategorie:** Weisen Sie dem Gerät eine oder mehrere Kategorien zu (z. B. "Licht", "Sicherheit").
*   **Optionen:**
    *   **Aktivieren:** Aktivieren Sie dieses Kontrollkästchen, um das Gerät in Jeedom zu aktivieren.
    *   **Sichtbar:** Aktivieren Sie dieses Kontrollkästchen, um das Gerät auf dem Jeedom-Dashboard sichtbar zu machen.

### Spezifische Workflow-Parameter

*   **Workflow:**
    *   Klicken Sie auf die Schaltfläche "Aktualisieren" (<i class="fas fa-sync"></i>) neben dem Feld, um die Liste aller verfügbaren Workflows auf Ihrer n8n-Instanz zu laden.
    *   Wählen Sie den gewünschten n8n-Workflow, den dieses Gerät steuern soll, aus der Dropdown-Liste aus.
    *   **Fehlerfall:** Wenn die Liste nicht geladen wird (z. B. aufgrund eines API-Verbindungsproblems oder wenn keine Workflows gefunden werden), wird ein Feld zur manuellen Eingabe der Workflow-ID angezeigt. Sie finden Ihre Workflow-ID in der n8n-Editor-URL (z. B. `https://ihre.n8n.instanz/workflow/IHRE_WORKFLOW_ID`).
*   **Webhook-URL (Optional):**
    *   Wenn Sie diesen n8n-Workflow über den Befehl "Starten" von Jeedom aus auslösen möchten, müssen Sie dessen Webhook-URL eingeben.
    *   Diese URL wird vom "Webhook"-Knoten Ihres n8n-Workflows generiert. Kopieren Sie die vollständige URL (z. B. `https://ihre.n8n.instanz/webhook/ihr-eindeutiger-pfad`).
    *   **Wichtig:** Wenn dieses Feld leer ist, steht der Befehl "Starten" für dieses Gerät nicht zur Verfügung.
*   **Automatische Aktualisierung:** (Falls verfügbar) Ermöglicht die Definition der Häufigkeit, mit der Jeedom den Workflow-Status (aktiv/inaktiv) von n8n aktualisieren soll. Verwenden Sie den Cron-Assistenten, um einen Zeitplan zu definieren.

### Gerät speichern

*   Nachdem alle Parameter konfiguriert wurden, klicken Sie auf die Schaltfläche **"Speichern"** oben auf der Seite.
*   Jeedom speichert das Gerät und erstellt automatisch die zugehörigen Befehle (Aktivieren, Deaktivieren, Status und Starten, wenn der Webhook konfiguriert ist).

## 5. Verfügbare Befehle

Nach dem Speichern des Geräts sind die folgenden Befehle zugänglich:

### Aktionsbefehle

*   **Aktivieren:** Sendet eine Anfrage an n8n, um den diesem Gerät zugeordneten Workflow zu aktivieren. Der Workflow beginnt mit der Ausführung gemäß seiner Konfiguration (z. B. bei einem Auslöser).
*   **Deaktivieren:** Sendet eine Anfrage an n8n, um den Workflow zu deaktivieren. Der Workflow wird die Ausführung einstellen und nicht mehr auf seine Auslöser reagieren.
*   **Starten:** (Nur sichtbar, wenn eine "Webhook-URL" für das Gerät konfiguriert ist). Sendet eine HTTP `POST`-Anfrage an die angegebene Webhook-URL. Dies löst die Ausführung des n8n-Workflows aus, als ob der Webhook extern aufgerufen worden wäre.

### Informationsbefehle

*   **Status:** Ein binärer Informationsbefehl (`0` oder `1`), der den aktuellen Status des Workflows in n8n anzeigt:
    *   `1` (Aktiv): Der Workflow ist aktiviert und bereit zur Ausführung.
    *   `0` (Inaktiv): Der Workflow ist deaktiviert.
    *   Diese Informationen werden bei der automatischen Aktualisierung oder nach einer Aktivierungs-/Deaktivierungsaktion aktualisiert.

## 6. Fehlerbehebung und häufige Fehler

Hier sind die am häufigsten auftretenden Probleme und deren Lösung.

### HTTP 401 "unauthorized" Fehler

**Beschreibung:** Dieser Fehler weist auf ein Authentifizierungsproblem beim Versuch hin, eine Verbindung zur n8n-API herzustellen.

**Mögliche Ursachen:**
*   Fehlender, falscher oder abgelaufener API-Schlüssel.
*   Die REST-API ist in n8n nicht aktiviert.
*   Die n8n-Instanz-URL ist falsch oder nicht zugänglich.
*   Problem mit den API-Schlüsselberechtigungen.

**Lösungen:**
1.  **Überprüfen Sie Ihre globale Plugin-Konfiguration:** Stellen Sie sicher, dass die **n8n-Instanz-URL** und der **API-Schlüssel** in `Plugins > Plugin-Verwaltung > n8n Connect > Konfiguration` korrekt eingegeben wurden.
2.  **Testen Sie die Verbindung:** Verwenden Sie die Schaltfläche **"Testen"** auf derselben Seite, um Ihre Anmeldeinformationen und die Zugänglichkeit der Instanz zu überprüfen.
3.  **Überprüfen Sie n8n:**
    *   Stellen Sie in Ihrer n8n-Instanz sicher, dass die REST-API aktiviert ist (**Einstellungen > API**).
    *   Überprüfen Sie, ob der von Ihnen verwendete API-Schlüssel tatsächlich der hier generierte ist, dass er nicht abgelaufen ist und dass er über die erforderlichen Berechtigungen verfügt (mindestens `workflows.read`, `workflows.write`, `workflows.activate`, `workflows.deactivate`).
    *   Stellen Sie sicher, dass Ihre n8n-Instanz gestartet ist und ordnungsgemäß funktioniert.
4.  **Netzwerkkonnektivität:** Überprüfen Sie Firewalls oder Netzwerk-Routing-Probleme, die Jeedom daran hindern könnten, mit n8n über den angegebenen Port zu kommunizieren.

### "Webhook-URL fehlt"

**Beschreibung:** Diese Meldung wird angezeigt, wenn Sie versuchen, den Befehl "Starten" für ein Gerät auszuführen, das Feld "Webhook-URL" in seiner Konfiguration jedoch leer ist.

**Lösung:**
*   Bearbeiten Sie das betroffene Gerät (`Plugins > n8n Connect`, klicken Sie auf das Gerät).
*   Geben Sie in den spezifischen Parametern die vollständige Webhook-URL Ihres n8n-Workflows in das Feld **"Webhook-URL"** ein.
*   Speichern Sie das Gerät. Der Befehl "Starten" sollte nun funktionieren.

### "Webhook-Fehler: Der angeforderte Webhook ... ist nicht registriert"

**Beschreibung:** n8n zeigt an, dass der Webhook, der der URL entspricht, nicht gefunden werden kann oder dass der Workflow nicht aktiv ist.

**Mögliche Ursachen:**
*   Der Workflow ist in n8n nicht aktiviert. Produktions-Webhooks funktionieren nur, wenn der Workflow aktiv ist.
*   Die in Jeedom eingegebene Webhook-URL ist falsch (Tippfehler, falsche Webhook-ID usw.).
*   Der "Webhook"-Knoten in Ihrem n8n-Workflow ist nicht für die Annahme von `POST`-Anfragen konfiguriert (obwohl dies das Standardverhalten ist).

**Lösungen:**
1.  **Aktivieren Sie den Workflow in n8n:** Öffnen Sie Ihren Workflow in n8n und stellen Sie sicher, dass die Schaltfläche `Aktiv` (oben rechts im Editor) auf `EIN` steht.
2.  **Überprüfen Sie die Webhook-URL:** Kopieren Sie die Webhook-URL direkt aus dem "Webhook"-Knoten Ihres n8n-Workflows und fügen Sie sie erneut in das Feld "Webhook-URL" des Jeedom-Geräts ein, um Fehler zu vermeiden.
3.  **HTTP-Methode:** Das Plugin sendet eine `POST`-Anfrage. Stellen Sie sicher, dass Ihr "Webhook"-Knoten in n8n für die Annahme von `POST`-Anfragen konfiguriert ist (dies ist der Standard für Produktions-Webhooks).

### "Zeitüberschreitung"

**Beschreibung:** Jeedom hat innerhalb der zugewiesenen Zeit (standardmäßig 30 Sekunden) keine Antwort von n8n erhalten.

**Mögliche Ursachen:**
*   Ihre n8n-Instanz ist gestoppt oder reagiert nicht.
*   Problem mit der Netzwerkkonnektivität zwischen Jeedom und n8n (Firewall, Router usw.).
*   Die n8n-Instanz ist überlastet oder reagiert sehr langsam.

**Lösungen:**
1.  **Überprüfen Sie den n8n-Status:** Stellen Sie sicher, dass Ihre n8n-Instanz ausgeführt wird und über einen Browser oder einen `ping` vom Jeedom-Server aus zugänglich ist.
2.  **Überprüfen Sie die Konnektivität:** Testen Sie die Netzwerkverbindung zwischen Ihrem Jeedom und n8n. Versuchen Sie beispielsweise vom Jeedom-Terminal aus `curl -v IHRE_N8N_URL`.
3.  **n8n-Leistung:** Wenn n8n überlastet ist, sollten Sie Ihre Workflows optimieren oder die Ihrer n8n-Instanz zugewiesenen Ressourcen erhöhen.

### Diagnoseprotokolle

Weitere detaillierte Informationen zu Fehlern finden Sie in den Plugin-Protokollen von n8n Connect:

1.  Gehen Sie in Jeedom zu `Werkzeuge > Protokolle`.
2.  Wählen Sie in der Dropdown-Liste `n8nconnect` aus.
3.  Die Protokolle zeigen die Kommunikation zwischen Jeedom und n8n an, einschließlich gesendeter Anfragen und empfangener Antworten, was für die Fehlerbehebung entscheidend ist.

## Workflow-Fehlerbenachrichtigungen

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

## 7. Support

Wenn Sie nach dem Befolgen dieser Anleitung weiterhin Probleme haben, sammeln Sie bitte die folgenden Informationen, bevor Sie um Hilfe bitten:

*   Genaue Jeedom-Version (sichtbar unter `Einstellungen > System > Konfiguration > Allgemein`).
*   Ihre n8n-Instanzversion.
*   Vollständige und genaue Fehlermeldungen, direkt aus den `n8nconnect`-Jeedom-Protokollen kopiert.
*   Screenshot der globalen Plugin-Konfigurationsseite (blenden Sie Ihren API-Schlüssel aus).
*   Screenshot der Konfigurationsseite des betroffenen Jeedom-Geräts.
*   Detaillierte Beschreibung der Schritte zur Reproduktion des Problems.