### Update Voucher

This endpoint is used to update a voucher using the provided reference number of the voucher.

#### URL

```
{{base_url}}/api/voucher/update/{voucherReferenceNumber}
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
    "code" : "1233",
    "value" : 25,
    "usage" : 1,
    "status" : "ACTIVE",
    "expires_at": "2025-12-31"

}
```

#### Response Example (Success)

```json
{
    "message": "Voucher updated",
    "results": {
        "referenceNumber": "114e2f",
        "code": "1233",
        "discount": "25%",
        "usage": 1,
        "status": "ACTIVE",
        "expiresAt": "2025-12-31 00:00:00"
    },
    "code": 200,
    "error": false
}
```
