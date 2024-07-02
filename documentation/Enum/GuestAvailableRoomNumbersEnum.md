# Guest Enum

This endpoint is used for dropdowns in the frontend to display the guest names.

#### URL

```
{{base_url}}/api/enum/guest/available-room-numbers
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
ALL
```

#### Query Parameters

-   `roomType` - The type of room to get the available room numbers for. (e.g. `JUNIOR STANDARD`)
-   `checkInDate` - The check-in date of the reservation. (e.g. `2021-12-25`)
-   `checkOutDate` - The check-out date of the reservation. (e.g. `2021-12-27`)

#### Response Example (Success)

```json
{
    "message": "Available room numbers retrieved successfully.",
    "results": [111, 116],
    "code": 200,
    "error": false
}
```
