### Index Addon

This endpoint retrieves the list of all addons.

#### URL

```
{{base_url}}/api/addon/
```

#### Method

```
GET
```

#### Authentication Needed

```
TRUE
```

#### Permitted Roles

```
ADMIN
```

#### Response Example (Success)

```json

{
    "message": "list of all addons",
    "results": [
        {
            "name": "EXTRA PILLOW ",
            "price": "3.15"
        },
        {
            "name": "EXTRA TOWEL",
            "price": "3.76"
        },
        {
            "name": "CHAMPAGNE BOTTLE",
            "price": "1.90"
        },
        {
            "name": "AIRPORT PICKUP",
            "price": "9.24"
        },
        {
            "name": "AIRPORT DROPOFF",
            "price": "7.45"
        },
        {
            "name": "EXTRA BEDSHEET",
            "price": "89.00"
        },
    ],
    "code": 200,
    "error": false
}
```
