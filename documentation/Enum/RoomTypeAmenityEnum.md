### Room Type Enum

This endpoint is used for dropdowns in the frontend to display the room types.

#### URL

```
{{base_url}}/api/enum/room-type-amenity
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

#### Response Example (Success)

```json
{
    "message": "Room type amenities fetched successfully",
    "results": [
        {
            "referenceNumber": "a37a75",
            "roomTypeAmenity": "ADAPTER"
        },
        {
            "referenceNumber": "350900",
            "roomTypeAmenity": "AIR CONDITIONING"
        },
        {
            "referenceNumber": "8140de",
            "roomTypeAmenity": "BATHTUB"
        },
        ...
    ],
    "code": 200,
    "error": false
}
```
