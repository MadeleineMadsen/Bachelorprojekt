# GBG Social – Social Studenterforening Platform

Dette er et bachelorprojekt udviklet af Kamilla, Naomi og Madeleine på 7. semester (Webudvikling, EK).

## Formål
Projektet er en platform til ROS, som understøtter studielivet på skolen.  
Vejledere kan oprette events, og nye studerende kan tilmelde sig events samt ansøge om at blive tutor/vejleder.

## Funktioner
- Login og signup
- Roller (vejleder/admin og studerende)
- Oprettelse af events
- Tilmelding til events
- Ansøgning om tutor/vejleder-rolle

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