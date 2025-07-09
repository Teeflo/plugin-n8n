# Configurazione e risoluzione dei problemi del plugin n8n Connect

Questo documento fornisce una guida dettagliata per la configurazione del plugin n8n Connect in Jeedom, nonché soluzioni ai problemi comuni che potresti incontrare.

## Indice

1.  [Prerequisiti](#1-prerequisiti)
2.  [Installazione del plugin](#2-installazione-del-plugin)
3.  [Configurazione globale del plugin](#3-configurazione-globale-del-plugin)
    *   [Accesso alla configurazione](#accesso-alla-configurazione)
    *   [Parametri di connessione n8n](#parametri-di-connessione-n8n)
    *   [Test di connessione](#test-di-connessione)
4.  [Configurazione degli equipaggiamenti (workflow)](#4-configurazione-degli-equipaggiamenti-workflow)
    *   [Creazione di un nuovo equipaggiamento](#creazione-di-un-nuovo-equipaggiamento)
    *   [Parametri generali dell'equipaggiamento](#parametri-generali-dell'equipaggiamento)
    *   [Parametri specifici del workflow](#parametri-specifici-del-workflow)
    *   [Salvataggio dell'equipaggiamento](#salvataggio-dell'equipaggiamento)
5.  [Comandi disponibili](#5-comandi-disponibili)
    *   [Comandi di azione](#comandi-di-azione)
    *   [Comandi di informazione](#comandi-di-informazione)
6.  [Risoluzione dei problemi ed errori comuni](#6-risoluzione-dei-problemi-ed-errori-comuni)
    *   [Errore HTTP 401 "non autorizzato"](#errore-http-401-non-autorizzato)
    *   ["URL webhook mancante"](#url-webhook-mancante)
    *   ["Errore webhook: Il webhook richiesto ... non è registrato"](#errore-webhook-il-webhook-richiesto--non-è-registrato)
    *   ["Timeout"](#timeout)
    *   ["Risposta API n8n non valida"](#risposta-api-n8n-non-valida)
    *   [Log di diagnostica](#log-di-diagnostica)
7.  [Supporto](#7-supporto)

---

## 1. Prerequisiti

Prima di iniziare la configurazione, assicurati che i seguenti elementi siano presenti:

*   **Istanza n8n:** Un'istanza n8n funzionante e accessibile dalla tua installazione Jeedom. Può essere un'istanza locale, su una rete privata o un'istanza cloud.
*   **API REST n8n abilitata:** L'API REST deve essere abilitata nelle impostazioni della tua istanza n8n. Di solito la trovi sotto `Impostazioni > API`.
*   **Chiave API n8n:** Una chiave API valida generata in n8n. Questa chiave deve avere i permessi necessari per:
    *   Elencare i workflow.
    *   Attivare/disattivare i workflow.
    *   (Opzionale) Eseguire i workflow tramite l'API se utilizzi questo metodo (anche se il plugin preferisce i webhook per l'avvio).
*   **Jeedom:** Un'installazione Jeedom versione 4.2.0 o superiore.
*   **Estensione PHP cURL:** L'estensione PHP `cURL` è essenziale per la comunicazione del plugin con l'API n8n. Assicurati che sia installata e abilitata sul tuo sistema Jeedom.

## 2. Installazione del plugin

1.  **Tramite il Market Jeedom:** Accedi alla tua interfaccia Jeedom, quindi vai su `Plugin > Gestione plugin > Market`. Cerca "n8n Connect" e installalo.
2.  **Attivazione:** Una volta completata l'installazione, il plugin apparirà nell'elenco dei tuoi plugin. Clicca sul pulsante `Attiva` (di solito un'icona con un segno di spunta verde) per renderlo operativo.

## 3. Configurazione globale del plugin

Questo passaggio stabilisce la connessione tra il tuo Jeedom e la tua istanza n8n.

### Accesso alla configurazione

*   In Jeedom, vai su `Plugin > Gestione plugin`.
*   Individua "n8n Connect" nell'elenco e clicca sulla sua icona (di solito una chiave inglese) o sul pulsante `Configurazione`.

### Parametri di connessione n8n

Nella pagina di configurazione, troverai i seguenti campi:

*   **URL dell'istanza n8n:**
    *   Inserisci l'indirizzo completo della tua istanza n8n.
    *   **Esempi:**
        *   `https://mio.n8n.local` (per un'istanza con SSL/TLS)
        *   `http://192.168.1.100:5678` (per un'istanza locale senza SSL/TLS, con la porta predefinita)
    *   Assicurati che l'URL sia accessibile dal server Jeedom.
*   **Chiave API:**
    *   Copia e incolla la chiave API che hai generato nella tua istanza n8n (sotto `Impostazioni > API`).
    *   **Attenzione:** Non condividere mai questa chiave. Concede l'accesso alla tua istanza n8n.

### Test di connessione

*   Dopo aver inserito l'URL e la chiave API, clicca sul pulsante **"Testa"**.
*   Jeedom tenterà di connettersi alla tua istanza n8n e di recuperare un elenco di workflow per verificare la validità delle informazioni fornite.
*   Verrà visualizzato un messaggio di successo o di errore, che indica se la connessione è stata stabilita correttamente.

## 4. Configurazione degli equipaggiamenti (workflow)

Ogni equipaggiamento Jeedom rappresenta un workflow n8n specifico che desideri controllare.

### Creazione di un nuovo equipaggiamento

1.  In Jeedom, vai su `Plugin > n8n Connect`.
2.  Clicca sul pulsante **"Aggiungi"** per creare un nuovo equipaggiamento.

### Parametri generali dell'equipaggiamento

*   **Nome dell'equipaggiamento:** Dai un nome chiaro e descrittivo al tuo equipaggiamento Jeedom (es: "Workflow Irrigazione Giardino", "Workflow Notifiche").
*   **Oggetto padre:** Associa l'equipaggiamento a un oggetto Jeedom esistente (es: "Giardino", "Casa").
*   **Categoria:** Assegna una o più categorie all'equipaggiamento (es: "Luce", "Sicurezza").
*   **Opzioni:**
    *   **Attiva:** Seleziona questa casella per attivare l'equipaggiamento in Jeedom.
    *   **Visibile:** Seleziona questa casella per rendere l'equipaggiamento visibile sulla Dashboard Jeedom.

### Parametri specifici del workflow

*   **Workflow:**
    *   Clicca sul pulsante di aggiornamento (<i class="fas fa-sync"></i>) accanto al campo per caricare l'elenco di tutti i workflow disponibili sulla tua istanza n8n.
    *   Seleziona il workflow n8n desiderato che questo equipaggiamento deve controllare dall'elenco a discesa.
    *   **Caso di errore:** Se l'elenco non si carica (ad esempio, a causa di un problema di connessione API o se non vengono trovati workflow), apparirà un campo di inserimento manuale dell'ID del workflow. Puoi trovare l'ID del tuo workflow nell'URL dell'editor n8n (es: `https://tua.istanza.n8n/workflow/TUO_ID_WORKFLOW`).
*   **URL Webhook (Opzionale):**
    *   Se desideri poter attivare questo workflow n8n tramite il comando "Avvia" da Jeedom, devi inserire il suo URL webhook.
    *   Questo URL è generato dal nodo "Webhook" del tuo workflow n8n. Copia l'URL completa (es: `https://tua.istanza.n8n/webhook/il-tuo-percorso-unico`).
    *   **Importante:** Se questo campo è vuoto, il comando "Avvia" non sarà disponibile per questo equipaggiamento.
*   **Aggiornamento automatico:** (Se disponibile) Consente di definire la frequenza con cui Jeedom deve aggiornare lo stato del workflow (attivo/inattivo) da n8n. Utilizza l'assistente cron per definire una pianificazione.

### Salvataggio dell'equipaggiamento

*   Una volta configurati tutti i parametri, clicca sul pulsante **"Salva"** nella parte superiore della pagina.
*   Jeedom salverà l'equipaggiamento e creerà automaticamente i comandi associati (Attiva, Disattiva, Stato e Avvia se il webhook è configurato).

## 5. Comandi disponibili

Dopo il salvataggio dell'equipaggiamento, saranno accessibili i seguenti comandi:

### Comandi di azione

*   **Attiva:** Invia una richiesta a n8n per attivare il workflow associato a questo equipaggiamento. Il workflow inizierà l'esecuzione in base alla sua configurazione (ad esempio, su un trigger).
*   **Disattiva:** Invia una richiesta a n8n per disattivare il workflow. Il workflow smetterà di essere eseguito e non risponderà più ai suoi trigger.
*   **Avvia:** (Visibile solo se un "URL Webhook" è configurato per l'equipaggiamento). Invia una richiesta HTTP `POST` all'URL del webhook specificato. Questo attiverà l'esecuzione del workflow n8n come se il webhook fosse stato chiamato esternamente.

### Comandi di informazione

*   **Stato:** Un comando di informazione binario (`0` o `1`) che indica lo stato attuale del workflow in n8n:
    *   `1` (Attivo): Il workflow è attivato e pronto per l'esecuzione.
    *   `0` (Inattivo): Il workflow è disattivato.
    *   Questa informazione viene aggiornata durante l'aggiornamento automatico o dopo un'azione di attivazione/disattivazione.

## 6. Risoluzione dei problemi ed errori comuni

Ecco i problemi più frequentemente riscontrati e come risolverli.

### Errore HTTP 401 "non autorizzato"

**Descrizione:** Questo errore indica un problema di autenticazione durante il tentativo di connessione all'API n8n.

**Possibili cause:**
*   Chiave API mancante, errata o scaduta.
*   L'API REST non è abilitata in n8n.
*   L'URL dell'istanza n8n non è corretta o non è accessibile.
*   Problema di permessi della chiave API.

**Soluzioni:**
1.  **Verifica la configurazione globale del tuo plugin:** Assicurati che l'**URL dell'istanza n8n** e la **Chiave API** siano inseriti correttamente in `Plugin > Gestione plugin > n8n Connect > Configurazione`.
2.  **Testa la connessione:** Utilizza il pulsante **"Testa"** su questa stessa pagina per convalidare le tue credenziali e l'accessibilità dell'istanza.
3.  **Verifica n8n:**
    *   Nella tua istanza n8n, vai su `Impostazioni > API` e assicurati che l'API REST sia abilitata.
    *   Verifica che la chiave API che stai utilizzando sia effettivamente quella generata qui, che non sia scaduta e che abbia i permessi necessari (almeno `workflows.read`, `workflows.write`, `workflows.activate`, `workflows.deactivate`).
    *   Assicurati che la tua istanza n8n sia avviata e funzioni correttamente.
4.  **Connettività di rete:** Controlla firewall o problemi di routing di rete che potrebbero impedire a Jeedom di comunicare con n8n sulla porta specificata.

### "URL webhook mancante"

**Descrizione:** Questo messaggio appare quando si tenta di eseguire il comando "Avvia" per un equipaggiamento, ma il campo "URL Webhook" è vuoto nella sua configurazione.

**Soluzione:**
*   Modifica l'equipaggiamento interessato (`Plugin > n8n Connect`, clicca sull'equipaggiamento).
*   Nei parametri specifici, inserisci l'URL webhook completa del tuo workflow n8n nel campo **"URL Webhook"**.
*   Salva l'equipaggiamento. Il comando "Avvia" dovrebbe ora funzionare.

### "Errore webhook: Il webhook richiesto ... non è registrato"

**Descrizione:** n8n indica che non riesce a trovare il webhook corrispondente all'URL o che il workflow non è attivo.

**Possibili cause:**
*   Il workflow non è attivato in n8n. I webhook di produzione funzionano solo se il workflow è attivo.
*   L'URL del webhook inserita in Jeedom non è corretta (errore di battitura, ID webhook errato, ecc.).
*   Il nodo "Webhook" nel tuo workflow n8n non è configurato per accettare richieste `POST` (anche se questo è il comportamento predefinito).

**Soluzioni:**
1.  **Attiva il workflow in n8n:** Apri il tuo workflow in n8n e assicurati che il pulsante `Attivo` (in alto a destra dell'editor) sia impostato su `ON`.
2.  **Verifica l'URL del webhook:** Copia l'URL del webhook direttamente dal nodo "Webhook" del tuo workflow n8n e incollala di nuovo nel campo "URL Webhook" dell'equipaggiamento Jeedom per evitare errori.
3.  **Metodo HTTP:** Il plugin invia una richiesta `POST`. Assicurati che il tuo nodo "Webhook" in n8n sia configurato per accettare richieste `POST` (questo è il valore predefinito per i webhook di produzione).

### "Timeout"

**Descrizione:** Jeedom non ha ricevuto una risposta da n8n entro il tempo assegnato (30 secondi per impostazione predefinita).

**Possibili cause:**
*   La tua istanza n8n è ferma o non risponde.
*   Problema di connettività di rete tra Jeedom e n8n (firewall, router, ecc.).
*   L'istanza n8n è sovraccarica o risponde molto lentamente.

**Soluzioni:**
1.  **Verifica lo stato di n8n:** Assicurati che la tua istanza n8n sia in esecuzione e accessibile tramite un browser o un `ping` dal server Jeedom.
2.  **Verifica la connettività:** Testa la connessione di rete tra il tuo Jeedom e n8n. Ad esempio, dal terminale del tuo Jeedom, prova `curl -v TUO_URL_N8N`.
3.  **Prestazioni di n8n:** Se n8n è sovraccarico, considera di ottimizzare i tuoi workflow o di aumentare le risorse allocate alla tua istanza n8n.

### Log di diagnostica

Per maggiori informazioni sugli errori, consulta i log del plugin n8n Connect:

1.  In Jeedom, vai su `Strumenti > Log`.
2.  Nell'elenco a discesa, seleziona `n8nconnect`.
3.  I log mostrano le comunicazioni tra Jeedom e n8n, incluse le richieste inviate e le risposte ricevute, il che è fondamentale per la risoluzione dei problemi.

## Notifiche di errore del workflow

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

## 7. Supporto

Se riscontri problemi persistenti dopo aver seguito questa guida, raccogli le seguenti informazioni prima di chiedere aiuto:

*   Versione esatta di Jeedom (visibile in `Impostazioni > Sistema > Configurazione > Generale`).
*   Versione della tua istanza n8n.
*   Messaggi di errore completi ed esatti, copiati direttamente dai log di Jeedom `n8nconnect`.
*   Screenshot della pagina di configurazione globale del plugin (nascondi la tua chiave API).
*   Screenshot della pagina di configurazione dell'equipaggiamento Jeedom interessato.
*   Descrizione dettagliata dei passaggi per riprodurre il problema.