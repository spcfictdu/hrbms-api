# **Room Enum API Documentation**

This endpoint is used in the frontend to fetch room numbers dynamically, often for dropdown selection.

## **Endpoint**

```
{{base_url}}/api/enum/room
```

## **Method**

```
GET
```

## **Authentication Required**

```
No
```

## **Permitted Roles**

```
Admin, Front Desk
```

## **Query Parameters**

| Parameter          | Type   | Required                            | Description                                                                                                                |
| ------------------ | ------ | ----------------------------------- | -------------------------------------------------------------------------------------------------------------------------- |
| `roomType`         | STRING | Optional                            | Filters rooms by type. Example: `DELUXE`                                                                                   |
| `roomNumber`       | INT    | Optional                            | Filters by a specific room number. Example: `101`                                                                          |
| `dateRange`        | STRING | Optional                            | Filters rooms available within the specified date range. Format: `YYYY-MM-DD,YYYY-MM-DD`. Example: `2024-05-23,2024-05-25` |
| `extraPersonCount` | INT    | Optional                            | Filters rooms based on the number of extra persons allowed. Example: `1`                                                   |
| `discount`         | STRING | Optional                            | Applies a discount filter. Example: `VOUCHER`, `PWD`, `SNR`.                                                               |
| `voucherCode`      | STRING | Required if `discount` is `VOUCHER` | Filters rooms that accept a specific voucher code. Example: `1234`.                                                        |
| `addons`           | STRING | Optional                            | Filters rooms based on included add-ons. Example: `EXTRA PILLOW-1`                                                         |

## **Success Response Example**

```json
{
    "message": "Rooms fetched successfully",
    "results": [
        {
            "referenceNumber": "356de3b5",
            "roomNumber": 101,
            "roomFloor": 1,
            "roomType": "JUNIOR STANDARD",
            "roomTypeCapacity": 2,
            "roomRateType": "REGULAR",
            "roomRatesArray": [
                {
                    "date": "2024-05-23",
                    "dayOfWeek": "Thursday",
                    "rate": 1340,
                    "extraPersonRate": 335
                },
                {
                    "date": "2024-05-24",
                    "dayOfWeek": "Friday",
                    "rate": 1340,
                    "extraPersonRate": 335
                },
                {
                    "date": "2024-05-25",
                    "dayOfWeek": "Saturday",
                    "rate": 1440,
                    "extraPersonRate": 360
                }
            ],
            "addons": [
                {
                    "name": "Extra Pillow",
                    "quantity": 1,
                    "unit_price": 2.24,
                    "total": 2.24
                },
                {
                    "name": "Extra Towel",
                    "quantity": 3,
                    "unit_price": 8.65,
                    "total": 25.95
                }
            ],
            "discount": "10%",
            "roomTotal": 4120,
            "extraPersonCount": null,
            "extraPersonTotal": 0,
            "roomTotalWithExtraPerson": 4120
        }
    ],
    "code": 200,
    "error": false
}
```
