# Test des Corrections du Plugin n8nconnect

## Corrections Apportées

### 1. Correction de la méthode postSave() dans core/class/n8nconnect.class.php
- **Problème** : Les valeurs `isVisible` et `isHistorized` étaient définies avec des comparaisons booléennes
- **Solution** : Utilisation d'entiers (1 ou 0) pour ces valeurs
- **Impact** : Les commandes sont maintenant créées correctement

### 2. Correction de l'URL de documentation dans plugin_info/info.json
- **Problème** : L'URL de documentation pointait vers `plugin-n8n` au lieu de `plugin-n8nconnect`
- **Solution** : Correction de l'URL pour correspondre à l'ID du plugin
- **Impact** : Documentation accessible correctement

### 3. Amélioration de la gestion des workflows dans desktop/js/n8nconnect.js
- **Problème** : Chargement automatique des workflows même sans configuration
- **Solution** : Vérification de la configuration avant chargement
- **Impact** : Évite les erreurs inutiles et améliore l'expérience utilisateur

### 4. Amélioration de la validation dans core/ajax/n8nconnect.ajax.php
- **Problème** : Pas de validation du nom d'équipement
- **Solution** : Ajout de validation obligatoire
- **Impact** : Évite la création d'équipements sans nom

### 5. Amélioration de la gestion d'erreurs dans core/class/n8nconnect.class.php
- **Problème** : Pas de gestion d'erreurs dans la méthode cron()
- **Solution** : Ajout de try-catch pour chaque équipement
- **Impact** : Un équipement défaillant n'empêche plus les autres de fonctionner

## Instructions de Test

### Test 1 : Création d'équipement
1. Aller dans Plugins > Communication > n8n Connect
2. Cliquer sur "Ajouter"
3. Vérifier que l'équipement se crée sans erreur
4. Vérifier que les commandes sont créées automatiquement

### Test 2 : Configuration du plugin
1. Aller dans Plugins > Communication > n8n Connect > Configuration
2. Remplir l'URL de l'instance n8n (ex: https://mon.n8n.local)
3. Remplir la clé API
4. Cliquer sur "Tester"
5. Vérifier que le test de connexion fonctionne

### Test 3 : Sélection de workflow
1. Dans un équipement, cliquer sur le bouton de rafraîchissement des workflows
2. Vérifier que la liste des workflows s'affiche
3. Sélectionner un workflow
4. Sauvegarder l'équipement

### Test 4 : Test des commandes
1. Dans l'onglet Commandes, tester chaque commande
2. Vérifier que les commandes "Lancer", "Activer", "Désactiver" fonctionnent
3. Vérifier que la commande "État" affiche le bon statut

### Test 5 : Test du cron
1. Configurer un cron d'auto-actualisation (ex: */5 * * * *)
2. Attendre quelques minutes
3. Vérifier que l'état des workflows se met à jour automatiquement

## Vérification des Logs

Pour vérifier que tout fonctionne correctement, consulter les logs du plugin :
- Aller dans Outils > Logs
- Sélectionner le plugin "n8nconnect"
- Vérifier qu'il n'y a pas d'erreurs critiques

## Points d'Attention

1. **Configuration requise** : L'URL et la clé API doivent être configurées avant de pouvoir utiliser le plugin
2. **Permissions** : La clé API n8n doit avoir les permissions suffisantes pour lister et contrôler les workflows
3. **Connectivité** : L'instance n8n doit être accessible depuis Jeedom

## Résolution des Problèmes Courants

### Erreur 401 (Non autorisé)
- Vérifier que la clé API est correcte
- Vérifier que la clé API a les bonnes permissions

### Erreur 404 (Non trouvé)
- Vérifier que l'URL de l'instance n8n est correcte
- Vérifier que l'instance n8n est accessible

### Timeout
- Vérifier la connectivité réseau
- Augmenter les timeouts si nécessaire

### Aucun workflow trouvé
- Vérifier que l'instance n8n contient des workflows
- Vérifier que la clé API a accès aux workflows 