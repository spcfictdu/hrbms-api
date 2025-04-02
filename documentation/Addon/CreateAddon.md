### Create Addon

This endpoint is used to create a new addon.

#### URL

```
{{base_url}}/api/addon/create
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
    "name": "EXTRA BEDSHEET",
    "price": 10
}
```

#### Response Example (Success)

```json
{
    "message": "Addon created successfully.",
    "results": {
        "referenceNumber": "87ed9a",
        "name": "EXTRA BEDSHEET",
        "price": 10
    },
    "code": 200,
    "error": false
}
```
