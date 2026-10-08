# Data_Entry_System

A simple PHP + MySQL based web application for entering and managing paper marks of students, with a public entry form and a secure admin dashboard.

## 📌 Features

- Public form for paper markers to submit student marks (Index No, Group No, Part A & Part B marks, Marker name)
- Duplicate index number prevention (UNIQUE constraint)
- Admin login with hashed passwords (bcrypt via PHP `password_hash()`)
- Admin dashboard to:
  - View all submitted entries in a paginated table (10 per page)
  - Add, edit, and delete entries
  - Add, rename, and delete marker names (dynamically updates the form dropdown)
  - Export all entries as an Excel (.xlsx / .csv) file
- Clean, responsive dashboard UI

## 🗂 File Structure

``
Data_Entry_System/
│
├── config.php           # Database connection
├── index.php            # Public marks entry form
├── login.php            # Admin login
├── logout.php
├── dashboard.php        # Admin dashboard
├── export_excel.php     # Excel export handler
├── actions/
│ ├── add_entry.php
│ ├── edit_entry.php
│ ├── delete_entry.php
│ ├── add_marker.php
│ └── delete_marker.php
├── assets/
│ ├── style.css
│ └── script.js
├── .gitignore
└── README.md

``

## 🗄 Database

**Database name:** `marks_db`

**Tables:** `admins`, `markers`, `marks_entries` (see `schema.sql` for full table structure with columns and relationships)

## ⚙️ Setup

1. Clone the repository
```bash
   git clone https://github.com/your-username/Data_Entry_System.git
```
2. Import the database
   - Create a database named `marks_db` in MySQL
   - Run the SQL queries in `schema.sql` to create the tables
3. Configure database connection
   - Open `config.php` and update your DB host, username, password, and database name
4. Run the project
   - Place the project folder in your local server (XAMPP/WAMP `htdocs`)
   - Visit `http://localhost/Data_Entry_System/index.php` for the public form
   - Visit `http://localhost/Data_Entry_System/login.php` for admin login

## 🔐 Security

- Passwords are hashed using PHP's `password_hash()` (bcrypt) and verified with `password_verify()`
- Prepared statements (PDO/MySQLi) used throughout to prevent SQL injection

## 🛠 Tech Stack

- PHP
- MySQL
- HTML, CSS, JavaScript

## 📄 License

No license — personal learning project.