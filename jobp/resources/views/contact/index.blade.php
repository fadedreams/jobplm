<?php

foreach ($contacts as $contact) {
    echo $name = $contact->name;
}
?>

@foreach($contacts as $contact)
{{ $name = $contact->name }}
@endforeach
