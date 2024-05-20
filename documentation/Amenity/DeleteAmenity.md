### Delete Amenity

This endpoint is used to is used to delete a specific amenity using the provided reference number of the amenity.

#### URL

```
{{base_url}}/api/amenity/delete/{amenityReferenceNumber}
```   

#### Method
```
DELETE
```

#### Authentication Needed
```
TRUE
```

#### Permitted Roles
```
ADMIN
```

#### Response Example (Success)

``` json
{
    "message": "Amenity deleted successfully.",
    "results": [],
    "code": 200,
    "error": false
}
```
