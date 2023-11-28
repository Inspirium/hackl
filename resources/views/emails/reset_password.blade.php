<x-mail::message :club="$club" title="Obnovite lozinku">
    Poštovani,
    zatražili ste promjenu lozinku. Kliknite na donji link kako biste obnovili lozinku.
    <x-mail::button :url="$path">Obnovi lozinku</x-mail::button>
        Ako niste zatražili promjenu lozinke, zanemarite ovu poruku.
</x-mail::message>
