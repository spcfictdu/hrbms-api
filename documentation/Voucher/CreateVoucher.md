### Create Voucher

This endpoint is used to create a new voucher.

#### URL

```
{{base_url}}/api/voucher/create
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
    "code" : "Early Bird",
    "value" : 20,
    "usage" : 1,
    "expires_at": "2025-12-31"

}
```

#### Response Example (Success)

```json
{
    "message": "Voucher created successfully",
    "results": {
        "referenceNumber": "f4ff6a",
        "code": "Early Bird",
        "value": 2,
        "usage": 1,
        "status": "ACTIVE",
        "expiresAt": "2025-12-31"
    },
    "code": 200,
    "error": false
}
```
