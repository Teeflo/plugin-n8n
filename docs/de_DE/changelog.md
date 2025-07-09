# Changelog n8n Connect

## 0.1.0
- Erste Version des n8n Connect Plugins für Jeedom.
  Dieses Plugin ermöglicht es Ihnen, Ihre n8n-Workflows direkt über Ihre Jeedom-Hausautomatisierungsoberfläche zu steuern und zu überwachen. Es bietet eine einfache und effektive Integration, um Workflows zu starten, zu aktivieren/deaktivieren und ihren Status zu überprüfen.

  Enthaltene Funktionen:
  - n8n-Instanzkonfiguration: Verbinden Sie Ihr Jeedom einfach mit Ihrer n8n-Instanz über deren URL und einen API-Schlüssel.
  - Workflow-Verwaltung: Erstellen Sie Jeedom-Geräte für jeden n8n-Workflow, den Sie steuern möchten.
  - Aktionsbefehle:
    - Aktivieren/Deaktivieren: Ändern Sie den Ausführungsstatus Ihrer n8n-Workflows.
    - Starten (über Webhook): Lösen Sie einen n8n-Workflow aus, indem Sie eine Anfrage an seine konfigurierte Webhook-URL senden.
  - Informationsbefehl:
    - Status: Erhalten Sie den Status (aktiv/inaktiv) Ihres n8n-Workflows.
  - Workflow-Fehlerbenachrichtigungen: Erhalten Sie Benachrichtigungen in Jeedom, wenn ein n8n-Workflow fehlschlägt.
  - Vereinfachte Auswahl: Wählen Sie Ihre n8n-Workflows über eine Dropdown-Liste aus oder geben Sie deren ID manuell ein.
  - Detaillierte Protokollierung: Präzise Protokolle zur Erleichterung der Diagnose bei Problemen.

## 0.1.1
- Italienische Beschreibung in info.json korrigiert.
- JSON-Syntaxfehler in info.json behoben (zusätzliches Komma).
