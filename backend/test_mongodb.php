<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->covoiturage;
$collection = $db->suggestions;

// Ajouter un document test
$collection->insertOne(['from' => 'Paris', 'to' => 'Lyon', 'date' => '2025-11-20']);

echo "Insertion OK !\n";

// Lire les documents
$docs = $collection->find()->toArray();
print_r($docs);
