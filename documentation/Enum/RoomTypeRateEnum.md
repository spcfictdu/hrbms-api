### Room Type Rate Enum

This endpoint is used for dropdowns in the frontend to display the room types.

#### URL

```
{{base_url}}/api/enum/room-type-rate
```

#### Method

```
GET
```

#### Authentication Needed

```
FALSE
```

#### Permitted Roles

```
NO ROLE NEEDED
```

#### Query Parameters

-   referenceNumber (optional) - The reference number of the room type rate to fetch. If not provided, all room type rates will be fetched.
-   roomType (optional) - The room type to filter the room type rates. If not provided, all room type rates will be fetched.
-   rateType (optional) - The rate type to filter the room type rates. If not provided, all room type rates will be fetched.
-   discountName (optional) - The discount name to filter the room type rates. If not provided, all room type rates will be fetched.

#### Response Example (Success)

```json
{
    "message": "Room Type Rate Enum",
    "results": [
        {
            "id": 6,
            "referenceNumber": "RT-0001",
            "roomType": "JUNIOR STANDARD",
            "discountName": "DREAMSTAY DISCOUNT",
            "type": "SPECIAL",
            "startDate": "2021-01-01",
            "endDate": "2021-12-31",
            "monday": 1000,
            "tuesday": 1000,
            "wednesday": 1000,
            "thursday": 1000,
            "friday": 1000,
            "saturday": 1000,
            "sunday": 1000
        }
    ],
    "code": 200,
    "error": false
}
```
