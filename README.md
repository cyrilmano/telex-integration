# HNG12 Stage Backend Project

This is the backend API project for the HNG12 Stage 0 task. The API provides basic information in JSON format, including the registered email, current date/time in ISO 8601 format, and GitHub repository URL. It is built using **Laravel 11.x** and can be cloned and deployed to a publicly accessible endpoint.

## Table of Contents
1. [Project Overview](#project-overview)
2. [Technologies Used](#technologies-used)
3. [Installation Guide](#installation-guide)
4. [API Documentation](#api-documentation)
5. [Running the Application](#running-the-application)
6. [Contributing](#contributing)
7. [License](#license)

## Project Overview
This API exposes a simple endpoint that returns the following information:

- **Email**: The email address used to register for the HNG12 Slack workspace.
- **Current Datetime**: The current UTC date and time in ISO 8601 format.
- **GitHub URL**: The URL of the project's codebase on GitHub.

The endpoint is publicly accessible and designed to provide basic information in a clean, JSON format.

## Technologies Used
- **Backend Framework**: [Laravel 11.x](https://laravel.com)
- **PHP**: Version 8.1 or higher
- **Composer**: Dependency Management
- **Database**: None required for this project (No database interaction in this task)
- **API Response Format**: JSON
- **Deployment**: Can be hosted on any platform (Heroku, DigitalOcean, etc.)

## Installation Guide

To set up the project locally, follow these steps:

### Prerequisites

Before you begin, ensure you have the following installed:
- **PHP** (Version 8.1 or higher)  
- **Composer** (for managing PHP dependencies)  
- **Laravel 11.x** (installed via Composer)

### Clone the Repository

First, clone the repository to your local machine:

```bash
git clone https://github.com/cyrilmano/hng12-backend
cd hng12-backend
