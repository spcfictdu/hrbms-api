### Show Room Type

This endpoint retrieves information about a specific room type using the provided reference number of the room type.

#### URL

```
{{base_url}}/api/room-type/{roomTypeReferenceNumber}
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

-   `search` - The search query to filter the results. Default is `null`.
-   `perPage` - The number of results to show per page. Default is 10.
-   `page` - The page number to show. Default is 1.
-   `sortBy` - The column to sort by. Default is `name`.
-   `sortOrder` - The order to sort by. Default is `asc`.

#### Response Example (Success)

```json
{
    "message": "Room type found.",
    "results": {
        "referenceNumber": "ee0cf0ea",
        "name": "STANDARD",
        "description": "This modern room comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom.",
        "bedSize": "1 queen bed",
        "propertySize": "15 m²/161 ft²",
        "isNonSmoking": true,
        "balconyOrTerrace": false,
        "capacity": 2,
        "extraPersonCapacity": 3,
        "images": [
            "ee0cf0ea/storage/ee0cf0ea/RoomPic_0.png",
            "ee0cf0ea/storage/ee0cf0ea/RoomPic_1.png",
            "ee0cf0ea/storage/ee0cf0ea/RoomPic_2.png",
            "ee0cf0ea/storage/ee0cf0ea/RoomPic_3.png"
        ],
        "amenities": [
            "AIR CONDITIONING",
            "BATHTUB",
            "SATELLITE/CABLE TV",
            "TELEPHONE",
            "FREE WI-FI",
            "REFRIGERATOR",
            "IN-ROOM SAFE",
            "CLOSET"
        ],
        "rates": {
            "regular": {
                "referenceNumber": "900388c8",
                "monday": 1444,
                "tuesday": 1444,
                "wednesday": 1444,
                "thursday": 1444,
                "friday": 1444,
                "saturday": 1544,
                "sunday": 1544
            },
            "special": [
                {
                    "referenceNumber": "RT-0002",
                    "discountName": "DREAMSTAY DISCOUNT",
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
            ]
        },
        "rooms": [
            {
                "roomId": 2,
                "roomReferenceNumber": "ef03c1e1",
                "roomNumber": 102,
                "roomType": "STANDARD",
                "status": "AVAILABLE",
                "guest": null
            },
            {
                "roomId": 7,
                "roomReferenceNumber": "74069624",
                "roomNumber": 107,
                "roomType": "STANDARD",
                "status": "AVAILABLE",
                "guest": null
            }
        ],
        "pagination": {
            "total": 4,
            "perPage": 2,
            "currentPage": 1,
            "lastPage": 2,
            "from": 1,
            "to": 2
        }
    },
    "code": 200,
    "error": false
}
```
