# BoxToGo
Plateforme de mise en relation pour la location d'espaces de rangement inutilisés. Facilitant le partage, l'optimisation des espaces, et la création d'une communauté autour du stockage.
1. Commencez par ouvrir votre terminal ou votre invite de commande sur votre ordinateur et placez-vous dans le répertoire où vous souhaitez cloner le projet.
2. Utilisez la commande suivante pour cloner le projet depuis GitHub : **`git clone https://github.com/ParasmoM/BoxToGo.git`**
3. Une fois le clonage terminé, accédez au dossier 'BoxToGo' en utilisant la commande : **`cd BoxToGo`**
4. Ensuite, ouvrez le fichier **`.env`** et configurez la variable **`DATABASE_URL`** en spécifiant le type de base de données (par exemple, 'mysql'), vos identifiants de base de données et choisissez un nom pour votre base de données.
5. Assurez-vous que votre serveur de base de données (par exemple, MySQL) est en cours d'exécution et que le service est actif.
6. Pour créer la base de données, utilisez la commande suivante : **`php bin/console doctrine:database:create`**
7. Évitez les conflits de schéma en exécutant la commande : **`php bin/console doctrine:schema:update --force`**
8. Attention : pour créer un compte administrateur permettant l'accès à l'interface admin, exécutez la commande suivante : **`php bin/console doctrine:fixtures:load`** 

Vous pourrez ensuite utiliser les informations de connexion suivantes :

- Adresse e-mail : admin@admin.com
- Mot de passe : @1Admin
