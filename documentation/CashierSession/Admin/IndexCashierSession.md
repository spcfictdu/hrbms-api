### Index Cashier Session

This endpoint is used to show all cashier sessions.

#### URL

```
{{base_url}}/api/cashier-session
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

#### Query Parameters

##### Filter

| Parameter  | Description                              | Required |
| ---------- | ---------------------------------------- | -------- |
| `status`   | The status of the cashier session.       | NO       |
| `openedAt` | The date the cashier session was opened. | NO       |
| `closedAt` | The date the cashier session was closed. | NO       |

##### Search

| Parameter | Description                               | Required |
| --------- | ----------------------------------------- | -------- |
| `search`  | The search query for the cashier session. | NO       |

#### Pagination

| Parameter   | Description                                                                             | Required |
| ----------- | --------------------------------------------------------------------------------------- | -------- |
| `page`      | The page number.                                                                        | NO       |
| `perPage`   | The number of items per page.                                                           | NO       |
| `sortBy`    | The column to sort by. **(openingBalance, closingBalance, openedAt, closedAt, status)** | NO       |
| `sortOrder` | The sort order. **(desc, asc)**                                                         | NO       |

#### Response Example (Success)

```json
{
    "message": "Successfully retrieved cashier sessions",
    "results": [
        {
            "openingBalance": "1500.00",
            "closingBalance": null,
            "openedAt": "2025-06-24 10:30:49",
            "closedAt": null,
            "status": "ACTIVE",
            "userFullName": "JANE DOE",
            "userId": 64,
            "payments": [
                {
                    "name": "GCASH",
                    "totalAmount": "1858.00"
                },
                {
                    "name": "CASH",
                    "totalAmount": "2500.00"
                },
                {
                    "name": "CREDIT_CARD",
                    "totalAmount": "1340.00"
                },
                {
                    "name": "CHEQUE",
                    "totalAmount": "0.00"
                }
            ]
        }
    ],
    "code": 200,
    "error": false
}
```
