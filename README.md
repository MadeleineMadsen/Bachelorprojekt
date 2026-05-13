# GBG Social – Social Studenterforening Platform

Dette er et bachelorprojekt udviklet af Kamilla, Naomi og Madeleine på 7. semester (Webudvikling, EK).

## Formål
Projektet er en platform til den Sociale Studenterforening på Erhvervsakademi København - Guldbergsgade.
Platformen understøtter studielivet ved at samle events, medlemsadministration og sociale aktiviteter ét sted.

Brugere kan oprette en konto, logge ind, tilmelde sig events og ansøge om at blive medlem/vejleder. Admins kan administrere events, godkende eller afvise medlemsansøgninger samt administrere eksisterende medlemmer.


## Funktioner

# Authentication & Brugerstyring
- Signup og login
- Mailverificering ved oprettelse af bruger
- Rollebaseret adgang:
- Admin
- Member (vejleder)
- User (studerende)
- Sikker password hashing
- Sessionshåndtering
- Logout

# Profilhåndtering
- Redigering af brugerprofil
- Upload af profilbillede
- Ændring af password
- Opdatering af brugeroplysninger

# Eventsystem
- Oprettelse af events (admin)
- Visning af events
- Tilmelding til events
- Kalenderoversigt over events

# Medlemssystem
- Ansøgning om member-/vejlederrolle
- Godkendelse eller afvisning af ansøgninger (admin)
- Oversigt over aktive medlemmer
- Søgning og filtrering af medlemmer
- Soft delete af medlemmer

# Mailfunktioner
- Automatiske mails via SMTP:
- Bekræftelse ved signup
- Bekræftelse ved medlemsansøgning
- Godkendelse af medlem
- Afvisning af medlem
- Fjernelse af medlem

# Frontend
- Responsivt design
- Aktiv navigation
- Mobile navigation
- Error- og success-beskeder ved formularer

## Arkitektur
Projektet følger en simpel MVC-struktur:
- Models → håndterer data og database (PDO)
- Views → præsentation (HTML/PHP)
- Controllers → styrer requests og applikationsflow

## Teknisk Setup

# Backend
- PHP
- MySQL
- PDO
- PHPMailer

# Frontend
- HTML5
- CSS3
- JavaScript

# Infrastruktur
- Apache
- Docker
- phpMyAdmin

## Sikkerhed
- Password hashing med password_hash()
- Sessionbaseret authentication
- Inputvalidering
- Prepared statements via PDO
- Soft delete fremfor permanent sletning

## Database
Systemet benytter relationel MySQL-database med:
- users
- members
- events
- event_registrations
- educations
- semesters
- roles