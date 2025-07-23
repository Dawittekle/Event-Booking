# 🎉 Event-Booking (Venue Link)

<img width="1354" height="642" alt="image" src="https://github.com/user-attachments/assets/5066821c-020e-43e8-8583-3f9603f22664" />


A web platform that connects individuals and organizations with venue owners for all types of events. Whether you're planning a wedding, corporate event, or concert, **Venue Link** helps you find and book the perfect venue — quickly and effortlessly.

---

## 📝 Description

**Venue Link** bridges the gap between event organizers and venue owners. Users can easily browse, find, and book venues for:

* Weddings
* Corporate Events
* Trade Shows
* Product Launches
* Graduations
* Concerts
* Private Parties
* Conferences
* Seminars
* Workshops

Venue owners can register and manage their listings, making their venues visible and bookable to a broad audience. This system reduces the time and stress involved in finding and securing event spaces.

---

## 🧰 Tech Stack

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)


---

## ⚙️ Installation

To run the Event-Booking project locally, follow the steps below. We recommend using **XAMPP** as your local development environment.

### Prerequisites

* [XAMPP](https://www.apachefriends.org/) (includes PHP, MySQL, and Apache)

### Steps

1. **Download or Clone the Repository**

   ```bash
   git clone https://github.com/Dawittekle/event-booking.git
   ```

2. **Move the Project to XAMPP**

   Copy the project folder (`event-booking`) into your XAMPP `htdocs` directory:

   ```
   C:\xampp\htdocs\
   ```

3. **Start XAMPP Services**

   * Open **XAMPP Control Panel**
   * Start **Apache** and **MySQL**

4. **Set Up the Database**

   * Open your browser and go to:
     [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   * Click on **Import** from the top menu.
   * Choose the included SQL file (`event_booking.sql`) located in the project directory.
   * Click **Go** to import the database schema and tables.

5. **Configure Database Connection**

   * Open the configuration file (e.g., `config.php` or `db.php` depending on your setup).
   * Update your MySQL credentials (typically no password on local XAMPP):

     ```php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "event_booking"; // Match the name you used when importing
     ```

6. **Run the Application**

   Open your browser and visit:

   ```
   http://localhost/event-booking
   ```

---

## 📸 Screenshots

> Replace this section with images showing how the site looks and works:

* Homepage

  
  <img width="1097" height="604" alt="image" src="https://github.com/user-attachments/assets/249b2c9e-34c0-4166-9fea-ffae3681a167" />

  

* Venue listings

  
  <img width="965" height="643" alt="image" src="https://github.com/user-attachments/assets/259d16f4-7fdf-4ad2-9f38-ec590055883e" />



* Booking form

  
  <img width="727" height="644" alt="image" src="https://github.com/user-attachments/assets/7e75d3d4-702e-424e-8bd4-77e8b9871d8e" />

  

* Dashboard

  
  <img width="779" height="375" alt="image" src="https://github.com/user-attachments/assets/52f577b9-4cd6-4d76-913e-4d1efc881a21" />

  

* Register form

  
  <img width="1327" height="628" alt="image" src="https://github.com/user-attachments/assets/b2f4b164-cc96-4b49-869b-73ca5f842662" />

  


## 📌 Usage Notes

* **Use the provided SQL file** to initialize the required database structure.
* Users must **register** and **log in** to book venues.
* Venue owners must **register their venue** through the portal.

---

## 👥 Author

**Dawit Teklebrhan**
GitHub: [@Dawittekle](https://github.com/Dawittekle)

Special thanks to the team members who contributed to the development of this project.

---

## 📄 License

This project is licensed under the **MIT License**. You’re free to use, modify, and distribute it with attribution.

---

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change or improve.
