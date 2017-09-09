# Job Hunter App
Helps you with your job search.

### User Stories
1. User can sign up and login to manage job search
2. User can

### Models
```
User
|__ id
|__ username
|__ password

Job Site
|__ id
|__ name
|__ link

Job
|__ id
|__ title VARCHAR
|__ company_name VARCHAR
|__ company_site
|__ content TEXT
|__ cover_letter TEXT
|__ job_site FOREIGN KEY
|__ applied_date
|__ created_date
|__ status --> (N)NO RESPONSE, (C)CALLED BACK, (I)INTERVIEWED, (A)ACCEPTED, (R)REJECTED

```

### Files / Folders
```
index.php - login / register
job_site.php - CRUD for job sites
job_application.php - 1. table list of job applications and 2. add job application 3. delete job application
job_edit.php - Add job application
stats.php - Stats dashboard for job applications
logout.php
/static/style.css
/includes/connection.php - MySQL connection
/includes/validation.php - Validators
/includes/header.php
/includes/footer.php
```
### Author
Neptune Michael Cabelin

### License
MIT
