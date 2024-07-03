### Index Guest

This endpoint is used to get the list of all guests.

#### URL

```
{{base_url}}/api/guest
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

-   page : INTEGER (optional) - The page number to retrieve. Default is 1.
-   perPage : INTEGER (optional) - The number of items to retrieve per page. Default is 10.

-   referenceNumber : STRING (optional) - The reference number of the guest
-   firstName : STRING (optional) - The first name of the guest
-   middleName : STRING (optional) - The middle name of the guest
-   lastName : STRING (optional) - The last name of the guest
-   fullName : STRING (optional) - The full name of the guest
-   email : STRING (optional) - The email of the guest
-   phone : STRING (optional) - The phone number of the guest

#### Response Example (Success)

```json
{
    "message": "Successfully retrieved guests",
    "results": {
        "guests": [
            {
                "id": 1,
                "referenceNumber": "64065b9b",
                "fullName": "LITTEL, ANSEL LIND",
                "email": "mpurdy@yahoo.com",
                "phone": "2691752667"
            },
            {
                "id": 2,
                "referenceNumber": "5dd2c2a5",
                "fullName": "LARKIN, FRANKIE PFANNERSTILL",
                "email": "kuhlman.jimmy@haley.info",
                "phone": "5458958787"
            },
            {
                "id": 3,
                "referenceNumber": "b0d7395f",
                "fullName": "MARQUARDT, LELIA BEATTY",
                "email": "otto69@gmail.com",
                "phone": "9470218251"
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
