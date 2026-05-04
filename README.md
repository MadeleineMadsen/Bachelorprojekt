# GBG Social – Social Studenterforening Platform

Dette er et bachelorprojekt udviklet af Kamilla, Naomi og Madeleine på 7. semester (Webudvikling, EK).

## Formål
Projektet er en platform til den Sociale Studenterforening på Erhvervsakademi København - Guldbergsgade, som understøtter studielivet på skolen.  
Admin kan oprette events samt godkende og slette medlemmer. Nye studerende kan signe up/logge ind for at blive en user, og herefter tilmelde sig events samt ansøge om at blive member. Som member har man udfyldt en ansøgning, været til samtale og er blevet godkendt på siden af admin, hvorefter man først her går fra user til member.

## Funktioner
- Login og signup
- Roller (admin, members (vejleder) og users (studerende))
- Oprettelse af events
- Tilmelding til events
- Ansøgning om vejleder-rolle (member)
- Godkendelse/sletning af vejledere

## Arkitektur
Projektet følger en simpel MVC-struktur:
- Models → håndterer data og database (PDO)
- Views → præsentation (HTML/PHP)
- Controllers → styrer requests og applikationsflow

## Teknisk setup
- PHP (backend)
- MySQL + PDO (database)
- Apache (webserver)
- Docker (udviklingsmiljø)
- phpMyAdmin (database GUI)