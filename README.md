<h1 align="center">🧭 Social Media Activity Tracker</h1>

<p align="center">
  A comprehensive, database-driven web application designed to capture, organize, and analyze user interactions on a social networking platform.
</p>

<p align="center">
  <b>Developed for:</b> 3rd Year, 1st Semester — <i>Database System Laboratory</i> <br>
  <b>Department of Computer Science and Engineering, KUET</b>
</p>

<hr>

<h2>💡 About the Project</h2>

<p>
  The <b>Social Media Activity Tracker</b> is a comprehensive, database-driven web application that replicates the key functionalities of a modern social networking platform. 
  It enables users to create, share, and interact with posts, manage detailed personal profiles, follow other users, and receive real-time notifications — all supported by 
  a well-structured and relational database system.
</p>

<p>
  The system demonstrates the integration of <b>MySQL</b> with the <b>Laravel (PHP)</b> framework using the <b>Model-View-Controller (MVC)</b> architecture, ensuring 
  data consistency, efficient storage, and secure access control. It also emphasizes practical applications of database normalization, indexing, 
  and referential integrity to support complex relationships among users, posts, and interactions.
</p>

<p>
  The <b>Social Media Activity Tracker</b> serves as both a functional prototype and an academic exploration into how social media platforms manage 
  large-scale user data, engagement activities, and community interactions in real time.
</p>

<hr>

<h2>🚀 Key Features</h2>

<ul>
  <li><b>User Authentication:</b> Secure registration, login, and logout.</li>
  <li><b>Profile Management:</b> Manage personal details, education, work experience, and extracurricular activities.</li>
  <li><b>Post Management:</b> Create, edit, delete, and share posts, Smart search by raw text.</li>
  <li><b>Social Interaction:</b> Follow and connect with other users.</li>
  <li><b>Engagement:</b> Like posts, comment, and reply to comments.</li>
  <li><b>Content Curation:</b> Save or bookmark posts for later viewing.</li>
  <li><b>Notifications:</b> Receive alerts for user interactions.</li>
  <li><b>Content Discovery:</b> Search and explore user-generated content.</li>
  <li><b>Moderation:</b> Report inappropriate content for review.</li>
</ul>

<hr>

<h2>🗃️ Database Schema</h2>

<p>The database is structured into multiple tables for efficient data management:</p>

<table>
  <tr><th>Table Name</th><th>Description</th></tr>
  <tr><td><b>users</b></td><td>Manages user profile information</td></tr>
  <tr><td><b>posts</b></td><td>Contains all user-generated posts</td></tr>
  <tr><td><b>categories</b></td><td>Defines various post categories</td></tr>
  <tr><td><b>likes</b></td><td>Keeps track of likes on posts</td></tr>
  <tr><td><b>comments</b></td><td>Stores user comments on posts</td></tr>
  <tr><td><b>follows</b></td><td>Tracks user-follower relationships</td></tr>
  <tr><td><b>post_saves</b></td><td>Stores bookmarked posts</td></tr>
  <tr><td><b>notifications</b></td><td>Contains system-generated notifications</td></tr>
  <tr><td><b>reported_posts</b></td><td>Stores reported posts and reasons</td></tr>
  <tr><td><b>educations</b></td><td>Stores academic records</td></tr>
  <tr><td><b>work_experiences</b></td><td>Stores professional experience</td></tr>
  <tr><td><b>extracurricular_activities</b></td><td>Contains extracurricular involvement</td></tr>
</table>

<hr>

<h2>🔗 Database Relationships</h2>

<ul>
  <li><b>Users ↔ Posts:</b> One-to-Many</li>
  <li><b>Posts ↔ Categories:</b> Many-to-One</li>
  <li><b>Users ↔ Comments:</b> One-to-Many</li>
  <li><b>Users ↔ Likes:</b> One-to-Many</li>
  <li><b>Users ↔ Post_User_Saves:</b> Many-to-Many</li>
  <li><b>Users ↔ Follows:</b> Many-to-Many (Self-Join)</li>
  <li><b>Users ↔ Reports:</b> One-to-Many</li>
  <li><b>Users ↔ Notifications:</b> One-to-Many</li>
  <li><b>Users ↔ Work_Experiences / Educations / Extra_Curricular_Activities:</b> One-to-Many</li>
</ul>

<p>
  These relationships ensure the system maintains <b>strong referential integrity</b> and <b>traceability</b> of all social interactions.
</p>

<hr>

<h2>🛠️ Technologies Used</h2>

<ul>
  <li><b>Backend Framework:</b> PHP (Laravel)</li>
  <li><b>Database:</b> MySQL</li>
  <li><b>Architecture:</b> Model-View-Controller (MVC)</li>
</ul>

<hr>

<h2>⚙️ Getting Started</h2>

<h3>🧩 Prerequisites</h3>

<ul>
  <li>PHP ≥ 8.1</li>
  <li>Composer</li>
  <li>MySQL</li>
  <li>Node.js & NPM (optional for frontend assets)</li>
</ul>

<h3>🧰 Installation Steps</h3>

<ol>
  <li><b>Clone the repository</b>
    <pre><code>git clone https://github.com/your_username/your_repository.git</code></pre>
  </li>

  <li><b>Navigate to the project directory</b>
    <pre><code>cd your_repository</code></pre>
  </li>

  <li><b>Install PHP dependencies</b>
    <pre><code>composer install</code></pre>
  </li>

  <li><b>Create your environment file</b>
    <pre><code>cp .env.example .env</code></pre>
  </li>

  <li><b>Generate the application key</b>
    <pre><code>php artisan key:generate</code></pre>
  </li>

  <li><b>Configure database credentials</b>
    <pre><code>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
    </code></pre>
  </li>

  <li><b>Run migrations and seeders</b>
    <pre><code>php artisan migrate --seed</code></pre>
  </li>

  <li><b>Start the development server</b>
    <pre><code>php artisan serve</code></pre>
  </li>
</ol>

<p>You can now access the application at:  
👉 <a href="http://127.0.0.1:8000" target="_blank">http://127.0.0.1:8000</a></p>

<hr>

<h2>👥 Authors & Acknowledgements</h2>

<ul>
  <li><b>Author:</b> Mehedi Hasan Rabby (me)</li>
  <li><b>Supervisors:</b>
  <ul>
    <li>
      <a href="https://www.linkedin.com/in/opi-brehampie/?originalSubdomain=bd" target="_blank">Md Mehrab Hossain Opi</a> – Lecturer, Department of Computer Science and Engineering, KUET
    </li>
    <li>
      <a href="https://www.linkedin.com/in/waliul034/?originalSubdomain=bd" target="_blank">Waliul Islam Sumon</a> – Lecturer, Department of Computer Science and Engineering, KUET
    </li>
  </ul>
</li>

</ul>

<hr>

<h3 align="center">⭐ If you find this project useful, consider giving it a star on GitHub!</h3>

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<hr>

<h2>🏁 License</h2>

<p>
  The Laravel framework is open-sourced software licensed under the <a href="https://opensource.org/licenses/MIT">MIT license</a>.<br>
  This project was developed for academic purposes only as part of the <i>Database System Laboratory</i> course at KUET.  
  You may use or modify it for educational and research purposes.
</p>
