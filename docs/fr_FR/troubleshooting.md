# Guide de dépannage - Plugin n8nconnect

## Erreur HTTP 401 "unauthorized"

### Description du problème
L'erreur HTTP 401 "unauthorized" apparaît lors de la création d'équipements dans le plugin n8nconnect. Cette erreur indique un problème d'authentification avec l'API n8n.

### Causes possibles

1. **Clé API manquante ou incorrecte**
   - La clé API n'est pas configurée dans le plugin
   - La clé API est incorrecte ou expirée
   - La clé API n'a pas les bonnes permissions

2. **URL de l'instance n8n incorrecte**
   - L'URL n'est pas accessible depuis Jeedom
   - L'URL ne pointe pas vers la bonne instance n8n
   - Problème de résolution DNS

3. **Problème de configuration n8n**
   - API REST non activée dans n8n
   - Instance n8n non accessible
   - Problème de certificat SSL

### Solutions

#### 1. Vérifier la configuration du plugin

1. Allez dans **Plugins > Gestion des plugins > n8nconnect > Configuration**
2. Vérifiez que tous les champs sont correctement remplis :
   - **URL de l'instance n8n** : Doit être l'adresse complète (ex: `https://mon.n8n.local`)
   - **Clé API** : Doit être la clé API valide de votre instance n8n

#### 2. Tester la connexion

1. Dans la configuration du plugin, cliquez sur le bouton **"Tester"**
2. Vérifiez que le test de connexion réussit
3. Si le test échoue, notez le message d'erreur exact

#### 3. Vérifier la configuration de n8n

1. **Vérifiez que l'API REST est activée** :
   - Dans n8n, allez dans **Settings > API**
   - Assurez-vous que l'API REST est activée
   - Notez la clé API générée

2. **Vérifiez l'accessibilité de l'instance** :
   - Testez l'URL depuis un navigateur
   - Vérifiez que l'instance n8n est démarrée
   - Vérifiez les logs n8n pour d'éventuelles erreurs

3. **Vérifiez les permissions de la clé API** :
   - Assurez-vous que la clé API a les permissions nécessaires
   - Vérifiez que la clé API n'est pas expirée

#### 4. Vérifier la connectivité réseau

1. **Testez la connectivité depuis Jeedom** :
   ```bash
   # Depuis le serveur Jeedom
   curl -H "X-N8N-API-KEY: VOTRE_CLE_API" https://VOTRE_INSTANCE_N8N/api/v1/workflows
   ```

2. **Vérifiez les pare-feu** :
   - Assurez-vous que le port de n8n est accessible depuis Jeedom
   - Vérifiez les règles de pare-feu

#### 5. Solution temporaire

Si le problème persiste, vous pouvez créer un équipement en saisissant manuellement l'ID du workflow :

1. Cliquez sur **"Ajouter"** pour créer un nouvel équipement
2. Le plugin affichera automatiquement un champ de saisie manuelle
3. Saisissez l'ID du workflow n8n (visible dans l'interface n8n)
4. Sauvegardez l'équipement

### Logs de diagnostic

Pour diagnostiquer le problème, consultez les logs du plugin :

1. Allez dans **Outils > Logs**
2. Sélectionnez le plugin **n8nconnect**
3. Regardez les messages d'erreur récents

Les logs contiennent des informations détaillées sur :
- Les tentatives de connexion à l'API n8n
- Les erreurs d'authentification
- Les problèmes de configuration

### Messages d'erreur courants

- **"Configuration n8n incomplète"** : URL ou clé API manquante
- **"Erreur d'authentification (401)"** : Clé API incorrecte
- **"URL de l'instance n8n incorrecte"** : Problème de connectivité ou URL erronée
- **"Délai d'attente dépassé"** : Instance n8n non accessible ou lente

### Support

Si le problème persiste après avoir suivi ces étapes :

1. Consultez les logs du plugin pour plus de détails
2. Vérifiez la documentation officielle de n8n
3. Contactez le support avec les informations suivantes :
   - Version de Jeedom
   - Version de n8n
   - Messages d'erreur exacts
   - Configuration du plugin (sans la clé API) 