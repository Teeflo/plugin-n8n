# TEST DU PLUGIN N8NCONNECT

## ÉTAPES DE VÉRIFICATION

### 1. INSTALLATION
- [ ] Installer le plugin via l'interface Jeedom
- [ ] Vérifier qu'aucune erreur n'apparaît lors de l'installation
- [ ] Vérifier que le plugin apparaît dans la liste des plugins

### 2. CONFIGURATION
- [ ] Aller dans la configuration du plugin
- [ ] Saisir l'URL de l'instance n8n (ex: https://mon.n8n.local)
- [ ] Saisir la clé API n8n
- [ ] Tester la connexion avec le bouton "Tester"
- [ ] Vérifier que le test de connexion fonctionne

### 3. CRÉATION D'ÉQUIPEMENT
- [ ] Aller dans le plugin n8nconnect
- [ ] Cliquer sur "Ajouter"
- [ ] Vérifier qu'un nouvel équipement est créé
- [ ] Vérifier que la liste des workflows se charge automatiquement
- [ ] Sélectionner un workflow dans la liste
- [ ] Sauvegarder l'équipement
- [ ] Vérifier que les commandes sont créées automatiquement

### 4. FONCTIONNALITÉS DES COMMANDES
- [ ] Vérifier que les commandes suivantes sont créées :
  - [ ] Lancer (action)
  - [ ] Activer (action)
  - [ ] Désactiver (action)
  - [ ] État (info)
- [ ] Tester la commande "Lancer"
- [ ] Vérifier que le workflow se lance dans n8n
- [ ] Tester la commande "Activer"
- [ ] Vérifier que le workflow s'active dans n8n
- [ ] Tester la commande "Désactiver"
- [ ] Vérifier que le workflow se désactive dans n8n

### 5. GESTION DES ÉQUIPEMENTS
- [ ] Modifier le nom d'un équipement
- [ ] Sauvegarder les modifications
- [ ] Vérifier que les modifications sont prises en compte
- [ ] Supprimer un équipement
- [ ] Vérifier que l'équipement est bien supprimé

### 6. BOUTON CONFIGURATION
- [ ] Cliquer sur le bouton "Configuration"
- [ ] Vérifier que la page de configuration s'ouvre

### 7. GESTION DES ERREURS
- [ ] Tester avec une URL n8n incorrecte
- [ ] Vérifier que les messages d'erreur sont appropriés
- [ ] Tester avec une clé API incorrecte
- [ ] Vérifier que les messages d'erreur sont appropriés

## PROBLÈMES CORRIGÉS

### ✅ CONFLITS GIT RÉSOLUS
- Fichier `core/ajax/n8nconnect.ajax.php` : Suppression des marqueurs de conflit
- Fichier `desktop/js/n8nconnect.js` : Suppression des marqueurs de conflit

### ✅ FONCTION MANQUANTE CORRIGÉE
- Fichier `plugin_info/configuration.php` : Remplacement de `handleAjaxError` par une gestion d'erreur appropriée

### ✅ AMÉLIORATIONS APPORTÉES
- Gestion d'erreurs plus détaillée dans les appels AJAX
- Messages d'erreur plus informatifs pour l'utilisateur
- Logs de debug pour faciliter le diagnostic
- Rechargement automatique des workflows lors de la création/ouverture d'équipements

## RÉSULTAT ATTENDU

Le plugin doit maintenant fonctionner correctement avec :
- Création d'équipements sans erreur
- Chargement automatique de la liste des workflows
- Fonctionnement correct du bouton "Configuration"
- Gestion appropriée des erreurs
- Interface utilisateur réactive sans rechargement de page inutile 