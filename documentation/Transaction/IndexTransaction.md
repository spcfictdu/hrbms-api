### Index Transaction

This endpoint is used to get the list of all transactions.

#### URL

```
{{base_url}}/api/transaction
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
FRONT DESK
```

#### Query Parameters

#### Pagination

-   `page` - The page number of the transactions list. Default is 1.
-   `perPage` - The number of transactions to display per page. Default is 10.

#### Filters

-   `firstName` - The first name of the guest.
-   `middleName` - The middle name of the guest.
-   `lastName` - The last name of the guest.
-   `referenceNumber` - The reference number of the transaction.
-   `checkInDate` - The check-in date of the transaction.
-   `checkOutDate` - The check-out date of the transaction.

#### Response Example (Success)

```json
{
    "message": "List of all transactions.",
    "results": [
        {
            "fullName": "MORAR, FAYE ",
            "status": "CHECKED-IN",
            "transactionRefNum": "a8d1826a",
            "occupants": "2",
            "checkInDate": "2024-05-22",
            "checkOutDate": "2024-05-23",
            "booked": "2024-05-22",
            "room": 101,
            "total": 3000
        },
        {
            "fullName": "OBERBRUNNER, WELLINGTON ",
            "status": "CHECKED-OUT",
            "transactionRefNum": "bb30f69b",
            "occupants": "2",
            "checkInDate": "2024-05-23",
            "checkOutDate": "2024-05-24",
            "booked": "2024-05-22",
            "room": 201,
            "total": 3000
        },
        {
            "fullName": "STOLTENBERG, JULIEN ",
            "status": "CONFIRMED",
            "transactionRefNum": "14980997",
            "occupants": "2",
            "checkInDate": "2024-05-24",
            "checkOutDate": "2024-05-25",
            "booked": "2024-05-22",
            "room": 301,
            "total": 3000
        },
        ...
    ],
    "code": 200,
    "error": false
}
```
