### Update Amenity

This endpoint is used to update an addon using the provided reference number of the addon.

#### URL

```
{{base_url}}/api/addon/update/{addonReferenceNumber}
```

#### Method

```
PUT
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
    "price": 89
}
```

#### Response Example (Success)

```json
{
    "message": "Addon updated successfully.",
    "results": {
        "referenceNumber": "9f061e",
        "name": "EXTRA BEDSHEET",
        "price": 89
    },
    "code": 200,
    "error": false
}
```
