# Web Dev 2 – DBC Auto 2.0

## Description
DBC Auto is a car dealer web application built for Web Development 2.

The application has a public side where users can browse available cars, view details, and send an inquiry for a car they are interested in. It also has a staff side where employees and admins can log in and manage dealership functionality.

This project is based on my earlier Web Development 1 car dealer project, but it has been expanded into a frontend + backend application with a REST API, JWT authentication, role authorization, and a real database.

## Database
Database name:
- developmentdb

Database credentials:
- Host: mysql
- Port: 3306
- Username: developer
- Password: secret123

SQL database creation script is included as:
- developmentdb.sql

The application uses MariaDB/MySQL in Docker.

## Login credentials

### Admin account
- Email: admin@dbcauto.nl
- Password: password123

### Employee account
- Email: employee@dbcauto.nl
- Password: password123

## Project structure

### Frontend
- `frontend/` – Vue.js application
- `frontend/src/components` – atoms, molecules, organisms, pages, templates
- `frontend/src/utils` – API utilities
- `frontend/src/App.vue` – main frontend routing

### Backend
- `backend/app/public/index.php` – API entry point
- `backend/app/src/Controllers` – controllers
- `backend/app/src/Models` – domain models
- `backend/app/src/Repositories` – database access
- `backend/app/src/Services` – business logic and validation
- `backend/app/src/Helpers` – JWT helper and other helpers
- `backend/app/src/Utils` – database connection and utilities

### Docker
- docker-compose.yml
- PHP.Dockerfile
- nginx.conf

### Database script
- developmentdb.sql

## Features

### Public user
- View home page
- Browse car inventory
- Filter cars by brand, fuel type, and status
- View car details
- Send inquiry for a car
- Reopen most recent inquiry conversation on the same device

### Employee
- Log in with JWT authentication
- View inquiries
- Open a specific inquiry
- Reply to customer inquiries

### Admin
- All employee functionality
- Create cars
- Edit cars
- Delete cars

## Architecture / Patterns
- REST API backend
- Vue.js frontend
- MVC-like backend architecture
- Repository pattern for database access
- Service layer for business logic and validation
- JWT authentication
- Role-based authorization
- Component-based frontend structure

## API overview

### Cars
- GET /cars
- GET /cars/{id}`
- POST /cars
- PUT /cars/{id}
- DELETE /cars/{id}

### Authentication
- POST /auth/login
- GET /auth/me

### Inquiries
- GET /inquiries
- POST /inquiries
- PUT /inquiries/{id}
- GET /inquiries/token/{token}
- POST /inquiries/token/{token}/messages

## AI disclosure statement
I used AI only as a support tool for learning, debugging, and improving the project in general

In general, I was using it only for:
- debugging PHP and Vue errors
- discussing backend and frontend architecture
- improving validation logic
- refactoring components and the pages
- improving the styling and the structure
- helping to prepare this README file too

Even after doing my own research, I reviewed, and also integrated all suggestions manually
