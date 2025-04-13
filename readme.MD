# 🔗 Link Manager

A lightweight, secure, and responsive web application for managing personal or categorized links. Users can register/login, create link categories, add and organize links, and search them by title or category — all built using **PHP**, **MySQL**, **JavaScript**, and **Bootstrap**, without any external frameworks.

---

## 🌟 Features

- ✅ User Registration & Login (Restricted to approved email)
- 🔒 CSRF Protection for all forms
- 📁 Categorize links with stats tracking
- ➕ Add, 📝 Edit, and ❌ Delete links
- 🔍 AJAX-based search by title or category
- 📊 Category stats on dashboard
- 🎨 Fully responsive and animated UI (Bootstrap + Animate.css)
- 🛡️ Prepared for future enhancements (Magic Link login, OTP, etc.)

---

## 🧱 Tech Stack

| Layer        | Technology                  |
|--------------|-----------------------------|
| Frontend     | HTML, CSS, Bootstrap, JS    |
| Backend      | PHP (No framework)          |
| Database     | MySQL                       |
| AJAX         | jQuery                      |
| UI Animation | Animate.css                 |

---

## 🚀 Live Demo

[🌐 Visit Live Site](https://link.nischalmandal.com)

---

## 🛠️ Installation & Setup

1. **Clone the project**

```bash
git clone https://github.com/yourusername/link-manager.git
cd link-manager
```

2. **Create a MySQL database**

Import the SQL schema from /database/schema.sql or use the provided SQL queries.

3. **Configure database connection**

Open /includes/db.php and edit the following:
```bash
$servername = "localhost";
$username = "your_db_user";
$password = "your_db_pass";
$dbname = "your_db_name";

```


##  Folder Structure

```
link-manager/
├── 📂 assets/
│   ├── 📂 css/
│   │   └── style.css        # Main CSS file for styling
│   ├── 📂 js/
│   │   ├── main.js          # Main JavaScript file for AJAX and UI interactivity
│   │   └── auth.js          # JavaScript for login/signup interactivity
│   └── 📂 images/           # Static images (logo, icons)
├── 📂 includes/
│   ├── db.php               # Database connection file
│   ├── functions.php        # Common functions (like login check, link CRUD)
│   ├── header.php           # Common header for all pages
│   └── footer.php           # Common footer for all pages
├── 📂 pages/
│   ├── dashboard.php        # Dashboard page for adding/viewing links
│   ├── login.php            # Login page
│   ├── register.php         # Registration page
│   └── logout.php           # Logout functionality
├── 📂 ajax/
│   ├── add_link.php         # Handle AJAX link addition
│   ├── search_link.php      # Handle AJAX link searching
│   └── add_category.php     # Handle AJAX category addition
├── 📂 uploads/
│   └── (optional uploads directory if you want users to upload files/images)
├── index.php                # Home page (with search functionality)
├── .htaccess                # For URL rewriting (optional)
└── README.md                # Project documentation
```