# n8n Connect pour Jeedom

Ce plugin permet de piloter et de superviser vos workflows **n8n** directement depuis votre interface domotique Jeedom. Il offre une intégration simple et efficace pour lancer des workflows, les activer/désactiver, et vérifier leur état.

## Fonctionnalités

*   **Configuration d'instance n8n :** Connectez facilement votre Jeedom à votre instance n8n via son URL et une clé API.
*   **Gestion des workflows :** Créez des équipements Jeedom pour chaque workflow n8n que vous souhaitez contrôler.
*   **Commandes d'action :**
    *   **Activer/Désactiver :** Changez l'état d'exécution de vos workflows n8n.
    *   **Lancer (via Webhook) :** Déclenchez un workflow n8n en envoyant une requête à son URL de webhook configurée. Cette commande apparaît uniquement si un webhook est renseigné pour l'équipement.
*   **Commande d'information :**
    *   **État :** Obtenez le statut (actif/inactif) de votre workflow n8n.
*   **Sélection simplifiée :** Choisissez vos workflows n8n via une liste déroulante ou saisissez manuellement leur ID.
*   **Journalisation détaillée :** Des logs précis pour faciliter le diagnostic en cas de problème.

## Prérequis

1.  Une instance [n8n](https://n8n.io/) fonctionnelle et accessible depuis votre Jeedom.
2.  L'API REST de n8n doit être activée sur votre instance.
3.  Une clé API n8n valide avec les permissions nécessaires pour gérer les workflows.
4.  Jeedom version 4.2.0 ou supérieure.
5.  L'extension PHP `cURL` doit être installée et activée sur votre système Jeedom.

## Installation

1.  Installez le plugin "n8n Connect" directement depuis le Market Jeedom.
2.  Après l'installation, activez le plugin dans **Plugins > Gestion des plugins**.

## Configuration

### 1. Configuration Globale du Plugin

Accédez à la configuration globale du plugin via **Plugins > Gestion des plugins > n8n Connect > Configuration**.

*   **URL de l'instance n8n :** Saisissez l'adresse complète de votre instance n8n (ex: `https://mon.n8n.local` ou `http://192.168.1.100:5678`).
*   **Clé API :** Entrez votre clé API n8n, générée dans n8n (**Settings > API**).
*   Cliquez sur le bouton **"Tester"** pour vérifier la connexion à votre instance n8n.

### 2. Configuration des Équipements (Workflows)

Pour chaque workflow n8n que vous souhaitez contrôler :

1.  Allez dans **Plugins > n8n Connect**.
2.  Cliquez sur **"Ajouter"** pour créer un nouvel équipement.
3.  **Nom de l'équipement :** Donnez un nom significatif à votre équipement Jeedom (ex: "Workflow Lumières Salon").
4.  **Workflow :**
    *   Cliquez sur le bouton de rafraîchissement (<i class="fas fa-sync"></i>) pour charger la liste de vos workflows n8n disponibles.
    *   Sélectionnez le workflow désiré dans la liste déroulante.
    *   Si la liste ne se charge pas (par exemple, en cas de problème de connexion API), un champ de saisie manuelle de l'ID du workflow apparaîtra. Vous pouvez trouver l'ID de votre workflow dans l'interface n8n.
5.  **Webhook URL (Optionnel) :** Si vous souhaitez déclencher ce workflow via une commande "Lancer", collez ici l'URL du webhook de votre workflow n8n. Cette URL est fournie par le nœud "Webhook" de votre workflow n8n.
6.  Configurez les **Paramètres généraux** (Objet parent, Catégorie, Activer/Visible) selon vos besoins.
7.  Cliquez sur **"Sauvegarder"**. Les commandes "Activer", "Désactiver" et "État" seront créées automatiquement. La commande "Lancer" sera ajoutée si une URL de webhook a été renseignée.

## Commandes Disponibles

Une fois l'équipement configuré, les commandes suivantes seront disponibles :

*   **Activer :** Active le workflow correspondant dans n8n.
*   **Désactiver :** Désactive le workflow correspondant dans n8n.
*   **Lancer :** Envoie une requête HTTP POST à l'URL du webhook configurée pour le workflow. Cette commande est visible uniquement si une "Webhook URL" est renseignée dans la configuration de l'équipement.
*   **État :** Commande d'information binaire indiquant si le workflow est actif (1) ou inactif (0) dans n8n.

## Dépannage

### Erreur HTTP 401 "unauthorized"

Cette erreur indique un problème d'authentification avec l'API n8n.

*   **Vérifiez votre configuration :** Assurez-vous que l'**URL de l'instance n8n** et la **Clé API** sont correctement renseignées dans la configuration globale du plugin.
*   **Testez la connexion :** Utilisez le bouton **"Tester"** dans la configuration globale pour valider vos identifiants.
*   **Vérifiez n8n :**
    *   Assurez-vous que l'API REST est activée dans n8n (**Settings > API**).
    *   Vérifiez que votre clé API n8n est valide et non expirée, et qu'elle possède les permissions nécessaires.
    *   Assurez-vous que votre instance n8n est démarrée et accessible depuis Jeedom.
*   **Connectivité réseau :** Vérifiez les pare-feu ou les problèmes de réseau qui pourraient empêcher Jeedom de communiquer avec n8n.

### Messages d'erreur courants

*   **"URL de webhook manquante" :** La commande "Lancer" a été exécutée, mais aucune URL de webhook n'est configurée pour cet équipement.
*   **"Erreur webhook : The requested webhook ... is not registered" :** Le workflow n'est pas actif dans n8n, ou l'URL du webhook est incorrecte. Assurez-vous que le workflow est activé dans n8n et que l'URL est exacte.
*   **"Délai d'attente dépassé" :** Jeedom n'a pas pu joindre votre instance n8n dans le temps imparti. Vérifiez que n8n est en ligne et accessible.
*   **"Réponse invalide de l'API n8n" :** L'API n8n a renvoyé une réponse inattendue.

### Logs de diagnostic

Pour des informations plus détaillées, consultez les logs du plugin :
1.  Allez dans **Outils > Logs**.
2.  Sélectionnez le plugin **n8nconnect**.
3.  Recherchez les messages d'erreur récents pour identifier la cause du problème.

## Support

En cas de problème persistant, veuillez fournir les informations suivantes :
*   Version de Jeedom.
*   Version de n8n.
*   Messages d'erreur exacts (copiés depuis les logs).
*   Capture d'écran de votre configuration du plugin (masquez votre clé API).
*   Capture d'écran de la configuration de l'équipement concerné.