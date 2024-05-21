### Delete Guest

This endpoint deletes a guest from the system using the provided guest ID.

#### URL

```
{{base_url}}/api/guest/delete/{id}
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

```json
{
    "message": "Successfully deleted guest",
    "results": null,
    "code": 200,
    "error": false
}
```
