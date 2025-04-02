### Show Voucher

This endpoint retrieves information about a specific voucher using the provided reference number of the voucher.

#### URL

```
{{base_url}}/api/voucher/{voucherReferenceNumber}
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
    "message": "Voucher found",
    "results": {
        "referenceNumber": "114431",
        "code": "Early Bird",
        "discount": "20%",
        "usage": 1,
        "status": "ACTIVE",
        "expiresAt": "2025-12-31 00:00:00"
    },
    "code": 200,
    "error": false
}
```
