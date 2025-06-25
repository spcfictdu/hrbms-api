### Start User Cashier Session

This endpoint is used to start a user cashier session.

#### URL

```
{{base_url}}/api/cashier-session/user/start
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
FRONT DESK
```

#### Query Parameters

#### Pagination

| Parameter   | Description                                                                             | Required |
| ----------- | --------------------------------------------------------------------------------------- | -------- |
| `page`      | The page number.                                                                        | NO       |

#### Response Example (Success)

```json
{
    "message": "Success, the user's cashier history has been opened.",
    "results": {
        "data": [
            {
                "userId": 64,
                "openedAt": "2025-06-24 10:30:49",
                "closedAt": null,
                "status": "ACTIVE",
                "payments": [
                    {
                        "paymentId": 10,
                        "guestName": "LANGOSH, REGGIE TORPHY",
                        "paymentType": "GCASH",
                        "amountReceived": "1858.00",
                        "roomTotal": "1858.00",
                        "addOnTotal": 0,
                        "discount": "185.80",
                        "createdAt": "2025-06-24T02:32:19.000000Z"
                    }
                ]
            },
            {
                "userId": 64,
                "openedAt": "2025-06-24 09:56:00",
                "closedAt": "2025-06-24 10:30:35",
                "status": "INACTIVE",
                "payments": [
                    {
                        "paymentId": 9,
                        "guestName": "WILDERMAN, DANIELLE MERTZ",
                        "paymentType": "CHEQUE",
                        "amountReceived": "2500.00",
                        "roomTotal": "2173.00",
                        "addOnTotal": 0,
                        "discount": "217.30",
                        "createdAt": "2025-06-24T02:02:21.000000Z"
                    }
                ]
            }
        ],
        "meta": {
            "current_page": 1,
            "last_page": 3,
            "per_page": 2,
            "total": 5,
            "next_page_url": "http://127.0.0.1:8000/api/cashier-session/64/show-history?page=2",
            "prev_page_url": null
        }
    },
    "code": 200,
    "error": false
}
```
