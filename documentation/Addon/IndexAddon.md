### Index Amenity

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
            "price": "8.45"
        },
        {
            "name": "EXTRA TOWEL",
            "price": "4.97"
        },
        {
            "name": "CHAMPAGNE BOTTLE",
            "price": "5.97"
        },
        {
            "name": "AIRPORT PICKUP",
            "price": "6.77"
        },
        {
            "name": "AIRPORT DROPOFF",
            "price": "9.75"
        }
    ],
    "code": 200,
    "error": false
}
```
