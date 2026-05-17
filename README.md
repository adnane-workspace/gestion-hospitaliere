# Gestion Hospitaliere - Laravel 13

Application web de gestion hospitaliere (admin, medecin, patient) construite avec Laravel 13. Ce projet est conçu comme une simulation hospitalière réaliste, inspirée d'un vrai système médical professionnel, avec un accent sur la gestion des dossiers patients et le suivi clinique.

## Fonctionnalites principales

- Authentification et gestion des roles (`admin`, `medecin`, `patient`)
- Tableau de bord selon le role
- Gestion des patients
- Gestion des rendez-vous
- Historique des consultations
- Notifications in-app (base de donnees)z
- Messagerie patient-medecin
- Gestion des disponibilites medecins
- Données médicales détaillées pour les patients (groupe sanguin, allergies, antécédents, IMC, tension, fréquence cardiaque)
- Dossier médical patient enrichi avec historique, traitements et indicateurs de santé
- Interface patient améliorée avec tableau de bord, accès au dossier, rendez-vous et messagerie
- Données réalistes injectées par seeders pour une expérience de démonstration professionnelle
- Export PDF de l'historique patient
- Demarrage de consultation depuis rendez-vous (workflow metier)
- Ouverture directe du dossier patient depuis dashboard medecin
- Securite backend avancee via Policies + middleware profil obligatoire

## Améliorations de la partie Patient

- Dossier médical enrichi : groupe sanguin, allergies, antécédents médicaux et chirurgicaux, maladies chroniques, médicaments actuels.
- Calcul automatique de l'IMC et affichage de la catégorie de poids.
- Tableau de bord patient personnalisé avec accès aux rendez-vous, historique clinique et messagerie.
- Interface patient plus conviviale et moderne, pensée pour un usage académique et de démonstration.
- Seeders alimentant des patients, rendez-vous, factures et consultations réalistes pour simuler un environnement professionnel.

## Stack technique

- PHP 8.3+
- Laravel 13
- SQLite (par defaut)
- Tailwind CSS
- Alpine.js
- ApexCharts
- DomPDF (`barryvdh/laravel-dompdf`)

## Installation du projet

### 1) Cloner le projet

```bash
git clone https://github.com/adnane-workspace/gestion-hospitaliere.git
cd gestion-hospitaliere
```

### 2) Installer les dependances

```bash
composer install
npm install
```

### 3) Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

> Sur Windows PowerShell, vous pouvez aussi utiliser:
>
> `Copy-Item .env.example .env`

### 4) Base de donnees

Le projet est configure par defaut sur SQLite.

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --seed
```

### 5) Lancer le projet

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Application disponible sur:
`http://127.0.0.1:8000`

## Comptes de test

Ces comptes sont crees par `DatabaseSeeder`.

- **Admin**
  - Email: `admin@hospit.com`
  - Mot de passe: `password`
  - Role: `admin`

- **Medecin**
  - Email: `medecin@hospit.com`
  - Mot de passe: `password`
  - Role: `medecin`

- **Patient**
  - Email: `patient@hospit.com`
  - Mot de passe: `password`
  - Role: `patient`

## Dump SQL avec donnees de test

Le dump SQL est fourni ici:

- `database/dumps/gestion-hospitaliere-dump.sql`

Pour regenerer le dump apres modification de la base:

```bash
python -c "import sqlite3, pathlib; db='database/database.sqlite'; out='database/dumps/gestion-hospitaliere-dump.sql'; pathlib.Path('database/dumps').mkdir(parents=True, exist_ok=True); con=sqlite3.connect(db); pathlib.Path(out).write_text('\n'.join(con.iterdump()), encoding='utf-8'); con.close()"
```

## Commandes utiles

- Reinitialiser la base et reseeder:
  - `php artisan migrate:fresh --seed`
- Migrer/Seeder sans reset (idempotent):
  - `php artisan migrate --seed`
- Lancer les tests:
  - `php artisan test`
- Lancer uniquement les tests de securite/permissions:
  - `php artisan test tests/Feature/Security/AuthorizationPolicyTest.php`
- Lister les routes:
  - `php artisan route:list`

## Fonctionnement rapide

### Rôles

- `admin` : administration globale, gestion des patients, gestion des médecins, accès à la comptabilité.
- `medecin` : consultation des rendez-vous, démarrage de consultations, accès aux dossiers patients et historique clinique.
- `patient` : prise de rendez-vous, consultation du dossier médical, messagerie avec le médecin, suivi personnel.

### Mode de fonctionnement général

Le système sépare les responsabilités par rôle : l'administrateur supervise, le médecin gère les consultations et le patient consulte son dossier et communique via la messagerie interne.

### Parcours d'un patient

1. Le patient se connecte et consulte son tableau de bord personnalisé.
2. Il peut prendre un rendez-vous, voir ses prochains RDV et lancer une conversation avec le secrétariat ou le médecin.
3. Le dossier patient affiche des informations médicales détaillées : groupe sanguin, allergies, antécédents, indicateurs vitaux et IMC.
4. Le médecin voit le dossier patient et peut démarrer une consultation depuis un rendez-vous confirmé.

## Structure rapide du projet

- `app/Http/Controllers`: logique des ecrans et API
- `app/Models`: entites metier (Patient, Medecin, RendezVous, etc.)
- `app/Services`: logique metier (ex: reservation RDV)
- `app/Policies`: autorisations fines par ressource
- `app/Http/Requests`: validation des donnees (Form Requests)
- `resources/views`: interfaces Blade + Tailwind
- `database/migrations`: schema de base
- `database/seeders`: donnees de test
- `tests/Feature/Security`: tests de securite d'acces

## Securite et coherence des donnees

- Validation centralisee avec Form Requests:
  - creation/mise a jour patient
  - envoi de message
  - disponibilites medecin
  - prise de rendez-vous
- Autorisations backend via Policies:
  - `PatientPolicy`
  - `RendezVousPolicy`
  - `ConsultationPolicy`
  - `MessagePolicy`
- Garde-fou profil metier via middleware:
  - `ensure.profile:medecin`
  - `ensure.profile:patient`
- Workflow consultation securise:
  - demarrage uniquement depuis statuts RDV autorises
  - transitions de statut controlees

## Tester les nouvelles fonctionnalites

1. Connectez-vous en medecin (`medecin@hospit.com` / `password`)
2. Ouvrez le dashboard medecin
3. Cliquez `Demarrer la Consultation` sur le prochain patient:
   - une consultation est creee/reutilisee
   - le RDV passe en `en_cours`
4. Cliquez `Voir le dossier complet`:
   - ouverture du dossier patient cible
5. Connectez-vous en patient et testez la messagerie:
   - envoi vers medecin autorise
   - envoi vers admin bloque (validation/policy)

## Remarques

- Si vous utilisez MySQL au lieu de SQLite, adaptez `DB_*` dans `.env`, puis relancez `php artisan migrate --seed`.
- Pour la production, changez les mots de passe des comptes de test et desactivez `APP_DEBUG`.
