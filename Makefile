# -------------------------------
# Makefile pour le projet Stubborn
# -------------------------------

# Déclare les cibles "virtuelles" qui ne correspondent pas à des fichiers
.PHONY: install start test reset

# --------------------------------------------------
# Installation complète du projet
# --------------------------------------------------
install:
	# Crée le fichier .env.local si il n'existe pas encore
	[ -f .env.local ] || cp .env.example .env.local
	# Installe les dépendances PHP via Composer
	composer install
	# Crée la base de données si elle n'existe pas
	php bin/console doctrine:database:create --if-not-exists
	# Exécute les migrations de la base
	php bin/console doctrine:migrations:migrate --no-interaction
	# Charge les fixtures pour l'environnement dev
	php bin/console doctrine:fixtures:load --group=dev --no-interaction

# --------------------------------------------------
# Lancement des tests PHPUnit
# --------------------------------------------------
test:
	# Les tests sont affichés avec couleur dans la console
	php bin/phpunit --colors=always

# --------------------------------------------------
# Démarrage de l'application
# --------------------------------------------------
start:
	# Lance d'abord les tests
	make test
	# Démarre le serveur Symfony en background
	symfony server:start -d

# --------------------------------------------------
# Réinitialisation complète de la base
# --------------------------------------------------
reset:
	# Supprime la base si elle existe
	php bin/console doctrine:database:drop --force --if-exists
	# Relance l'installation complète
	make install