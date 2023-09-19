<h1 align="center">
    <img src="logo.png" alt="application logo">
</h1>

<p align="center">
    <a href="#features">Features</a>&nbsp;&nbsp; | &nbsp;
    <a href="#screenshots">Screenshots</a>&nbsp; | &nbsp;    
    <a href="#getting-started">Getting started</a>&nbsp; | &nbsp;
    <a href="#configuration">Configuration</a>&nbsp; | &nbsp;
    <a href="#used-technologies-and-tools">Technologies and tools</a>&nbsp; | &nbsp;
    <a href="#license">License</a>
</p>

# About

LearnIn is the next generation learning management system. Provides constant monitoring of students in the progress of teaching. Create courses and tasks, issue grades for students, check uploaded files by students, add course and task referential files, generate statistics, and more. Powered by Laravel Framework for backend, Vue.js for frontend and MariaDB for database.

<div align="center">
    <img src="screenshots/dashboard.jpg" alt="dashboard">
    LearnIn dashboard
</div>

# Features

-   provides three types of user: administrator, teacher and student
-   create courses and assign users them
-   create categories to the course
-   create tasks related with course categories
-   issue grades for students
-   easily assign new users to the course
-   watch uploaded files by students and download .zip with all files
-   upload course files (ex. lectures, books, etc.), task referential files (ex. instructions, executable programs, etc.) and student file to the task
-   administration panel for managing all courses, users and tasks
-   generate students marks statistics from specific task or whole course category
-   user-friendly, responsive layout
-   multilingual support (available now: English and Polish)

# Screenshots

<img src="screenshots/courseView.jpg" alt="course view">
<img src="screenshots/taskView.jpg" alt="task view">
<img src="screenshots/courses.jpg" alt="courses">
<img src="screenshots/tasks.jpg" alt="tasks">
<img src="screenshots/marks.jpg" alt="marks">
<img src="screenshots/studentUploads.jpg" alt="students uploads">
<img src="screenshots/studentMarks.jpg" alt="students marks">
<img src="screenshots/userView.jpg" alt="user view">
<img src="screenshots/statistics.jpg" alt="statistics">
<img src="screenshots/exampleForm.jpg" alt="example form">
<img src="screenshots/exampleForm2.jpg" alt="example form second">
<img src="screenshots/courseParticipants.jpg" alt="course participants modal">
<img src="screenshots/courseParticipants2.jpg" alt="course participants modal second">
<img src="screenshots/upload.jpg" alt="file upload">
<img src="screenshots/teacherInformationEdit.jpg" alt="teacher information edit modal">
<img src="screenshots/adminExample.jpg" alt="admin example">

# Getting started

Full installation guide for most users with requirements was described in [Standard installation](INSTALLATION.md#standard-installation).

There are three ways to deploy LearnIn:
* [production](INSTALLATION.md#standard-installation) - for most users, prepared to deploy on your web server, 
* [development](INSTALLATION.md#development-environment) - for feature development,
* [docker](INSTALLATION.md#docker-development) - docker environment.

# Configuration

You can configure the project by your own needs.
To do this, open .env file (cloned from .env.dev or .env.prod or .env.docker) and change parameters.

1. [Database credentials](INSTALLATION.md#project-location)

2. [Project directory](INSTALLATION.md#database)

3. [Localization - language](INSTALLATION.md#localization)

4. [Apache configuration - optional](INSTALLATION.md#apacheconf)

5. [NGINX configuration - optional](INSTALLATION.md#nginxconf)
   
6. It's highly recommend to run the scheduler (that cleans old generated .zip files by teachers every 3 hours):

**In Docker:**
```
docker exec -it app sh -c "php /var/www/html/artisan schedule:work"
```

**Locally:**
```
php artisan schedule:work
```

# Used technologies and tools

-   HTML5
-   PHP Laravel Framework v10.15.0
-   MariaDB
-   Apache
-   Tailwind CSS v3.3.3
-   Vue 3 and libraries:
    -   Vuex
    -   Axios
    -   Lodash
    -   laravel-vue-i18n
    -   vue-router
    -   DayJS
    -   vue-multiselect
    -   vue-toastification
    -   vue3-popper
    -   vuejs-paginate-next
    -   vue-modal
-   Docker and Docker Compose
-   bundler: Vite
-   IDE and tools: Jetbrains PHPStorm, Postman, Jetbrains DataGrip

# License

Distributed under the MIT License.
