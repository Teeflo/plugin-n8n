# Changelog n8n Connect

## 0.1.0
- Première version du plugin n8n Connect pour Jeedom.
  Ce plugin permet de piloter et de superviser vos workflows n8n directement depuis votre interface domotique Jeedom. Il offre une intégration simple et efficace pour lancer des workflows, les activer/désactiver, et vérifier leur état.

  Fonctionnalités incluses :
  - Configuration d'instance n8n : Connectez facilement votre Jeedom à votre instance n8n via son URL et une clé API.
  - Gestion des workflows : Créez des équipements Jeedom pour chaque workflow n8n que vous souhaitez contrôler.
  - Commandes d'action :
    - Activer/Désactiver : Changez l'état d'exécution de vos workflows n8n.
    - Lancer (via Webhook) : Déclenchez un workflow n8n en envoyant une requête à son URL de webhook configurée.
  - Commande d'information :
    - État : Obtenez le statut (actif/inactif) de votre workflow n8n.
  - Notifications d'erreur de workflow : Recevez des notifications dans Jeedom lorsqu'un workflow n8n échoue.
  - Sélection simplifiée : Choisissez vos workflows n8n via une liste déroulante ou saisissez manuellement leur ID.
  - Journalisation détaillée : Des logs précis pour faciliter le diagnostic en cas de problème.

## 0.1.1
- Correction de la description italienne dans info.json.
- Correction d'une erreur de syntaxe JSON dans info.json (virgule en trop).
