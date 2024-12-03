# Food-ordering-website

This is a web-based food ordering system developed using PHP, JavaScript, HTML, and CSS. The application allows users to register, log in, view the restaurant's menu, and place orders conveniently. The backend is powered by a MySQL database and hosted locally using XAMPP.


## **🌟 <ins>Features</ins>**

**1. 🔐 User Authentication**

   *Register:* Users can create accounts with secure credentials.

   *Login:* Access personalized features after logging in, including viewing order history.

**2. 🍽️ Menu Management**

   *View Menu:* Users can browse a comprehensive menu with detailed descriptions of each dish.

   *Add to Cart:* Users can select items to add to their cart for easy ordering.

**3. 🛒 Shopping Cart**

   *Manage Cart:* Users can view items in their cart, update quantities, or remove items before checkout.

   *Checkout Process:* After finalizing their selections, users can proceed to checkout to confirm their order.

## 🛠️ **<ins>Tech Stack</ins>**

**1. 🔗 Backend**

  *MySQL:* For storing user data and menu items.

   *PHP:* Server-side scripting language for handling requests and database interactions.

**2. 🔗 Frontend**

   *HTML/CSS:* For structuring and styling the web pages.

   *JavaScript:* For enhancing user interaction and managing dynamic content.

**4. 🔗 Server Environment**

   *XAMPP:* A cross-platform solution that provides Apache server and MySQL database for local development.


## **<ins>Implementation Overview</ins>**

**1. Setting Up XAMPP:**

   Install XAMPP to create a local server environment.
    
   Start Apache and MySQL through the XAMPP control panel.

**2. Creating the Database:**

   Use phpMyAdmin (accessible via http://localhost/phpmyadmin) to create a database for the application.

   Define tables for users and menu items with appropriate fields.

**3. Developing the Application:**

   Create HTML files for registration (register.html), login (login.html), menu display (menu.php), and checkout (checkout.php).

   Use PHP scripts to handle form submissions, authenticate users, and interact with the database.

**4. Styling the Application:**

  Use CSS for styling the layout and improving user experience.
    
  Implement JavaScript for dynamic features like updating cart totals without refreshing the page.

**5. Testing the Application:**

  Access the application via http://localhost/[your_project_directory] in a web browser to test functionality such as registration, login, menu browsing, adding items to the cart, and checking out.
