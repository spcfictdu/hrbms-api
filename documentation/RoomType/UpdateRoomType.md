### Update Room Type

This endpoint is used to update a room type using the provided reference number of the room type.

#### URL

```
{{base_url}}/api/room-type/update/{roomTypeReferenceNumber}
```

#### Method

```
PUT
```

#### Authentication Needed

```
TRUE
```

#### Permitted Roles

```
ADMIN
```

#### Request Body

```json
{
    "name": "DELUXE",
    "description": "Offering more space, this modern room comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom.",
    "bedSize": "1 queen bed",
    "propertySize": "21 m²/226 ft²",
    "isNonSmoking": true,
    "balconyOrTerrace": false,
    "capacity": 2,
    "amenities": {
        "delete": [
            "CLOSET"
        ],
        "add": [
            "CLOTHES RACK"
        ]
    },
    "images": {
        "add": [{array of files}],
        "delete": [
            "VW43hivNMoM4eAWGMpfnjN1pw6ApxaYn4n4DAwGd.jpg"
        ],
        "update": [
            {
                "old": "VW43hivNMoM4eAWGMpfnjN1pw6ApxaYn4n4DAwGd.jpg",
                "new": {file}
            }
        ]
    }
}
```

#### Response Example (Success)

```json
{
    "message": "Room type updated successfully.",
    "results": {
        "referenceNumber": "0185739a",
        "name": "DELUXE",
        "description": "Offering more space, this modern room comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom.",
        "bedSize": "1 queen bed",
        "propertySize": "21 m²/226 ft²",
        "isNonSmoking": true,
        "balconyOrTerrace": false,
        "capacity": 2,
        "images": [
            "0185739a/GdM28bnt9bd0q9KcEw9qevfTvc5lyPYmRKIVQqPp.jpg",
            "0185739a/Csk1OyxeW1s2d5gkay4uJxLPYmgTuC09CRli9nra.jpg",
            "0185739a/HdM5SSvQqKl1wPlKNOsPim79jWeAzDduAtAeAY9O.jpg",
            "0185739a/SxQOwZLk2Abj4M1XKogKF0oogoIu2OxgnQFdCUq0.jpg"
        ],
        "amenities": ["AIR CONDITIONING", "FREE WI-FI", "CLOTHES RACK"],
        "rates": {
            "regular": {
                "referenceNumber": "d149c3f8",
                "monday": 1547,
                "tuesday": 1547,
                "wednesday": 1547,
                "thursday": 1547,
                "friday": 1547,
                "saturday": 1647,
                "sunday": 1647
            },
            "special": [
                {
                    "referenceNumber": "5558d1c8",
                    "discountName": "May Discount",
                    "startDate": "2024-05-01",
                    "endDate": "2024-05-31",
                    "monday": 1447,
                    "tuesday": 1447,
                    "wednesday": 1447,
                    "thursday": 1447,
                    "friday": 1447,
                    "saturday": 1547,
                    "sunday": 1547
                }
            ]
        }
    },
    "code": 200,
    "error": false
}
```
