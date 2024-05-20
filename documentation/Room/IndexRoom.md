### Index Room

This endpoint retrieves the list of all rooms.

####  URL

```
{{base_url}}/api/room/
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

#### Response Example (Success)

``` json
{
    "message": "List of all rooms.",
    "results": [
        {
            "referenceNumber": "22a027c3",
            "roomNumber": 101,
            "status": "READY FOR OCCUPANCY",
            "roomType": {
                "name": "JUNIOR STANDARD",
                "capacity": 2,
                "amenities": [
                    "AIR CONDITIONING",
                    "SATELLITE/CABLE TV",
                    "TELEPHONE",
                    "FREE WI-FI",
                    "REFRIGERATOR",
                    "IN-ROOM SAFE",
                    "CLOSET"
                ]
            }
        },
        {
            "referenceNumber": "27be6040",
            "roomNumber": 201,
            "status": "READY FOR OCCUPANCY",
            "roomType": {
                "name": "STANDARD",
                "capacity": 2,
                "amenities": [
                    "AIR CONDITIONING",
                    "BATHTUB",
                    "SATELLITE/CABLE TV",
                    "TELEPHONE",
                    "FREE WI-FI",
                    "REFRIGERATOR",
                    "IN-ROOM SAFE",
                    "CLOSET"
                ]
            }
        },
        {
            "referenceNumber": "6c4792ea",
            "roomNumber": 301,
            "status": "READY FOR OCCUPANCY",
            "roomType": {
                "name": "JUNIOR SUITE",
                "capacity": 2,
                "amenities": [
                    "AIR CONDITIONING",
                    "BATHTUB",
                    "SATELLITE/CABLE TV",
                    "TELEPHONE",
                    "FREE WI-FI",
                    "REFRIGERATOR",
                    "IN-ROOM SAFE",
                    "CLOSET",
                    "MINI BAR"
                ]
            }
        },
        {
            "referenceNumber": "1d069c38",
            "roomNumber": 401,
            "status": "READY FOR OCCUPANCY",
            "roomType": {
                "name": "SUITE",
                "capacity": 2,
                "amenities": [
                    "AIR CONDITIONING",
                    "BATHTUB",
                    "SATELLITE/CABLE TV",
                    "TELEPHONE",
                    "FREE WI-FI",
                    "REFRIGERATOR",
                    "IN-ROOM SAFE",
                    "CLOSET",
                    "MINI BAR"
                ]
            }
        },
        {
            "referenceNumber": "c9fbfbfc",
            "roomNumber": 501,
            "status": "READY FOR OCCUPANCY",
            "roomType": {
                "name": "SUPERIOR",
                "capacity": 2,
                "amenities": [
                    "AIR CONDITIONING",
                    "BATHTUB",
                    "SATELLITE/CABLE TV",
                    "TELEPHONE",
                    "FREE WI-FI",
                    "REFRIGERATOR",
                    "IN-ROOM SAFE",
                    "CLOSET",
                    "MINI BAR",
                    "KITCHENETTE"
                ]
            }
        },
        {
            "referenceNumber": "840b4991",
            "roomNumber": 602,
            "status": "READY FOR OCCUPANCY",
            "roomType": {
                "name": "DELUXE",
                "capacity": 2,
                "amenities": [
                    "AIR CONDITIONING",
                    "FREE WI-FI",
                    "CLOTHES RACK"
                ]
            }
        }
    ],
    "code": 200,
    "error": false
}
```
