# n8n Connect

Ce plugin permet de relier Jeedom à une instance **n8n** afin de contrôler et surveiller vos workflows d'automatisation.

## Configuration

Renseignez dans la page de configuration l'URL de votre instance n8n ainsi que la clé API dédiée. Vous pourrez ensuite créer un équipement par workflow à piloter.

Lors de la création d'un équipement, utilisez la liste déroulante pour sélectionner le workflow souhaité. Le plugin récupère automatiquement la liste depuis l'API n8n.
Si la liste ne peut pas être chargée, un champ permet de saisir manuellement l'identifiant du workflow.

## Commandes disponibles

- **Lancer** : exécute le workflow immédiatement.
- **Activer/Désactiver** : change l'état du workflow dans n8n.
- **État** : indique si le workflow est actif ou non dans n8n.

Ce plugin est proposé comme base pour développer des intégrations plus avancées entre Jeedom et n8n.
