# n8n Connect pour Jeedom

Ce plugin permet de piloter des workflows **n8n** directement depuis Jeedom. Il offre un moyen simple de lancer ou d'activer/désactiver des workflows et de centraliser leur supervision depuis l'interface domotique.

## Fonctionnalités

- Configuration d'une instance n8n (URL et clé API).
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
5. Testez la connexion avec le bouton **"Tester"**
6. Notez l'URL d'API entrante affichée. Elle sera utilisée dans vos workflows n8n pour envoyer des données vers Jeedom.

### Ajouter un équipement

1. Dans le plugin, cliquez sur **Ajouter** puis rafraîchissez la liste des workflows.
2. Sélectionnez le workflow souhaité et sauvegardez.
3. Une commande **Exécuter le workflow** est créée automatiquement. Ajoutez vos commandes d'information selon vos besoins.

### Exemple d'appel depuis n8n

Dans votre workflow n8n, utilisez un noeud **HTTP Request** configuré en `POST` vers l'URL affichée précédemment. Renseignez les paramètres `eqLogic_id`, `cmd_name` et `value` afin de mettre à jour une commande info de Jeedom.

Exemple de corps JSON envoyé :

```json
{
  "eqLogic_id": "12",
  "cmd_name": "temperature",
  "value": "23"
}
```

## Dépannage

### Erreur HTTP 401 "unauthorized"

Si vous rencontrez cette erreur lors de la création d'équipements :

1. **Vérifiez votre configuration** :
   - URL de l'instance n8n correcte et accessible
   - Clé API valide et non expirée

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
