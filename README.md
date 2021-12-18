##Project start 
###1. Clone project

###2. run composer install if required:
docker run --rm --interactive --tty --volume $(pwd):/app composer install

###3. Config .env file

###4. Create project
docker-compose up -d

###5. Open php container and insert DB
php artisan migrate --seed

##Test 

##Add users
POST http://localhost/api/addUser

Accept: application/json

Content-Type: application/json

[
    {
        "name":"Jericho",
        "email":"jericho@gmail.com",
        "password": "password",
        "country_id": "9"
    },

    {
        "name":"Babylon",
        "email":"babylon@gmail.com",
        "password":"password",
        "country_id": "9"
    }
]


##Verify User
?email=...&remember_token=...

GET http://localhost/api/login?email=...&remember_token=...

Accept: application/json

##Get user
GET http://localhost/api/user

Accept: application/json

Authorization: ...


##List users with filters
?names[]=...e&emails[]=...&verify[]=true/false&countries[]=...

GET http://localhost/api/users/list?countries[]=Bermuda

Accept: application/json


##Edit users
POST http://localhost/api/users/edit

Accept: application/json

Content-Type: application/json

[
    {
        "id":"21",
        "name":"edit test 1"
    },
    
    {
        "id":"23",
        "name":"edit test 2",
        "email":"edit12@mail.com"
    }
]


##Delit users
DELETE http://localhost/api/users/destroy?id[]=...&id[]=...

Authorization: Bearer ...

Accept: application/json






##Add projects
POST http://localhost/api/projects/add

Authorization: Bearer ...

Accept: application/json

Content-Type: application/json

[
    {
        "project_name":"Test project 1"
    },
    
    {
        "project_name":"Test project 2"
    }
]


##link projects
POST http://localhost/api/projects/link

Authorization: Bearer ...

Accept: application/json

Content-Type: application/json

[
    {
        "user_id":21,
        "project_id":1
    },
    
    {
        "user_id":23,
        "project_id":1
    },
    
    {
        "user_id":23,
        "project_id":2
    }
]


##List Projects
?emails[]=...&continents[]=...&labels[]=...

GET http://localhost/api/projects/list?emails[]=...&continents[]=...

Authorization: Bearer ...

Accept: application/json

Content-Type: application/json


##Delete Projects
DELETE http://localhost/api/projects/destroy?id[]=...

Authorization: Bearer ...

Accept: application/json



##Create new label
POST http://localhost/api/labels/store

Authorization: Bearer ...

Content-Type: application/json

Accept: application/json

[
    {
        "label_body":"Test label 1"
    },
    
    {
        "label_body":"Test label 2"
    }
]

##Link labels too project
POST http://localhost/api/labels/link

Authorization: Bearer ...

Content-Type: application/json

Accept: application/json

[
    {
        "project_id":1,
        "label_id":21
    },
    
    {
        "project_id":2,
        "label_id":22
    }
]

##List all labels with filters
?emails[]=...&projects[]=...

GET http://localhost/api/labels/list?emails[]=...&projects[]=...

Authorization: Bearer ...

Accept: application/json

##Delete labels
DELETE http://localhost/api/labels/destroy

Authorization: Bearer ...

Accept: application/json


