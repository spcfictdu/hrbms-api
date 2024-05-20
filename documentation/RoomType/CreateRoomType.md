### Create Room Type

This endpoint is used to create a new room type. 

#### URL

```
{{base_url}}/api/room-type/create
```   

#### Method
```
POST
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

``` json
{
    "name": "DELUXE",
    "description": "Offering more space, this modern room comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom. ",
    "bedSize": "1 queen bed",
    "propertySize": "21 m²/226 ft²",
    "isNonSmoking": true,
    "balconyOrTerrace": false,
    "capacity": 2,
    "amenities": [
        "AIR CONDITIONING",
        "CLOSET",
        "FREE WI-FI"
    ],
    "rates": {
        "monday": 1547,
        "tuesday": 1547,
        "wednesday": 1547,
        "thursday": 1547,
        "friday": 1547,
        "saturday": 1647,
        "sunday": 1647
    },
    "images": [{array of files}]
}
``` 

#### Response Example (Success)

``` json
{
    "message": "Room type created successfully.",
    "results": {
        "referenceNumber": "08d03ed0",
        "name": "DELUXE",
        "description": "Offering more space, this modern room comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom.",
        "bedSize": "1 queen bed",
        "propertySize": "21 m²/226 ft²",
        "isNonSmoking": true,
        "balconyOrTerrace": false,
        "capacity": "2",
        "images": [
            "08d03ed0/ckII3zFOehwWqnkPypjTGR8gb3aBohlKgssBTKjE.jpg",
            "08d03ed0/t6JnJhdSJzyJfCBLt6AXe1TF1aeAtB4ixj5FWuBu.jpg",
            "08d03ed0/SdvcdSEEX1uC1oMPN0eVMW46sCZz5zIXELcQTIPH.jpg",
            "08d03ed0/UULXkkdMDbhTUunGFlDD3MMCMXhLhTpndKPCbqWX.jpg",
            "08d03ed0/tU9vYrsJ9LkTWQCJdKwosmwbnyyGFOUsn0blwEzK.jpg"
        ],
        "amenities": [
            "AIR CONDITIONING",
            "CLOSET",
            "FREE WI-FI"
        ],
        "rates": {
            "monday": 1547,
            "tuesday": 1547,
            "wednesday": 1547,
            "thursday": 1547,
            "friday": 1547,
            "saturday": 1647,
            "sunday": 1647
        }
    },
    "code": 200,
    "error": false
}
```
