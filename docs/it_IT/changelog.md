# Changelog n8n Connect

## 0.1.0
- Prima versione del plugin n8n Connect per Jeedom.
  Questo plugin ti permette di controllare e monitorare i tuoi workflow n8n direttamente dalla tua interfaccia domotica Jeedom. Offre un'integrazione semplice ed efficace per lanciare i workflow, attivarli/disattivarli, e verificare il loro stato.

  Funzionalità incluse:
  - Configurazione dell'istanza n8n: Collega facilmente il tuo Jeedom alla tua istanza n8n tramite il suo URL e una chiave API.
  - Gestione dei workflow: Crea equipaggiamenti Jeedom per ogni workflow n8n che desideri controllare.
  - Comandi di azione:
    - Attiva/Disattiva: Cambia lo stato di esecuzione dei tuoi workflow n8n.
    - Avvia (tramite Webhook): Attiva un workflow n8n inviando una richiesta al suo URL webhook configurato.
  - Comandi di informazione:
    - Stato: Ottieni lo stato (attivo/inattivo) del tuo workflow n8n.
  - Notifiche di errore del workflow: Ricevi notifiche in Jeedom quando un workflow n8n fallisce.
  - Selezione semplificata: Scegli i tuoi workflow n8n tramite un elenco a discesa o inserisci manualmente il loro ID.
  - Registrazione dettagliata: Log precisi per facilitare la diagnosi in caso di problemi.

## 0.1.1
- Descrizione italiana corretta in info.json.
- Corretto errore di sintassi JSON in info.json (virgola in più).
