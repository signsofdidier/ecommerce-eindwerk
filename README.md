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
