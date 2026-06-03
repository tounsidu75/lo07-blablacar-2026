<?php

declare(strict_types=1);

final class DriverController extends BaseController
{
    private VehicleModel $vehicles;
    private CityModel $cities;
    private TripModel $trips;

    public function __construct()
    {
        parent::__construct();
        $this->vehicles = new VehicleModel();
        $this->cities = new CityModel();
        $this->trips = new TripModel();
    }

    public function vehicles(): void
    {
        $driver = $this->requireRole(['conducteur']);
        $this->render('driver/my_vehicles.php', [
            'title' => 'Liste de mes véhicules',
            'vehicles' => $this->vehicles->byOwner((int) $driver['id']),
        ]);
    }

    public function trips(): void
    {
        $driver = $this->requireRole(['conducteur']);
        $this->render('driver/my_trips.php', [
            'title' => 'Liste de tous mes trajets',
            'trips' => $this->trips->byDriver((int) $driver['id']),
        ]);
    }

    public function addTrip(): void
    {
        $driver = $this->requireRole(['conducteur']);
        $driverId = (int) $driver['id'];
        $cities = $this->cities->all();
        $vehicles = $this->vehicles->byOwner($driverId);
        $errors = [];
        $form = [
            'ville_depart' => '',
            'ville_arrivee' => '',
            'vehicule_id' => '',
            'prix' => '',
            'date_depart' => '',
            'heure_depart' => '',
        ];

        if ($this->isPost()) {
            foreach ($form as $key => $_) {
                $form[$key] = $this->postString($key);
            }

            $villeDepart = (int) $form['ville_depart'];
            $villeArrivee = (int) $form['ville_arrivee'];
            $vehicleId = (int) $form['vehicule_id'];
            $prix = $this->postFloat('prix');
            $vehicleIds = array_map('intval', array_column($vehicles, 'id'));
            $cityIds = array_map('intval', array_column($cities, 'id'));

            if (!in_array($villeDepart, $cityIds, true) || !in_array($villeArrivee, $cityIds, true)) {
                $errors[] = 'Les villes de départ et d’arrivée sont obligatoires.';
            }
            if ($villeDepart === $villeArrivee) {
                $errors[] = 'La ville de départ doit être différente de la ville d’arrivée.';
            }
            if (!in_array($vehicleId, $vehicleIds, true)) {
                $errors[] = 'Le véhicule doit vous appartenir.';
            }
            if ($prix <= 0) {
                $errors[] = 'Le prix doit être strictement positif.';
            }
            if ($form['date_depart'] === '' || $form['heure_depart'] === '') {
                $errors[] = 'La date et l’heure de départ sont obligatoires.';
            }

            if (!$errors) {
                $this->trips->add(
                    $villeDepart,
                    $villeArrivee,
                    $driverId,
                    $vehicleId,
                    $prix,
                    $form['date_depart'],
                    $form['heure_depart']
                );
                set_flash('success', 'Le trajet actif a été ajouté.');
                redirect_to('driver_trips');
            }
        }

        $this->render('driver/add_trip.php', [
            'title' => 'Ajout d’un trajet',
            'cities' => $cities,
            'vehicles' => $vehicles,
            'errors' => $errors,
            'form' => $form,
        ]);
    }

    public function passengers(): void
    {
        $driver = $this->requireRole(['conducteur']);
        $driverId = (int) $driver['id'];
        $activeTrips = $this->trips->activeByDriver($driverId);
        $selectedTrip = null;
        $passengers = null;
        $errors = [];

        if ($this->isPost()) {
            $tripId = $this->postInt('trajet_id');
            $selectedTrip = $this->trips->activeDriverTripLabel($tripId, $driverId);
            if (!$selectedTrip) {
                $errors[] = 'Veuillez choisir l’un de vos trajets actifs.';
            } else {
                $passengers = $this->trips->passengersForActiveDriverTrip($tripId, $driverId);
            }
        }

        $this->render('driver/passengers.php', [
            'title' => 'Liste des passagers d’un trajet actif',
            'activeTrips' => $activeTrips,
            'selectedTrip' => $selectedTrip,
            'passengers' => $passengers,
            'errors' => $errors,
        ]);
    }

    public function closeTrip(): void
    {
        $driver = $this->requireRole(['conducteur']);
        $driverId = (int) $driver['id'];
        $activeTrips = $this->trips->activeByDriver($driverId);
        $result = null;
        $closedTrip = null;
        $errors = [];

        if ($this->isPost()) {
            $tripId = $this->postInt('trajet_id');
            $closedTrip = $this->trips->activeDriverTripLabel($tripId, $driverId);
            if (!$closedTrip) {
                $errors[] = 'Veuillez choisir l’un de vos trajets actifs.';
            } else {
                $result = $this->trips->closeActiveTrip($tripId, $driverId);
                set_flash('success', 'Le trajet a été clôturé et les paiements ont été appliqués.');
                $activeTrips = $this->trips->activeByDriver($driverId);
            }
        }

        $this->render('driver/close_trip.php', [
            'title' => 'Clôturer un trajet actif',
            'activeTrips' => $activeTrips,
            'closedTrip' => $closedTrip,
            'result' => $result,
            'errors' => $errors,
        ]);
    }
}
