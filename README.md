# Backend for Kanban (Simple Trello Clone)


###Installation
```
PHP Version used: 7.3.33
Laravel Framework 7.30.6

run composer install
run cp .env.example .env
update database configuration in .env file
run php artisan migrate
Seeder php artisan db:seed
configure your ACCESS_TOKEN in .env file
```

###Routes
```
GET     /api/list-cards         - Get all cards

Params:
Required
access_token
Optional
date
status

Sample Request:
GET api/list-cards?access_token=TOKEN_HERE&date=2023-01-10&status=0


Sample Response:
[
    {
        "id": 1,
        "title": "Persevering neutral data-warehouse",
        "description": "Dolores qui reiciendis esse.",
        "column_id": 1,
        "order": 2,
        "deleted_at": null,
        "created_at": "2023-01-09T05:50:46.000000Z",
        "updated_at": "2023-01-09T05:57:49.000000Z"
    },
    {
        "id": 2,
        "title": "Pre-emptive bi-directional firmware",
        "description": "Minima non nam non.",
        "column_id": 1,
        "order": 1,
        "deleted_at": null,
        "created_at": "2023-01-09T05:50:46.000000Z",
        "updated_at": "2023-01-09T05:57:49.000000Z"
    }
]

```



