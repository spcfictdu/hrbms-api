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
    "results": {
        "data": [
            {
                "fullName": "LITTEL, ANSEL LIND",
                "status": "CHECKED-OUT",
                "transactionRefNum": "5226f069",
                "occupants": "2",
                "checkInDate": "2024-05-24",
                "checkOutDate": "2024-05-25",
                "booked": "2024-05-24",
                "room": 101,
                "total": 3000
            },
            {
                "fullName": "LARKIN, FRANKIE PFANNERSTILL",
                "status": "CONFIRMED",
                "transactionRefNum": "3477f092",
                "occupants": "2",
                "checkInDate": "2024-05-25",
                "checkOutDate": "2024-05-26",
                "booked": "2024-05-24",
                "room": 201,
                "total": 3000
            },
            {
                "fullName": "MARQUARDT, LELIA BEATTY",
                "status": "CHECKED-IN",
                "transactionRefNum": "3e4b9ea5",
                "occupants": "2",
                "checkInDate": "2024-05-26",
                "checkOutDate": "2024-05-27",
                "booked": "2024-05-24",
                "room": 301,
                "total": 3000
            },
            ...
        ],
        "pagination": {
            "total": 8,
            "perPage": 10,
            "currentPage": 1,
            "lastPage": 1,
            "from": 1,
            "to": 8,
        }
    },
    "code": 200,
    "error": false
}
```
