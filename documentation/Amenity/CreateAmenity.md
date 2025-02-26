### Create Amenity

This endpoint is used to create a new amenity.

#### URL

```
{{base_url}}/api/amenity/create
```

#### Method

```
POST
```

#### Authentication Needed

```
TRUE
```

#### Permitted Roles

```
ADMIN
```

#### Request Body

```json
{
    "name": "FREE BREAKFAST",
    "price": 0
}
```

#### Response Example (Success)

```json
{
    "message": "Amenity created successfully.",
    "results": {
        "referenceNumber": "61956e",
        "name": "FREE BREAKFAST"
    },
    "code": 200,
    "error": false
}
```
