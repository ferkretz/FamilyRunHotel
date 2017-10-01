<?php

return [
    // String validator
    "Invalid type given. String expected" => "Érvénytelen típus. Karakterlánc a várt típus",
    "The input is less than %min% characters long" => "Ez a mező nem tartalmazhat %min% karakternél kevesebbet",
    "The input is more than %max% characters long" => "Ez a mező nem tartalmazhat %min% karakternél többet",
    // Hostname validator
    "The input appears to be a DNS hostname but the given punycode notation cannot be decoded" => "Ez a mező DNS kiszolgáló névnek tűnik, de a megadott punycode jelölés nem visszakódolható",
    "The input appears to be a DNS hostname but contains a dash in an invalid position" => "Ez a mező érvényes DNS kiszolgáló névnek tűnik, de érvénytelen pozícióban tartalmaz kötőjelet",
    "The input does not match the expected structure for a DNS hostname" => "Ez a mező nem felel meg a várt DNS kiszolgáló név struktúrának",
    "The input appears to be a DNS hostname but cannot match against hostname schema for TLD '%tld%'" => "Ez a mező DNS kiszolgáló névnek tűnik, de nem sikerült megfeleltetni a '%tld%' kiszolgáló név sémának",
    "The input does not appear to be a valid local network name" => "Ez a mező nem tűnik érvényes helyi hálózati névnek",
    "The input does not appear to be a valid URI hostname" => "Ez a mező nem tűnik érvényes URI kiszolgáló névnek",
    "The input appears to be an IP address, but IP addresses are not allowed" => "Ez a mező IP címnek tűnik, de IP címek nem megengedettek",
    "The input appears to be a local network name but local network names are not allowed" => "Ez a mező helyi hálózati címnek tűnik, de helyi hálózati címek nem megengedettek",
    "The input appears to be a DNS hostname but cannot extract TLD part" => "Ez a mező DNS kiszolgáló névnek tűnik, de a TLD részt nem sikerült visszafejteni",
    "The input appears to be a DNS hostname but cannot match TLD against known list" => "Ez a mező DNS kiszolgáló névnek tűnik, de a megadott TLD ismeretlen",
    // EmailAddress validator
    "The input is not a valid email address. Use the basic format local-part@hostname" => "Ez nem egy szabályos email cím. Használd a helyi-rész@gazdagép formátumot",
    "'%hostname%' is not a valid hostname for the email address" => "A(z) '%hostname%' nem egy szabályos gazdagép",
    "'%hostname%' does not appear to have any valid MX or A records for the email address" => "A(z) '%hostname%' nem tűnik érvényes MX vagy A rekordnak egy email cím számára",
    "'%hostname%' is not in a routable network segment. The email address should not be resolved from public network" => "A(z) '%hostname%' nem visszafejthető hálózati szegmens. Az email cím nem visszafejthető a nyilvános hálózaton",
    "'%localPart%' can not be matched against dot-atom format" => "A(z) '%localPart%' nem felel meg a dot-atom formátum elvárásainak",
    "'%localPart%' can not be matched against quoted-string format" => "A(z) '%localPart%' nem megfelelően idézőjelezett",
    "'%localPart%' is not a valid local part for the email address" => "A(z) '%localPart%' nem egy email cím helyi része",
    "The input exceeds the allowed length" => "A mező hossza meghaladja a megengedett karakterszámot",
];
