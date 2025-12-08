# Dandori Jira
A **Laravel-based project** that mimics the core functionalities of a task management tool.

## 🚀 Setup Instructions

To get the **Dandori Jira** project running on your local machine, you need to follow a series of steps that cover backend and frontend setup. Since the project is for public use, you'll be downloading and configuring it rather than cloning it for personal development.

### 1\. Initial Setup and Prerequisites

First, ensure you have all the necessary software installed:

  * **XAMPP**: Start the Apache and MySQL services. These will act as your local web server and database server.
  * **Composer**: This is a PHP dependency manager.
  * **Node.js & npm**: These manage frontend dependencies and scripts.

-----

### 2\. Project Download and Backend Configuration

1.  Download the project files from the GitHub repository as a ZIP file.
2.  Extract the ZIP file and place the `jira` folder in your preferred directory.
3.  Open a terminal and navigate to the project's root directory: `cd path/to/jira`.
4.  Install the backend dependencies by running `composer install`.
5.  Create a copy of the environment file with `cp .env.example .env`.
6.  Generate a unique application key for security with `php artisan key:generate`.
7.  Create a new database in **phpMyAdmin**, for example, `jira_db`.
8.  Open the `.env` file and update the database settings to match your new database:
      * `DB_DATABASE=jira_db`
      * `DB_USERNAME=root`
      * `DB_PASSWORD=`

-----

### 3\. Database Migration and Seeding

With the database configured, you can set it up with the required tables and initial data.

1.  Run the migration and seeding commands: `php artisan migrate:fresh --seed`.
2.  This command will create all the necessary tables and populate them with default data for roles, permissions, and a default admin user.

-----

### 4\. Frontend Setup and Launch

1.  In the same terminal, install the frontend dependencies by running `npm install`.
2.  Build the frontend assets with `npm run build`.
3.  Start the Laravel development server with `php artisan serve`.
4.  Open a **new** terminal and, while in the same `jira` directory, start the frontend development server with `npm run dev`.

The application will now be running and accessible at [http://localhost:8000](https://www.google.com/search?q=http://localhost:8000). You can log in with the admin user created during the seeding process to begin managing the application.

---

## 👥 Roles Breakdown

### **Admin**

* Full administrator access
* Manage users, roles, customer and tickets
* Change global theme
* Download ticket data

---

### **AdminTeknisi**

* Able to download ticket reports
* Manage users, customer and tickets
* Assists other roles:

  * **Requestor** → Edit tickets if there are mistakes
  * **Teknisi** → Assign a technician to a ticket

---

### **Requestor**

* Create new tickets (ordered from supplier)

---

### **Teknisi**

* Work on tickets created by requestors
* Update ticket status:

  * **TO DO** → Ticket created, ready to be worked on
  * **IN PROGRESS** → Work started (Check In)
  * **PENDING** → Work paused due to circumstances
  * **FINISH** → Work completed and closed (Check Out)

---

### **Views**

* Read-only role
* Can only view:

  * "Status Ticket" chart
  * Table of WIP (Work in Progress) tickets on the homepage
  
