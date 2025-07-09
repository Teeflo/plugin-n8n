# n8n Connect per Jeedom

Questo plugin ti permette di controllare e monitorare i tuoi workflow **n8n** direttamente dalla tua interfaccia domotica Jeedom. Offre un'integrazione semplice ed efficace per avviare i workflow, attivarli/disattivarli e verificarne lo stato.

## Funzionalità

*   **Configurazione dell'istanza n8n:** Collega facilmente il tuo Jeedom alla tua istanza n8n tramite il suo URL e una chiave API.
*   **Gestione dei workflow:** Crea equipaggiamenti Jeedom per ogni workflow n8n che desideri controllare.
*   **Comandi di azione:**
    *   **Attiva/Disattiva:** Cambia lo stato di esecuzione dei tuoi workflow n8n.
    *   **Avvia (tramite Webhook):** Attiva un workflow n8n inviando una richiesta al suo URL webhook configurato. Questo comando appare solo se un webhook è configurato per l'equipaggiamento.
*   **Comando di informazione:**
    *   **Stato:** Ottieni lo stato (attivo/inattivo) del tuo workflow n8n.
*   **Notifiche di errore del workflow:** Ricevi notifiche in Jeedom quando un workflow n8n fallisce, consentendo una gestione proattiva dei problemi.
*   **Selezione semplificata:** Scegli i tuoi workflow n8n tramite un elenco a discesa o inserisci manualmente il loro ID.
*   **Registrazione dettagliata:** Log precisi per facilitare la diagnosi in caso di problemi.

## Prerequisiti

1.  Un'istanza [n8n](https://n8n.io/) funzionante e accessibile dal tuo Jeedom.
2.  L'API REST di n8n deve essere abilitata sulla tua istanza.
3.  Una chiave API n8n valida con i permessi necessari per gestire i workflow.
4.  Jeedom versione 4.2.0 o superiore.
5.  L'estensione PHP `cURL` deve essere installata e abilitata sul tuo sistema Jeedom.

## Installazione

1.  Installa il plugin "n8n Connect" direttamente dal Market Jeedom.
2.  Dopo l'installazione, attiva il plugin in **Plugin > Gestione plugin**.

## Configurazione

### 1. Configurazione globale del plugin

Accedi alla configurazione globale del plugin tramite **Plugin > Gestione plugin > n8n Connect > Configurazione**.

*   **URL dell'istanza n8n:** Inserisci l'indirizzo completo della tua istanza n8n (es: `https://mio.n8n.local` o `http://192.168.1.100:5678`).
*   **Chiave API:** Inserisci la tua chiave API n8n, generata in n8n (**Impostazioni > API**).
*   Clicca sul pulsante **"Testa"** per verificare la connessione alla tua istanza n8n.

### 2. Configurazione degli equipaggiamenti (workflow)

Per ogni workflow n8n che desideri controllare:

1.  Vai su **Plugin > n8n Connect**.
2.  Clicca su **"Aggiungi"** per creare un nuovo equipaggiamento.
3.  **Nome dell'equipaggiamento:** Dai un nome significativo al tuo equipaggiamento Jeedom (es: "Workflow Luci Soggiorno").
4.  **Workflow:**
    *   Clicca sul pulsante di aggiornamento (<i class="fas fa-sync"></i>) per caricare l'elenco dei tuoi workflow n8n disponibili.
    *   Seleziona il workflow desiderato dall'elenco a discesa.
    *   Se l'elenco non si carica (ad esempio, a causa di un problema di connessione API), apparirà un campo di inserimento manuale dell'ID del workflow. Puoi trovare l'ID del tuo workflow nell'interfaccia n8n.
5.  **URL Webhook (Opzionale):** Se desideri attivare questo workflow tramite un comando "Avvia", incolla qui l'URL del webhook del tuo workflow n8n. Questo URL è fornito dal nodo "Webhook" del tuo workflow n8n.
6.  Configura i **Parametri generali** (Oggetto padre, Categoria, Attiva/Visibile) secondo le tue esigenze.
7.  Clicca su **"Salva"**. I comandi "Attiva", "Disattiva" e "Stato" verranno creati automaticamente. Il comando "Avvia" verrà aggiunto se è stato fornito un URL webhook.

## Comandi disponibili

Una volta configurato l'equipaggiamento, saranno disponibili i seguenti comandi:

*   **Attiva:** Attiva il workflow corrispondente in n8n.
*   **Disattiva:** Disattiva il workflow corrispondente in n8n.
*   **Avvia:** Invia una richiesta HTTP POST all'URL del webhook configurato per il workflow. Questo comando è visibile solo se un "URL Webhook" è fornito nella configurazione dell'equipaggiamento.
*   **Stato:** Un comando di informazione binario che indica se il workflow è attivo (1) o inattivo (0) in n8n.

## Risoluzione dei problemi

### Errore HTTP 401 "unauthorized"

Questo errore indica un problema di autenticazione durante il tentativo di connessione all'API n8n.

*   **Verifica la tua configurazione:** Assicurati che l'**URL dell'istanza n8n** e la **Chiave API** siano inseriti correttamente nella configurazione globale del plugin.
*   **Testa la connessione:** Usa il pulsante **"Testa"** in questa stessa pagina per convalidare le tue credenziali.
*   **Verifica n8n:**
    *   Assicurati che l'API REST sia abilitata in n8n (**Impostazioni > API**).
    *   Verifica che la tua chiave API n8n sia valida e non scaduta, e che abbia i permessi necessari.
    *   Assicurati che la tua istanza n8n sia avviata e accessibile da Jeedom.
*   **Connettività di rete:** Controlla firewall o problemi di rete che potrebbero impedire a Jeedom di comunicare con n8n.

### Messaggi di errore comuni

*   **"URL webhook mancante":** Il comando "Avvia" è stato eseguito, ma nessun URL webhook è configurato per questo equipaggiamento.
*   **"Errore webhook: Il webhook richiesto ... non è registrato":** Il workflow non è attivo in n8n, o l'URL del webhook non è corretta. Assicurati che il workflow sia attivato in n8n e che l'URL sia esatta.
*   **"Timeout":** Jeedom non è riuscito a raggiungere la tua istanza n8n entro il tempo assegnato. Assicurati che n8n sia online e accessibile.
*   **"Risposta API n8n non valida":** L'API n8n ha restituito una risposta inaspettata.

### Log di diagnostica

Per informazioni più dettagliate, consulta i log del plugin:
1.  Vai su **Strumenti > Log**.
2.  Seleziona il plugin **n8nconnect**.
3.  Cerca i messaggi di errore recenti per identificare la causa del problema.

## Notifiche di errore n8n a Jeedom

Per ricevere notifiche di errore dai tuoi workflow n8n direttamente in Jeedom, puoi configurare un "Workflow di errore" globale in n8n che invierà una richiesta HTTP a Jeedom.

### Configurazione in n8n

1.  **Crea un nuovo workflow** in n8n (o usa un workflow esistente dedicato agli errori).
2.  Aggiungi un nodo **"Webhook"** come trigger. Configuralo per ascoltare le richieste `POST`.
3.  Aggiungi un nodo **"Richiesta HTTP"** dopo il nodo "Webhook".
    *   **Metodo:** `POST`
    *   **URL:** `http://TUO_IP_JEEDOM/plugins/n8nconnect/core/ajax/n8nconnect.ajax.php?action=receiveErrorNotification`
        *   Sostituisci `TUO_IP_JEEDOM` con l'indirizzo IP o il nome di dominio della tua installazione Jeedom.
    *   **Tipo di contenuto del corpo:** `JSON`
    *   **Corpo JSON:** Puoi inviare qualsiasi dato JSON pertinente. Ad esempio, per inviare informazioni di errore dal workflow fallito, puoi usare un'espressione come:
        ```json
        {
          "workflowName": "{{ $json.workflow.name }}",
          "workflowId": "{{ $json.workflow.id }}",
          "executionId": "{{ $json.id }}",
          "error": "{{ $json.error.message }}",
          "stackTrace": "{{ $json.error.stack }}"
        }
        ```
        Queste variabili (`$json.workflow.name`, ecc.) sono disponibili nel contesto di un workflow di errore n8n.
4.  **Attiva questo workflow** in n8n.
5.  **Configura questo workflow come "Workflow di errore" globale:**
    *   In n8n, vai su **Impostazioni > Gestione errori workflow**.
    *   Seleziona il workflow che hai appena creato dall'elenco a discesa "Workflow di errore".

### Elaborazione in Jeedom

Il plugin n8n Connect riceverà queste notifiche e le registrerà nei log del plugin (`Strumenti > Log > n8nconnect`). Puoi quindi utilizzare gli scenari Jeedom per analizzare questi log e attivare azioni (notifiche, avvisi, ecc.) in base al contenuto dei messaggi di errore.