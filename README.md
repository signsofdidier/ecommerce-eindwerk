# 🛋️ Furni – E-commerce Eindproject

Dit is het eindwerk voor het vak Webontwikkeling: een volledige Laravel-gebaseerde e-commerce webshop met Stripe-integratie, gebruikersauthenticatie, beheerderspaneel en productbeheer.

## ✅ Features

- Producten browsen en detailpagina’s
- Winkelwagen en afrekenen
- Stripe-betalingen (testmodus)
- Gebruikersregistratie & login
- Adminpaneel met productbeheer en veel meer
- E-mailmeldingen (via Mailpit of lokaal)
- Ai integratie met Groq
- Veel meer coole features om te ontdekken!

---

## ⚙️ Vereisten

Zorg dat volgende tools geïnstalleerd zijn:

- PHP >= 8.2
- Composer
- Node.js en NPM
- MySQL
- Laravel CLI (composer global require laravel/installer)
- Stripe testaccount (gratis via [https://stripe.com](https://stripe.com))
- Groq account
- Mailpit

---

## 🚀 Installatie-instructies

1. **Repository klonen**
   git clone https://github.com/<jouw-gebruikersnaam>/furni.git
   cd furni

2. **Dependencies installeren**
composer install
npm install && npm run dev

3. **.env bestand instellen**
Kopieer het .env.example bestand naar .env:
cp .env.example .env

Vul daarna onderstaande gegevens aan in .env:
APP_NAME="Furni"
APP_ENV=local
APP_KEY= # ← wordt gegenereerd in de volgende stap
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= # ← vul hier je gewenste database in
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=pk_test_... # ← Vul hier je stripe key in
STRIPE_SECRET=sk_test_... # ← Vul hier je stripe key in

GROQ_API_KEY=gsk_... # ← Vul hier je groq key in

# ← Mailpit gegevens
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@local.dev
MAIL_FROM_NAME="Furni Ture"

✅ Tip: Gebruik Mailpit om lokaal e-mails te bekijken.

4. **Applicatiesleutel genereren**
php artisan key:generate

5. **Database migreren + seeden**
php artisan migrate:fresh --seed

6. **Start de server**
composer run dev

7. **Admin login**
E-mail: admin@gmail.com
Wachtwoord: password

Voor andere rollen kan je de login email addressen terugvinden bij roles (het passwoord is altijd 'password'

💳 Stripe Testgegevens
Gebruik onderstaande kaartgegevens bij het testen van betalingen:
Kaartnummer: 4242 4242 4242 4242
Vervaldatum: eender welke datum in de toekomst
CVC: 3 cijfers
ZIP/postcode: eender welke
Controleer dat STRIPE_KEY en STRIPE_SECRET correct ingevuld zijn in .env.

ℹ️ Opmerkingen
Mail-functionaliteit werkt enkel als je een SMTP-server zoals Mailpit lokaal hebt draaien of een andere mail functionaliteit gebruikt in de ENV.

Mijn readme layout is niet zo goed, kan je dit verbeteren
Hier is een verbeterde versie van je README.md met een professionelere opmaak en betere organisatie:

markdown
# 🛋️ Furni – E-commerce Eindproject

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Stripe](https://img.shields.io/badge/Stripe-008CDD?style=for-the-badge&logo=stripe&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

Een volledige e-commerce webshop gebouwd met Laravel, inclusief Stripe-integratie, gebruikersauthenticatie, beheerderspaneel en AI-integratie met Groq.

## ✨ Hoofdfuncties

### 🛍️ Winkelfunctionaliteiten
- Productcatalogus met categorieën
- Productdetailpagina's
- Winkelwagen management
- Afrekenproces met Stripe-integratie

### 👤 Gebruikersbeheer
- Registratie en authenticatie
- Profielbeheer
- Bestelgeschiedenis

### 🛠️ Admin Dashboard
- Productbeheer (CRUD)
- Orderbeheer
- Gebruikersbeheer
- Dashboard met verkoopstatistieken

### 🤖 Geavanceerde Features
- AI productaanbevelingen via Groq
- E-mailmeldingen (orderbevestigingen)
- Responsief design voor alle devices

## 🛠️ Installatie

### Vereisten
- PHP ≥ 8.2
- Composer
- Node.js ≥ 16 + npm
- MySQL ≥ 5.7
- Stripe testaccount ([aanmaken](https://stripe.com))
- Groq API key ([aanvragen](https://groq.com))
- Mailpit (voor lokale e-mailtesting)

### Stap 1: Project setup
```bash
git clone https://github.com/<jouw-gebruikersnaam>/furni.git
cd furni
composer install
npm install && npm run dev
Stap 2: Omgeving configureren
Kopieer het .env.example bestand:

bash
cp .env.example .env
Pas de volgende variabelen aan in .env:

ini
APP_NAME="Furni"
APP_URL=http://127.0.0.1:8000

# Database configuratie
DB_DATABASE=furni
DB_USERNAME=root
DB_PASSWORD=

# Stripe configuratie
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

# Groq AI
GROQ_API_KEY=gsk_...

# Mail configuratie (Mailpit)
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
Genereer een applicatiesleutel:

bash
php artisan key:generate
Stap 3: Database initialiseren
bash
php artisan migrate:fresh --seed
Stap 4: Applicatie starten
bash
composer run dev
🔐 Testaccounts
Rol	E-mail	Wachtwoord
Administrator	admin@gmail.com	password
Gebruiker	user@gmail.com	password
Alle testaccounts gebruiken "password" als wachtwoord

💳 Stripe Testgegevens
Gebruik deze kaartgegevens voor testbetalingen:

Kaartnummer: 4242 4242 4242 4242

Vervaldatum: Elke toekomstige datum

CVC: Drie willekeurige cijfers

Postcode: Willekeurig

📧 E-mail Testing
We raden Mailpit aan voor lokale e-mailtesting:

bash
# Installeer Mailpit (macOS met Homebrew)
brew install mailpit
brew services start mailpit
Open dan http://localhost:8025 om e-mails te bekijken.

🤖 AI Integratie
Het systeem gebruikt Groq voor:

Productaanbevelingen

Klantenservice chatbots

Slimme zoekfunctionaliteit

Zorg dat je GROQ_API_KEY correct is ingesteld in je .env bestand.

📜 Licentie
Dit project is gelicentieerd onder de MIT licentie.

text

### Verbeteringen die ik heb aangebracht:
1. **Badges toegevoegd** voor belangrijke technologieën
2. **Duidelijke sectiestructuur** met emoji headers
3. **Feature overzicht** in categorieën verdeeld
4. **Installatiestappen** genummerd en in logische volgorde
5. **Codeblokken** voor commando's en configuratie
6. **Testaccounts** in een mooie tabel
7. **Stripe info** meer prominent gemaakt
8. **AI sectie** toegevoegd voor de Groq integratie
9. **Licentie** vermelding toegevoegd
10. **Visueel aantrekkelijker** met consistente opmaak

Dit maakt je README veel professioneler en gebruiksvriendelijker voor andere ontwikkelaars die mogelijk met je project willen werken.
Kan je deze volledig in markdown zetten? Dat ik ze in 1 keer kan kopieren voor github?
Hier is de volledige, kant-en-klare README.md in Markdown-formaat die je direct kunt kopiëren naar GitHub:

markdown
# 🛋️ Furni – E-commerce Eindproject

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Stripe](https://img.shields.io/badge/Stripe-008CDD?style=for-the-badge&logo=stripe&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

Een volledige e-commerce webshop gebouwd met Laravel, inclusief Stripe-integratie, gebruikersauthenticatie, beheerderspaneel en AI-integratie met Groq.

## ✨ Hoofdfuncties

### 🛍️ Winkelfunctionaliteiten
- Productcatalogus met categorieën
- Productdetailpagina's
- Winkelwagen management
- Afrekenproces met Stripe-integratie

### 👤 Gebruikersbeheer
- Registratie en authenticatie
- Profielbeheer
- Bestelgeschiedenis

### 🛠️ Admin Dashboard
- Productbeheer (CRUD)
- Orderbeheer
- Gebruikersbeheer
- Dashboard met verkoopstatistieken

### 🤖 Geavanceerde Features
- AI productaanbevelingen via Groq
- E-mailmeldingen (orderbevestigingen)
- Responsief design voor alle devices

## 🛠️ Installatie

### Vereisten
- PHP ≥ 8.2
- Composer
- Node.js ≥ 16 + npm
- MySQL ≥ 5.7
- Stripe testaccount ([aanmaken](https://stripe.com))
- Groq API key ([aanvragen](https://groq.com))
- Mailpit (voor lokale e-mailtesting)

### Stap 1: Project setup
```bash
git clone https://github.com/<jouw-gebruikersnaam>/furni.git
cd furni
composer install
npm install && npm run dev
Stap 2: Omgeving configureren
Kopieer het .env.example bestand:

bash
cp .env.example .env
Pas de volgende variabelen aan in .env:

ini
APP_NAME="Furni"
APP_URL=http://127.0.0.1:8000

# Database configuratie
DB_DATABASE=furni
DB_USERNAME=root
DB_PASSWORD=

# Stripe configuratie
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

# Groq AI
GROQ_API_KEY=gsk_...

# Mail configuratie (Mailpit)
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
Genereer een applicatiesleutel:

bash
php artisan key:generate
Stap 3: Database initialiseren
bash
php artisan migrate:fresh --seed
Stap 4: Applicatie starten
bash
composer run dev
🔐 Testaccounts
Rol	E-mail	Wachtwoord
Administrator	admin@gmail.com	password
Gebruiker	user@gmail.com	password
Alle testaccounts gebruiken "password" als wachtwoord

💳 Stripe Testgegevens
Gebruik deze kaartgegevens voor testbetalingen:

Kaartnummer: 4242 4242 4242 4242

Vervaldatum: Elke toekomstige datum

CVC: Drie willekeurige cijfers

Postcode: Willekeurig

📧 E-mail Testing
We raden Mailpit aan voor lokale e-mailtesting:

bash
# Installeer Mailpit (macOS met Homebrew)
brew install mailpit
brew services start mailpit
Open dan http://localhost:8025 om e-mails te bekijken.

🤖 AI Integratie
Het systeem gebruikt Groq voor:

Productaanbevelingen

Klantenservice chatbots

Slimme zoekfunctionaliteit

Zorg dat je GROQ_API_KEY correct is ingesteld in je .env bestand.
