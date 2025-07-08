# Configuration et Dépannage du Plugin n8n Connect

Ce document fournit un guide détaillé pour la configuration du plugin n8n Connect dans Jeedom, ainsi que des solutions aux problèmes courants que vous pourriez rencontrer.

## Table des Matières

1.  [Prérequis](#1-prérequis)
2.  [Installation du Plugin](#2-installation-du-plugin)
3.  [Configuration Globale du Plugin](#3-configuration-globale-du-plugin)
    *   [Accès à la Configuration](#accès-à-la-configuration)
    *   [Paramètres de Connexion n8n](#paramètres-de-connexion-n8n)
    *   [Test de Connexion](#test-de-connexion)
4.  [Configuration des Équipements (Workflows)](#4-configuration-des-équipements-workflows)
    *   [Création d'un Nouvel Équipement](#création-dun-nouvel-équipement)
    *   [Paramètres Généraux de l'Équipement](#paramètres-généraux-de-léquipement)
    *   [Paramètres Spécifiques du Workflow](#paramètres-spécifiques-du-workflow)
    *   [Sauvegarde de l'Équipement](#sauvegarde-de-léquipement)
5.  [Commandes Disponibles](#5-commandes-disponibles)
    *   [Commandes d'Action](#commandes-daction)
    *   [Commandes d'Information](#commandes-dinformation)
6.  [Dépannage et Erreurs Courantes](#6-dépannage-et-erreurs-courantes)
    *   [Erreur HTTP 401 "unauthorized"](#erreur-http-401-unauthorized)
    *   ["URL de webhook manquante"](#url-de-webhook-manquante)
    *   ["Erreur webhook : The requested webhook ... is not registered"](#erreur-webhook--the-requested-webhook--is-not-registered)
    *   ["Délai d'attente dépassé"](#délai-dattente-dépassé)
    *   ["Réponse invalide de l'API n8n"](#réponse-invalide-de-lapi-n8n)
    *   [Logs de Diagnostic](#logs-de-diagnostic)
7.  [Support](#7-support)

---

## 1. Prérequis

Avant de commencer la configuration, assurez-vous que les éléments suivants sont en place :

*   **Instance n8n :** Une instance n8n fonctionnelle et accessible depuis votre installation Jeedom. Cela peut être une instance locale, sur un réseau privé, ou une instance cloud.
*   **API REST n8n activée :** L'API REST doit être activée dans les paramètres de votre instance n8n. Vous la trouverez généralement sous `Settings > API`.
*   **Clé API n8n :** Une clé API valide générée dans n8n. Cette clé doit avoir les permissions nécessaires pour :
    *   Lister les workflows.
    *   Activer/Désactiver les workflows.
    *   (Optionnel) Exécuter des workflows via l'API si vous utilisez cette méthode (bien que le plugin privilégie les webhooks pour le lancement).
*   **Jeedom :** Une installation Jeedom version 4.2.0 ou supérieure.
*   **Extension PHP cURL :** L'extension PHP `cURL` est indispensable au fonctionnement du plugin pour communiquer avec l'API n8n. Assurez-vous qu'elle est installée et activée sur votre système Jeedom.

## 2. Installation du Plugin

1.  **Via le Market Jeedom :** Accédez à votre interface Jeedom, puis allez dans `Plugins > Gestion des plugins > Market`. Recherchez "n8n Connect" et installez-le.
2.  **Activation :** Une fois l'installation terminée, le plugin apparaîtra dans la liste de vos plugins. Cliquez sur le bouton `Activer` (généralement une icône de coche verte) pour le rendre opérationnel.

## 3. Configuration Globale du Plugin

Cette étape établit la connexion entre votre Jeedom et votre instance n8n.

### Accès à la Configuration

*   Dans Jeedom, naviguez vers `Plugins > Gestion des plugins`.
*   Localisez "n8n Connect" dans la liste et cliquez sur son icône (généralement une clé à molette) ou sur le bouton `Configuration`.

### Paramètres de Connexion n8n

Dans la page de configuration, vous trouverez les champs suivants :

*   **URL de l'instance n8n :**
    *   Saisissez l'adresse complète de votre instance n8n.
    *   **Exemples :**
        *   `https://mon.n8n.local` (pour une instance avec SSL/TLS)
        *   `http://192.168.1.100:5678` (pour une instance locale sans SSL/TLS, avec le port par défaut)
    *   Assurez-vous que l'URL est accessible depuis le serveur Jeedom.
*   **Clé API :**
    *   Copiez et collez la clé API que vous avez générée dans votre instance n8n (sous `Settings > API`).
    *   **Attention :** Ne partagez jamais cette clé. Elle donne accès à votre instance n8n.

### Test de Connexion

*   Après avoir renseigné l'URL et la Clé API, cliquez sur le bouton **"Tester"**.
*   Jeedom tentera de se connecter à votre instance n8n et de récupérer une liste de workflows pour vérifier la validité des informations fournies.
*   Un message de succès ou d'erreur s'affichera, vous indiquant si la connexion est établie correctement.

## 4. Configuration des Équipements (Workflows)

Chaque équipement Jeedom représente un workflow n8n spécifique que vous souhaitez contrôler.

### Création d'un Nouvel Équipement

1.  Dans Jeedom, allez dans `Plugins > n8n Connect`.
2.  Cliquez sur le bouton **"Ajouter"**.

### Paramètres Généraux de l'Équipement

*   **Nom de l'équipement :** Donnez un nom clair et descriptif à votre équipement Jeedom (ex: "Workflow Arrosage Jardin", "Workflow Notifications").
*   **Objet parent :** Associez l'équipement à un objet Jeedom existant (ex: "Jardin", "Maison").
*   **Catégorie :** Attribuez une ou plusieurs catégories à l'équipement (ex: "Lumière", "Sécurité").
*   **Options :**
    *   **Activer :** Cochez cette case pour activer l'équipement dans Jeedom.
    *   **Visible :** Cochez cette case pour rendre l'équipement visible sur le Dashboard Jeedom.

### Paramètres Spécifiques du Workflow

*   **Workflow :**
    *   Cliquez sur le bouton de rafraîchissement (<i class="fas fa-sync"></i>) à côté du champ pour charger la liste de tous les workflows disponibles sur votre instance n8n.
    *   Sélectionnez le workflow n8n que cet équipement doit contrôler dans la liste déroulante.
    *   **Cas d'erreur :** Si la liste ne se charge pas (par exemple, en cas de problème de connexion API ou si aucun workflow n'est trouvé), un champ de saisie manuelle de l'ID du workflow apparaîtra. Vous pouvez trouver l'ID de votre workflow dans l'URL de l'éditeur n8n (ex: `https://your.n8n.instance/workflow/YOUR_WORKFLOW_ID`).
*   **Webhook URL (Optionnel) :**
    *   Si vous souhaitez pouvoir déclencher ce workflow n8n via la commande "Lancer" depuis Jeedom, vous devez renseigner l'URL de son webhook.
    *   Cette URL est générée par le nœud "Webhook" de votre workflow n8n. Copiez l'URL complète (ex: `https://your.n8n.instance/webhook/your-unique-path`).
    *   **Important :** Si ce champ est vide, la commande "Lancer" ne sera pas disponible pour cet équipement.
*   **Auto-actualisation :** (Si disponible) Permet de définir la fréquence à laquelle Jeedom doit rafraîchir l'état du workflow (actif/inactif) depuis n8n. Utilisez l'assistant cron pour définir une planification.

### Sauvegarde de l'Équipement

*   Une fois tous les paramètres configurés, cliquez sur le bouton **"Sauvegarder"** en haut de la page.
*   Jeedom enregistrera l'équipement et créera automatiquement les commandes associées (Activer, Désactiver, État, et Lancer si le webhook est configuré).

## 5. Commandes Disponibles

Après la sauvegarde de l'équipement, les commandes suivantes seront accessibles :

### Commandes d'Action

*   **Activer :** Envoie une requête à n8n pour activer le workflow associé à cet équipement. Le workflow commencera à s'exécuter selon sa configuration (par exemple, sur un déclencheur).
*   **Désactiver :** Envoie une requête à n8n pour désactiver le workflow. Le workflow cessera de s'exécuter et ne répondra plus à ses déclencheurs.
*   **Lancer :** (Visible uniquement si une "Webhook URL" est configurée pour l'équipement). Envoie une requête HTTP `POST` à l'URL du webhook spécifiée. Cela déclenchera l'exécution du workflow n8n comme si le webhook avait été appelé de l'extérieur.

### Commandes d'Information

*   **État :** Une commande d'information binaire (`0` ou `1`) qui indique le statut actuel du workflow dans n8n :
    *   `1` (Actif) : Le workflow est activé et prêt à s'exécuter.
    *   `0` (Inactif) : Le workflow est désactivé.
    *   Cette information est mise à jour lors de l'auto-actualisation ou après une action d'activation/désactivation.

## 6. Dépannage et Erreurs Courantes

Voici les problèmes les plus fréquemment rencontrés et comment les résoudre.

### Erreur HTTP 401 "unauthorized"

**Description :** Cette erreur indique un problème d'authentification lors de la tentative de connexion à l'API n8n.

**Causes possibles :**
*   Clé API manquante, incorrecte ou expirée.
*   L'API REST n'est pas activée dans n8n.
*   L'URL de l'instance n8n est incorrecte ou inaccessible.
*   Problème de permissions de la clé API.

**Solutions :**
1.  **Vérifiez la configuration globale du plugin :** Assurez-vous que l'**URL de l'instance n8n** et la **Clé API** sont correctement renseignées dans `Plugins > Gestion des plugins > n8n Connect > Configuration`.
2.  **Testez la connexion :** Utilisez le bouton **"Tester"** sur cette même page pour valider vos identifiants et l'accessibilité de l'instance.
3.  **Vérifiez n8n :**
    *   Dans votre instance n8n, allez dans `Settings > API` et assurez-vous que l'API REST est activée.
    *   Vérifiez que la clé API que vous utilisez est bien celle générée ici, qu'elle n'est pas expirée et qu'elle a les permissions nécessaires (au minimum `workflows.read`, `workflows.write`, `workflows.activate`, `workflows.deactivate`).
    *   Assurez-vous que votre instance n8n est démarrée et fonctionne correctement.
4.  **Connectivité réseau :** Vérifiez les pare-feu (sur Jeedom ou sur le serveur n8n) ou les problèmes de routage qui pourraient empêcher Jeedom de communiquer avec n8n sur le port spécifié.

### "URL de webhook manquante"

**Description :** Ce message apparaît lorsque vous tentez d'exécuter la commande "Lancer" pour un équipement, mais que le champ "Webhook URL" est vide dans sa configuration.

**Solution :**
*   Éditez l'équipement concerné (`Plugins > n8n Connect`, cliquez sur l'équipement).
*   Dans les paramètres spécifiques, renseignez l'URL complète du webhook de votre workflow n8n dans le champ **"Webhook URL"**.
*   Sauvegardez l'équipement. La commande "Lancer" devrait maintenant fonctionner.

### "Erreur webhook : The requested webhook ... is not registered"

**Description :** n8n indique qu'il ne trouve pas le webhook correspondant à l'URL ou que le workflow n'est pas actif.

**Causes possibles :**
*   Le workflow n'est pas activé dans n8n. Les webhooks de production ne fonctionnent que si le workflow est actif.
*   L'URL du webhook renseignée dans Jeedom est incorrecte (faute de frappe, ID de webhook erroné, etc.).
*   Le nœud "Webhook" dans votre workflow n8n n'est pas configuré pour accepter les requêtes `POST` (bien que ce soit le comportement par défaut).

**Solutions :**
1.  **Activez le workflow dans n8n :** Ouvrez votre workflow dans n8n et assurez-vous que le bouton `Active` (en haut à droite de l'éditeur) est bien sur `ON`.
2.  **Vérifiez l'URL du webhook :** Copiez l'URL du webhook directement depuis le nœud "Webhook" de votre workflow n8n et collez-la à nouveau dans le champ "Webhook URL" de l'équipement Jeedom pour éviter toute erreur.
3.  **Méthode HTTP :** Le plugin envoie une requête `POST`. Assurez-vous que votre nœud "Webhook" dans n8n est configuré pour accepter les requêtes `POST` (c'est le cas par défaut pour les webhooks de production).

### "Délai d'attente dépassé"

**Description :** Jeedom n'a pas reçu de réponse de n8n dans le temps imparti (30 secondes par défaut).

**Causes possibles :**
*   Votre instance n8n est arrêtée ou ne répond pas.
*   Problème de connectivité réseau entre Jeedom et n8n (pare-feu, routeur, etc.).
*   L'instance n8n est surchargée ou très lente à répondre.

**Solutions :**
1.  **Vérifiez l'état de n8n :** Assurez-vous que votre instance n8n est en cours d'exécution et accessible via un navigateur ou un `ping` depuis le serveur Jeedom.
2.  **Vérifiez la connectivité :** Testez la connexion réseau entre votre Jeedom et n8n. Par exemple, depuis le terminal de votre Jeedom, essayez `curl -v YOUR_N8N_URL`.
3.  **Performance de n8n :** Si n8n est surchargé, envisagez d'optimiser vos workflows ou d'augmenter les ressources allouées à votre instance n8n.

### "Réponse invalide de l'API n8n"

**Description :** L'API n8n a renvoyé une réponse qui n'a pas pu être interprétée correctement par le plugin (par exemple, ce n'était pas du JSON valide).

**Causes possibles :**
*   Problème interne à n8n renvoyant une réponse mal formée.
*   Un proxy ou un pare-feu modifie la réponse de l'API.

**Solutions :**
1.  **Vérifiez les logs n8n :** Consultez les logs de votre instance n8n pour voir si des erreurs sont signalées au moment de l'appel API.
2.  **Test manuel :** Essayez d'accéder manuellement à une API n8n (ex: `YOUR_N8N_URL/api/v1/workflows`) via un outil comme Postman ou `curl` pour voir la réponse brute.

### Logs de Diagnostic

Pour obtenir des informations plus détaillées sur les erreurs, consultez les logs du plugin n8n Connect :

1.  Dans Jeedom, allez dans `Outils > Logs`.
2.  Dans la liste déroulante, sélectionnez `n8nconnect`.
3.  Les logs affichent les communications entre Jeedom et n8n, y compris les requêtes envoyées et les réponses reçues, ce qui est crucial pour le dépannage.

## Notifications d'erreur de workflow

Pour recevoir des notifications d'erreur de vos workflows n8n directement dans Jeedom, vous pouvez configurer un "Workflow d'erreur" global dans n8n qui enverra une requête HTTP à Jeedom.

### Configuration dans n8n

1.  **Créez un nouveau workflow** dans n8n (ou utilisez un workflow existant dédié aux erreurs).
2.  Ajoutez un nœud **"Webhook"** comme déclencheur. Configurez-le pour écouter les requêtes `POST`.
3.  Ajoutez un nœud **"HTTP Request"** après le nœud "Webhook".
    *   **Method :** `POST`
    *   **URL :** `http://VOTRE_IP_JEEDOM/plugins/n8nconnect/core/ajax/n8nconnect.ajax.php?action=receiveErrorNotification`
        *   Remplacez `VOTRE_IP_JEEDOM` par l'adresse IP ou le nom de domaine de votre installation Jeedom.
    *   **Body Content Type :** `JSON`
    *   **JSON Body :** Vous pouvez envoyer n'importe quelle donnée JSON pertinente. Par exemple, pour envoyer les informations d'erreur du workflow qui a échoué, vous pouvez utiliser une expression comme :
        ```json
        {
          "workflowName": "{{ $json.workflow.name }}",
          "workflowId": "{{ $json.workflow.id }}",
          "executionId": "{{ $json.id }}",
          "error": "{{ $json.error.message }}",
          "stackTrace": "{{ $json.error.stack }}"
        }
        ```
        Ces variables (`$json.workflow.name`, etc.) sont disponibles dans le contexte d'un workflow d'erreur n8n.
4.  **Activez ce workflow** dans n8n.
5.  **Configurez ce workflow comme "Workflow d'erreur" global :**
    *   Dans n8n, allez dans **Settings > Workflow Error Handling**.
    *   Sélectionnez le workflow que vous venez de créer dans la liste déroulante "Error Workflow".

### Traitement dans Jeedom

Le plugin n8n Connect recevra ces notifications et les enregistrera dans les logs du plugin (`Outils > Logs > n8nconnect`). Vous pouvez ensuite utiliser les scénarios Jeedom pour analyser ces logs et déclencher des actions (notifications, alertes, etc.) en fonction du contenu des messages d'erreur.

## 7. Support

Si vous rencontrez des problèmes persistants après avoir suivi ce guide, veuillez collecter les informations suivantes avant de demander de l'aide :

*   Version exacte de Jeedom (visible dans `Réglages > Système > Configuration > Général`).
*   Version de votre instance n8n.
*   Messages d'erreur complets et exacts, copiés directement depuis les logs `n8nconnect` de Jeedom.
*   Capture d'écran de la page de configuration globale du plugin (masquez votre clé API).
*   Capture d'écran de la page de configuration de l'équipement Jeedom concerné.
*   Description détaillée des étapes pour reproduire le problème.
