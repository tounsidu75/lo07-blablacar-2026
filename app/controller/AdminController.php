<?php

declare(strict_types=1);

final class AdminController extends BaseController
{
    // Controleur des fonctions A1 a A7 reservees a l'administrateur.
    private VehicleModel $vehicles;
    private CityModel $cities;

    public function __construct()
    {
        parent::__construct();
        $this->vehicles = new VehicleModel();
        $this->cities = new CityModel();
    }

    public function users(): void
    {
        // A1 : affiche tous les utilisateurs.
        $this->requireRole(['administrateur']);
        $this->render('admin/users.php', [
            'title' => 'Liste des utilisateurs',
            'users' => $this->users->all(),
        ]);
    }

    public function addDriver(): void
    {
        // A2 : reutilise le formulaire utilisateur avec le role conducteur.
        $this->handleAddUser('conducteur', 'Formulaire de création d’un nouveau conducteur');
    }

    public function addPassenger(): void
    {
        // A3 : reutilise le meme formulaire avec le role passager.
        $this->handleAddUser('passager', 'Formulaire de création d’un nouveau passager');
    }

    public function vehicles(): void
    {
        // A4 : liste les vehicules sans afficher les cles primaires.
        $this->requireRole(['administrateur']);
        $this->render('admin/vehicles.php', [
            'title' => 'Liste des véhicules',
            'vehicles' => $this->vehicles->allWithOwner(),
        ]);
    }

    public function addVehicle(): void
    {
        // A5 : ajoute un vehicule rattache a un conducteur existant.
        $this->requireRole(['administrateur']);
        $drivers = $this->users->byRole('conducteur');
        $errors = [];
        $form = [
            'marque' => '',
            'modele' => '',
            'annee' => '',
            'immatriculation' => '',
            'proprietaire_id' => '',
        ];

        if ($this->isPost()) {
            // On recharge le formulaire pour conserver les valeurs en cas d'erreur.
            foreach ($form as $key => $_) {
                $form[$key] = $this->postString($key);
            }

            $annee = (int) $form['annee'];
            $ownerId = (int) $form['proprietaire_id'];

            if ($form['marque'] === '' || $form['modele'] === '' || $form['immatriculation'] === '') {
                $errors[] = 'La marque, le modèle et l’immatriculation sont obligatoires.';
            }
            if ($annee < 1900 || $annee > 2100) {
                $errors[] = 'L’année du véhicule est invalide.';
            }
            if (!in_array($ownerId, array_map('intval', array_column($drivers, 'id')), true)) {
                $errors[] = 'Le propriétaire doit être un conducteur existant.';
            }

            if (!$errors) {
                $this->vehicles->add($form['marque'], $form['modele'], $annee, $form['immatriculation'], $ownerId);
                set_flash('success', 'Le véhicule a été ajouté.');
                redirect_to('admin_vehicles');
            }
        }

        $this->render('admin/add_vehicle.php', [
            'title' => 'Ajout d’un véhicule',
            'drivers' => $drivers,
            'errors' => $errors,
            'form' => $form,
        ]);
    }

    public function cities(): void
    {
        // A6 : affiche le referentiel des villes.
        $this->requireRole(['administrateur']);
        $this->render('admin/cities.php', [
            'title' => 'Liste des villes',
            'cities' => $this->cities->all(),
        ]);
    }

    public function addCity(): void
    {
        // A7 : ajoute une ville utilisable pour les futurs trajets.
        $this->requireRole(['administrateur']);
        $errors = [];
        $nom = '';

        if ($this->isPost()) {
            $nom = $this->postString('nom');
            if ($nom === '') {
                $errors[] = 'Le nom de la ville est obligatoire.';
            }

            if (!$errors) {
                try {
                    $this->cities->add($nom);
                    set_flash('success', 'La ville a été ajoutée.');
                    redirect_to('admin_cities');
                } catch (PDOException $exception) {
                    $errors[] = 'Impossible d’ajouter cette ville. Elle existe peut-être déjà.';
                }
            }
        }

        $this->render('admin/add_city.php', [
            'title' => 'Ajout d’une ville',
            'errors' => $errors,
            'nom' => $nom,
        ]);
    }

    private function handleAddUser(string $role, string $heading): void
    {
        // Methode commune pour eviter de dupliquer ajout conducteur/passager.
        $this->requireRole(['administrateur']);
        $errors = [];
        $form = [
            'nom' => '',
            'prenom' => '',
            'login' => '',
            'password' => 'secret',
            'solde' => '100.00',
        ];

        if ($this->isPost()) {
            foreach ($form as $key => $_) {
                $form[$key] = $this->postString($key);
            }

            $solde = $this->postFloat('solde');
            if ($form['nom'] === '' || $form['prenom'] === '' || $form['login'] === '' || $form['password'] === '') {
                $errors[] = 'Le nom, le prénom, le login et le mot de passe sont obligatoires.';
            }
            if ($solde < 0) {
                $errors[] = 'Le solde initial doit être positif ou nul.';
            }
            if ($form['login'] !== '' && $this->users->loginExists($form['login'])) {
                $errors[] = 'Ce login est déjà utilisé.';
            }

            if (!$errors) {
                $this->users->add($role, $form['nom'], $form['prenom'], $form['login'], $form['password'], $solde);
                set_flash('success', 'Le ' . $role . ' a été ajouté.');
                redirect_to('admin_users');
            }
        }

        $this->render('admin/add_user.php', [
            'title' => $heading,
            'heading' => $heading,
            'role' => $role,
            'errors' => $errors,
            'form' => $form,
        ]);
    }
}
