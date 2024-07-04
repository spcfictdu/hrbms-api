### Account Info

This endpoint is used to display all related data on guest account.

#### URL

```
{{base_url}}/guest/account/
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
FRONT DESK
GUEST
```

#### Query Parameters

#### Response Example (Success)

```json
{
    "message": "Acoount Information and Previous Transaction Histories",
    "results": {
        "accountInfo": {
            "fullName": "CAMADDO, ALEX M",
            "address": {
                "province": null,
                "city": null
            },
            "email": "try1@gmail.com",
            "phone": "09605607559"
        },
        "bookings": [],
        "reservation": [],
        "histories": []
    },
    "code": 200,
    "error": false
}
```
