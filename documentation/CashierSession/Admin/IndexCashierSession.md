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
    "message": "Success, all cashier sessions have been retrieved.",
    "results": [
        {
            "id": 1,
            "status": "ACTIVE",
            "opened_at": "2025-03-21T03:44:23.177816Z",
            "closed_at": null,
            "opening_balance": "5000.00",
            "closing_balance": null,
            "user": {
                "id": 1,
                "name": "John Doe"
            }
        },
        {
            "id": 2,
            "status": "ACTIVE",
            "opened_at": "2025-03-21T03:44:23.177816Z",
            "closed_at": null,
            "opening_balance": "5000.00",
            "closing_balance": null,
            "user": {
                "id": 2,
                "name": "Jane Doe"
            }
        }
    ],
    "code": 200,
    "error": false
}
```
