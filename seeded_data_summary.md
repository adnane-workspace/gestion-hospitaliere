# 🏥 Guide des Données de Simulation Hospitalière (Maroc)

Ce document sert de guide de référence complet pour explorer les données simulées injectées par le Seeder dans la base de données de l'application **Gestion Hospitalière**. Toutes ces données sont réalistes et inspirées du contexte médical marocain.

> [!TIP]
> **Mot de passe unique** : Tous les comptes répertoriés ci-dessous partagent le même mot de passe pour simplifier vos tests : `password`

---

## 🔑 1. Comptes de Test & Connexion

Utilisez ces identifiants pour vous connecter avec les différents rôles et explorer l'application sous diverses perspectives :

| Rôle | Nom de l'utilisateur | Adresse Email | Mot de passe | Description / Usage de test |
| :--- | :--- | :--- | :--- | :--- |
| **Administrateur** | Administrateur Principal | `admin@hospit.com` | `password` | Accès complet, gestion des services, des médecins, statistiques globales. |
| **Médecin (Test)** | Dr. Ahmed El Fassi | `medecin@hospit.com` | `password` | Cardiologue traitant de Yassine Mansouri et Amina Bensouda. |
| **Médecin** | Dr. Leila El Amrani | `elamrani@hospit.com` | `password` | Pédiatre spécialisée, médecin traitant de Kenza Filali (enfant). |
| **Médecin** | Dr. Fatim-Zahra Chraïbi | `chraibi@hospit.com` | `password` | Médecin généraliste, médecin traitant de Salma Bourkia. |
| **Médecin** | Dr. Youssef Alami | `alami@hospit.com` | `password` | Gynécologue Obstétricien. |
| **Médecin** | Dr. Mehdi Tazi | `tazi@hospit.com` | `password` | Dermatologue, médecin traitant de Reda El Glaoui. |
| **Patient (Test)** | Yassine Mansouri | `patient@hospit.com` | `password` | Patient central avec dossier médical très complet (HTA, ECG, CNOPS). |
| **Patient** | Salma Bourkia | `bourkia@hospit.com` | `password` | Patiente suivie pour grippe et diabète gestationnel (CNSS). |
| **Patient** | Karim Haddad | `haddad@hospit.com` | `password` | Patient avec historique d'hospitalisation cardiologique (AMO). |
| **Patient** | Amina Bensouda | `bensouda@hospit.com` | `password` | Patiente âgée souffrant d'hypothyroïdie et d'HTA (Assurance Privée). |
| **Patient** | Reda El Glaoui | `glaoui@hospit.com` | `password` | Étudiant suivi pour acné sévère (Assurance Privée). |
| **Patient** | Kenza Filali | `filali@hospit.com` | `password` | Profil pédiatrique (enfant de 6 ans, CNOPS). |

---

## 🥼 2. Profils des Médecins & Spécialités

Les médecins sont répartis dans des départements spécialisés. Leurs plages horaires de disponibilité hebdomadaires sont pré-configurées du **Lundi au Vendredi de 09:00 à 17:00**.

| Matricule | Médecin | Spécialité | Département | Téléphone |
| :--- | :--- | :--- | :--- | :--- |
| `MED-2026-0001` | Dr. Ahmed El Fassi | Cardiologue Spécialisé | Cardiologie | `0661122334` |
| `MED-2026-0002` | Dr. Amine Bennani | Cardiologue Rythmologue | Cardiologie | `0661234567` |
| `MED-2026-0003` | Dr. Leila El Amrani | Pédiatre Néonatologue | Pédiatrie | `0662345678` |
| `MED-2026-0004` | Dr. Youssef Alami | Gynécologue Obstétricien | Gynécologie-Obstétrique | `0663456789` |
| `MED-2026-0005` | Dr. Fatim-Zahra Chraïbi | Médecin Généraliste Familial | Médecine Générale | `0664567890` |
| `MED-2026-0006` | Dr. Mehdi Tazi | Dermatologue Esthétique | Dermatologie | `0665678901` |

---

## 📋 3. Dossiers Médicaux des Patients

Chaque patient dispose d'un profil complet avec des données cliniques détaillées, des antécédents médicaux et chirurgicaux, et une couverture sociale marocaine :

### 👤 Yassine Mansouri (Patient Test Central)
*   **Dossier** : `DOS-2026-0001` | **CIN** : `AB123456` | **Ville** : Rabat Agdal
*   **Physique** : 1m78, 82.5 kg (IMC : 26.04 - Surpoids léger)
*   **Groupe Sanguin** : A+ | **Mutuelle** : **CNOPS** (N° Contract : `CN-890123-A`)
*   **Allergies** : Pénicilline, Pollen
*   **Maladies Chroniques** : Hypertension artérielle (HTA)
*   **Antécédents** : Appendicectomie en 2012, Chirurgie de la rotule gauche en 2018
*   **Médicaments Actuels** : Coversyl 5mg (1 comp/jour), Ventoline au besoin

### 👤 Salma Bourkia
*   **Dossier** : `DOS-2026-0002` | **CIN** : `BE789012` | **Ville** : Casablanca Maarif
*   **Physique** : 1m65, 64 kg (IMC : 23.51 - Normal)
*   **Groupe Sanguin** : O+ | **Mutuelle** : **CNSS** (N° Contract : `CS-11223344-9`)
*   **Allergies** : Poussière, Aspirine
*   **Maladies Chroniques** : Diabète gestationnel antérieur
*   **Antécédents** : Rhinite allergique, Césarienne en 2021
*   **Médicaments Actuels** : Glucophage 850mg (1 comp/jour)

### 👤 Karim Haddad
*   **Dossier** : `DOS-2026-0003` | **CIN** : `HA345678` | **Ville** : Marrakech Gueliz
*   **Physique** : 1m82, 90 kg (IMC : 27.17 - Surpoids)
*   **Groupe Sanguin** : B- | **Mutuelle** : **AMO / CNSS** (N° Contract : `AM-89776655-2`)
*   **Allergies** : Lactose
*   **Antécédents** : Fracture de la clavicule gauche (2015)
*   **Observations** : Suivi pour douleurs thoraciques (a subi une hospitalisation d'observation).

### 👤 Amina Bensouda
*   **Dossier** : `DOS-2026-0004` | **CIN** : `CD901234` | **Ville** : Fès (Route d'Imouzzer)
*   **Physique** : 1m60, 70 kg (IMC : 27.34 - Surpoids)
*   **Groupe Sanguin** : AB+ | **Mutuelle** : **Wafa Assurance (Privée)** (N° Contract : `WF-998877-B`)
*   **Allergies** : Produits de contraste iodés
*   **Maladies Chroniques** : Hypothyroïdie, Hypertension artérielle (HTA)
*   **Médicaments Actuels** : Lévothyrox 75µg, Lisinopril 10mg

### 👤 Kenza Filali (Enfant - Pédiatrie)
*   **Dossier** : `DOS-2026-0006` | **Âge** : 6 ans | **Ville** : Rabat Hay Riad
*   **Physique** : 1m18, 22 kg (IMC : 15.79 - Normal)
*   **Groupe Sanguin** : A- | **Mutuelle** : **CNOPS (Enfant rattaché)**
*   **Allergies** : Pénicilline, Fraises
*   **Antécédents** : Varicelle en 2023. Carnet de vaccination à jour.

---

## 🩺 4. Consultations Cliniques & Prescriptions (Exemples)

La base contient des enregistrements cliniques détaillés découlant des rendez-vous terminés :

### 🔍 Consultation 1 : Cardiologie (Dr. Ahmed El Fassi ➔ Yassine Mansouri)
*   **Réf** : `CONS-2026-0001`
*   **Constantes** : TA `135/85` mmHg, FC `72` bpm, Temp `36.6` °C, SpO2 `98.0` %
*   **Motif** : Fatigue passagère et contrôle HTA sous Coversyl.
*   **Diagnostic Principal** : *Hypertension artérielle essentielle modérée* (**Code CIM-10 : I10**)
*   **Ordonnance Émise (`ORD-2026-0001`)** :
    1.  **Coversyl 5mg** (comprimé) : 1 comprimé le matin à jeun pendant 3 mois (ALD - Sans substitution).
    2.  **Magne B6** (comprimé) : 1 comprimé midi et soir au cours du repas pendant 30 jours.
*   **Recommandations** : Régime hyposodé, marche rapide 3 fois par semaine, éviter le surmenage.

### 🔍 Consultation 2 : Médecine Générale (Dr. Fatim-Zahra ➔ Salma Bourkia)
*   **Réf** : `CONS-2026-0002`
*   **Constantes** : Temp `38.9` °C (Fièvre), TA `110/70` mmHg, FC `92` bpm, SpO2 `97.0` %
*   **Motif** : Syndrome grippal aigu avec frissons et céphalées intenses.
*   **Diagnostic Principal** : *Grippe saisonnière (influenza)* (**Code CIM-10 : J11**)
*   **Ordonnance Émise (`ORD-2026-0002`)** :
    1.  **Doliprane 1g** (comprimé) : 1 comprimé toutes les 6 heures si fièvre ou douleurs (Max 4g/jour) pendant 5 jours.
    2.  **Rhinathiol Toux Sèche** (sirop) : 1 cuillère à soupe 3 fois par jour pendant 5 jours.
*   **Arrêt de travail** : Délivré pour une durée de **3 jours**.

---

## 💳 5. Facturation & Prise en Charge (MAD)

Les montants facturés reflètent les barèmes de remboursement réels appliqués au Maroc :

| Numéro Facture | Patient | Type Prestation | Montant Brut | Remise | Part Mutuelle (Remboursé) | Part Patient (Reste à charge) | Statut Paiement | Mode de Paiement | Organisme |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **`FAC-2026-0001`** | Yassine Mansouri | Consultation + ECG | 450,00 MAD | 50,00 MAD | **320,00 MAD** (80%) | **80,00 MAD** | **Payée** | Carte Bancaire | CNOPS |
| **`FAC-2026-0002`** | Salma Bourkia | Consultation Générale | 200,00 MAD | 0,00 MAD | **140,00 MAD** (70%) | **60,00 MAD** | **En retard** | - (Non payée) | CNSS |
| **`FAC-2026-0003`** | Karim Haddad | Hospitalisation (2 nuits) | 3000,00 MAD | 300,00 MAD | **2160,00 MAD** (80%) | **540,00 MAD** | **Partiel** | Virement | AMO / CNSS |

> [!NOTE]
> *   La facture `FAC-2026-0001` comprend 2 lignes de détails : la consultation à 300 MAD HT et le tracé ECG à 100 MAD HT. Une TVA de 10% s'applique, ce qui donne un montant TTC de 440 MAD.
> *   La facture `FAC-2026-0003` correspond à un séjour en soins intensifs de cardiologie (chambre privée individuelle à 1200 MAD/nuit et un forfait de consommables à 300 MAD).

---

## 💬 6. Messagerie Clinique (Exemple de Chat Actif)

Pour valider le module de messagerie instantanée sécurisée, une discussion réaliste a été pré-peuplée entre le patient **Yassine Mansouri** et son cardiologue le **Dr. Ahmed El Fassi** :

```text
[Yassine Mansouri - J-6 09:15]
"Bonjour Dr. Ahmed, j'espère que vous allez bien. Je vous contacte car je ressens une légère fatigue en fin de journée depuis le changement de dosage du Coversyl."

[Dr. Ahmed El Fassi - J-6 11:40]
"Bonjour Yassine. C'est une réaction fréquente en début de traitement due à la baisse de tension. Est-ce que vous prenez bien votre comprimé à jeun le matin ?"

[Yassine Mansouri - J-6 12:05]
"Oui Docteur, je le prends tous les matins au réveil avec un grand verre d'eau, 30 minutes avant le petit-déjeuner."

[Dr. Ahmed El Fassi - J-5 10:10]
"Excellent. C'est la bonne méthode. Pour pallier cette fatigue passagère, je vous ai prescrit du magnésium dans votre ordonnance. Prenez-le pendant les repas de midi et du soir. Tenez-moi au courant lors de notre prochain rendez-vous."

[Yassine Mansouri - J-4 18:30]
"Parfait Docteur. J'ai commencé le magnésium et je me sens déjà beaucoup mieux. Merci beaucoup pour votre réactivité !"
```

---

## 🔔 7. Notifications In-App Simulées

Les indicateurs de notifications en temps réel s'afficheront sur les tableaux de bord :

*   **Pour le Patient Yassine Mansouri** :
    *   *Rendez-vous confirmé* : Notification l'informant du suivi programmé pour aujourd'hui à 10:00 avec le Dr. Ahmed El Fassi.
    *   *Facture émise* : Notification de validation de la facture `FAC-2026-0001` d'un montant de 440.00 MAD.
*   **Pour le Dr. Ahmed El Fassi** :
    *   *Nouveau rendez-vous planifié* : Alerte l'informant d'une consultation d'urgence planifiée aujourd'hui à 15:30 avec Amina Bensouda.
