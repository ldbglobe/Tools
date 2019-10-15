<?php
// uncomment the next line to add a new customer entry on each call
$contact = Contact::create();
$contact->name = "Random name ".uniqid();
$contact->save();

$contacts = Contact::factory()->find_many();
foreach($contacts as $c)
	print_r($c->as_array());