# n8n Connect pour Jeedom

Ce plugin permet de piloter des workflows **n8n** directement depuis Jeedom. Il offre un moyen simple de lancer ou d'activer/désactiver des workflows et de centraliser leur supervision depuis l'interface domotique.

## Fonctionnalités

- Configuration d'une instance n8n (URL, clé API et identifiants Basic Auth facultatifs).
- Création d'équipements représentant chaque workflow à contrôler.
- Commandes pour lancer, activer ou désactiver un workflow.

Ce dépôt est basé sur le template officiel de plugin Jeedom et fournit un point de départ pour développer des interactions avancées entre Jeedom et n8n.

## Installation et configuration

### Prérequis

1. Une instance n8n fonctionnelle avec l'API REST activée
2. Une clé API n8n valide
3. Jeedom version 4.0 ou supérieure

### Configuration

1. Installez le plugin depuis le Market Jeedom
2. Activez le plugin
3. Allez dans **Plugins > Gestion des plugins > n8nconnect > Configuration**
4. Configurez :
   - **URL de l'instance n8n** (ex: `https://mon.n8n.local`)
   - **Clé API** (générée dans n8n > Settings > API)
   - **Identifiant et mot de passe Basic Auth** (optionnel)
5. Testez la connexion avec le bouton **"Tester"**

## Dépannage

### Erreur HTTP 401 "unauthorized"

Si vous rencontrez cette erreur lors de la création d'équipements :

1. **Vérifiez votre configuration** :
   - URL de l'instance n8n correcte et accessible
   - Clé API valide et non expirée
   - Identifiants Basic Auth corrects (si configurés)

2. **Testez la connectivité** :
   - Utilisez le bouton "Tester" dans la configuration
   - Vérifiez que l'instance n8n est démarrée
   - Testez l'URL depuis un navigateur

3. **Consultez les logs** :
   - Allez dans **Outils > Logs**
   - Sélectionnez le plugin **n8nconnect**
   - Recherchez les messages d'erreur détaillés

4. **Solution temporaire** :
   - Créez un équipement en saisissant manuellement l'ID du workflow
   - Le plugin affichera automatiquement un champ de saisie manuelle

Pour plus de détails, consultez le [guide de dépannage complet](docs/fr_FR/troubleshooting.md).

## Support

En cas de problème :
1. Consultez les logs du plugin
2. Vérifiez la documentation de n8n
3. Contactez le support avec les informations de diagnostic
