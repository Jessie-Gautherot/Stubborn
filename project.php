<?php
// -------------------------------
// project.php - Script projet Stubborn
// -------------------------------

// Fonction utilitaire pour exécuter une commande système
function run($cmd, $haltOnError = true)
{
    echo "\n==> $cmd\n"; // Affiche la commande en cours

    passthru($cmd, $ret); // Exécute la commande dans le terminal

    // Si erreur ET qu'on veut stopper → on arrête tout
    if ($haltOnError && $ret !== 0) {
        echo "\n Erreur lors de : $cmd\n";
        exit($ret);
    }
}

// Vérifie que le fichier .env.local existe
function checkEnv()
{
    if (!file_exists('.env.local')) {

        // Message clair pour le développeur / correcteur
        echo "\n Fichier .env.local manquant\n";

        echo "👉 Copiez .env.example vers .env.local et configurez-le\n";
        echo "👉 Exemple (bash) : cp .env.example .env.local\n\n";

        exit(1);
    }
}

// Récupération de l'action (install / start)
$action = $argv[1] ?? null;

if (!$action) {
    echo "Usage: php project.php [install|start]\n";
    exit(1);
}

switch ($action) {

    // INSTALLATION
    case 'install':

        echo "\n Installation du projet...\n";

        checkEnv();

        // 1. Installer les dépendances PHP
        run('composer install');

        // 2. Créer la base de données si elle n'existe pas
        run('php bin/console doctrine:database:create --if-not-exists');

        // 3. Exécuter les migrations
        run('php bin/console doctrine:migrations:migrate --no-interaction');

        // 4. Charger les données
        if (is_dir('src/DataFixtures')) {
            run('php bin/console doctrine:fixtures:load --no-interaction');
        }

        echo "\n Installation terminée !\n";
        echo "Lancez : php project.php start\n";
        break;

    // DÉMARRAGE
    case 'start':

        echo "\n Démarrage du projet...\n";

        checkEnv();

        // 1. Lancer les tests (sans bloquer si échec)
        echo "\n Exécution des tests...\n";
        run('php bin/phpunit --colors=always', false);

        // 2. Lancer le serveur Symfony
        echo "\n Lancement du serveur Symfony...\n";
        run('symfony server:start -d');

        echo "\n Application disponible sur : http://localhost:8000\n";
        break;


    default:
        echo "Action inconnue : $action\n";
        echo "Usage: php project.php [install|start]\n";
        exit(1);
}