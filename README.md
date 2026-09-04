# EduPulse 🎓

An AI-powered educational platform built with **Laravel 12**, designed to enhance personalized learning, quizzes, analytics, and student performance tracking.

---

## 🌐 Live Deployment

**Live Website:**  
https://edupulse.mejbahuddin.xyz

**Previous Render Deployment:**  
https://edupulse-4vk1.onrender.com

---
# 📌 Features

## 👨‍🎓 Authentication & User Roles
- Student registration/login
- Role-based authentication
- Secure password hashing
- Session management

## 📚 Learning System
- Course management
- Quiz system
- MCQ-based assessments
- Topic-wise learning

## 🤖 AI-Powered Recommendations
- Personalized study recommendations
- Performance analysis
- Topic performance tracking
- Smart feedback generation

## 📊 Analytics Dashboard
- Student performance analytics
- Quiz attempt tracking
- Recommendation insights
- Progress monitoring

## 📝 Assessment System
- Dynamic quizzes
- Multiple question types
- Attempt history
- Score calculation

## 📧 Email System
- SMTP/Mailtrap integration
- Email verification support
- Password reset support

## 🎨 Modern UI
- Responsive design
- Mobile-friendly interface
- TailwindCSS + Vite frontend
- Clean dashboard UI

---

# 🛠️ Tech Stack

## Backend
- Laravel 12
- PHP 8.2
- PostgreSQL (Supabase)

## Frontend
- Blade Templates
- Tailwind CSS
- Vite
- JavaScript

## Deployment
- Render (Docker Deployment)
- Supabase PostgreSQL

---

# 🚀 Deployment Architecture

```text
Frontend + Backend → Render
Database → Supabase PostgreSQL
Mail Service → Mailtrap / Gmail SMTP
```

## Environment Variables

### Create a .env file:
```
APP_NAME=EduPulse
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
DB_SSLMODE=require

SESSION_DRIVER=file
SESSION_LIFETIME=240

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=EduPulse
```
## Docker Setup
### Build Docker Image
```
docker build -t edupulse .
```
### Run Container
```
docker run -p 80:80 edupulse
```
## Local Installation
### 1. Clone Repository
```
git clone https://github.com/MejbahUddinBhuiyan/Edupulse.git
```
### 2. Enter Project
```
cd Edupulse
```
### 3. Install Dependencies
```
composer install
npm install
```
### 4. Configure Environment
```
cp .env.example .env
```
Update database credentials.
### 5. Generate App Key
```
php artisan key:generate
```
### 6. Run Migrations
```
php artisan migrate --seed
```
### 7. Start Development Server
```
php artisan serve
npm run dev
```
🗄️ Database

## EduPulse uses:

- PostgreSQL (Supabase)
- Laravel Migrations
- Laravel Seeders

## Run Migration
```
php artisan migrate
```
## Run Seeder
```
php artisan db:seed
```
## Production Deployment
### Render Deployment
- Docker-based deployment
- Automatic GitHub deployment
- HTTPS enabled
- Environment variable support
### Supabase
- Managed PostgreSQL database
- Cloud-hosted
- Secure SSL connection

## Security Features
- CSRF Protection
- Password Hashing
- Secure Sessions
- HTTPS Forced in Production
- Trusted Proxy Configuration

# 📁 Project Structure

```text
Edupulse/
├── app/                  # Core application logic
├── bootstrap/            # Laravel bootstrap files
├── config/               # Application configuration
├── database/             # Migrations, seeders, factories
├── public/               # Publicly accessible files
├── resources/            # Views, CSS, JS assets
├── routes/               # Web and API routes
├── storage/              # Logs, sessions, cache
├── tests/                # Automated tests
├── vendor/               # Composer dependencies
├── Dockerfile            # Docker configuration
├── composer.json         # PHP dependencies
├── package.json          # Node.js dependencies
└── README.md             # Project documentation
```

## Future Improvements
- AI chatbot integration
- Video learning modules
- Real-time notifications
- Advanced analytics
- Certificate generation
- Admin management panel improvements

## Developer
### Mejbah Uddin Bhuiyan
GitHub:
```
https://github.com/MejbahUddinBhuiyan
```
### Project: EduPulse
## License

This project is developed for educational and portfolio purposes.
