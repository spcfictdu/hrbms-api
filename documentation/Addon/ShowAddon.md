### Show Addon

This endpoint retrieves information about a specific addon using the provided reference number of the addon.

#### URL

```
{{base_url}}/api/addon/{addonReferenceNumber}
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
    "message": "Addon found.",
    "results": {
        "referenceNumber": "9f061e",
        "name": "EXTRA BEDSHEET",
        "price": "89.00"
    },
    "code": 200,
    "error": false
}
```
