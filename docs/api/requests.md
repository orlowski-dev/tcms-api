# tcms-api Requests

# Authentication

## GET /sanctum/csrf-cookie

Get a `Set-Cookie`. 


### Response

`204 No Content`

## POST /id/login

Authorize a user.

### Request 

| Key      | Data type | Required |
|----------|-----------|----------|
| email    | string    | Yes      |
| password | string    | Yes      |
| roleId   | int       | Yes      |

#### Example

```json
{
    "email": "test@example.com",
    "password": "password",
    "roleId": 2
}
```
### Response

#### Ok

```json
{
    "data": {
        "id": 1,
        "name": "Test User",
        "email": "test@example.com"
    }
}
```

#### Error

```json
{
    "message": "These credentials do not match our records.",
    "errors": {
        "email": [
            "These credentials do not match our records."
        ]
    }
}
```

## POST /id/logout

Logout a user.

### Response

```json
{
    "message": "Use has been logged out."
}

```

## POST /id/change-password

### Request

| Key         | Data type | Required |
|-------------|-----------|----------|
| userId      | int       | Yes      |
| newPassword | string    | Yes      |

#### Example

```json
{
    "userId": 32,
    "newPassword": "password123"
}
```

### Response

```json
{
    "message": "Password has been changed."
}
```


# Users

## GET /api/v1/users

Get all users. Response data is paginated.

### Query parameters

| Parameter          | Operators | Data type | Description                   | Required |
|--------------------|-----------|-----------|-------------------------------|----------|
| email              | eq        | string    | Filter users by email address | No       |
| name               | eq        | string    | Filter users by name          | No       |
| roleId             | eq, neq   | int       | Filter users by roleId        | No       |
| includeProfile     | N/A       | bool      | Include profile model         | No       |
| includeRole        | N/A       | bool      | Include user role name        | No       |
| includePermissions | N/A       | bool      | Include user role permissions | No       |

### Response

```json
{
    "data": [
        {
            "id": 1,
            "name": "Test User",
            "email": "test@example.com"
        },
        // .. 
    ],
    "links": {
        "first": "http://localhost:8000/api/v1/users?page=1",
        "last": "http://localhost:8000/api/v1/users?page=4",
        "prev": null,
        "next": "http://localhost:8000/api/v1/users?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 4,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/v1/users?page=1",
                "label": "1",
                "active": true
            },
            // ..
            {
                "url": "http://localhost:8000/api/v1/users?page=2",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://localhost:8000/api/v1/users",
        "per_page": 10,
        "to": 10,
        "total": 31
    }
}
```

## POST /api/v1/users

### Request

| Key      | Data type | Required |
|----------|-----------|----------|
| email    | string    | Yes      |
| name     | string    | Yes      |
| password | string    | Yes      |
| roleId   | int       | Yes      |

#### Example

```json
{
    "email": "postman@example.com",
    "name": "Postman Client",
    "password": "password",
    "roleId": 4
}
```

### Response

#### Ok

```json
{
    "data": {
        "id": 32,
        "name": "Postman Client",
        "email": "postman@example.com"
    }
}
```

#### Error

```json
{
    "message": "The email has already been taken.",
    "errors": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

## GET /api/v1/users/<user_id>

### Request

| Parameter          | Operators | Data type | Description                   | Required |
|--------------------|-----------|-----------|-------------------------------|----------|
| includeProfile     | N/A       | bool      | Include profile model         | No       |
| includeRole        | N/A       | bool      | Include user role name        | No       |
| includePermissions | N/A       | bool      | Include user role permissions | No       |

### Response

```json
{
    "data": {
        "id": 32,
        "name": "Postman Client",
        "email": "postman@example.com"
    }
}
```

## PUT /api/v1/users/<user_id>

### Request

| Key      | Data type | Required |
|----------|-----------|----------|
| email    | string    | Yes      |
| name     | string    | Yes      |
| password | string    | Yes      |
| roleId   | int       | Yes      |

#### Example

```json
{
    "email": "postman@email.com",
    "name": "Postman Client",
    "roleId": 4
}
```

### Response

```json
{
    "data": {
        "id": 23,
        "name": "Postman Client",
        "email": "postman@email.com"
    }
}
```

## PATCH /api/v1/users/<user_id>

### Request

| Key      | Data type | Required |
|----------|-----------|----------|
| email    | string    | No       |
| name     | string    | No       |
| password | string    | No       |
| roleId   | int       | No       |

#### Example

```json
{
    "roleId": 4
}
```

### Response

```json
{
    "data": {
        "id": 23,
        "name": "Postman Client",
        "email": "postman@email.com"
    }
}
```

## DELETE /api/v1/users/<user_id>

### Request

| Parameter   | Operators | Data type | Description                           | Required |
|-------------|-----------|-----------|----------------------------------------|----------|
| forceDelete | N/A       | bool      | User will be removed from the database | No       |

### Response

```json
{
    "message": "User has been deleted.",
    "softDeleted": true
}
```

## POST /api/v1/users/<user_id>/restore

Restore a user who was soft-deleted.

### Response

#### Ok

```json
{
    "message": "User has been resotred."
}
```

#### Error

```json
{
    "message": "User does not exist or has not been trashed."
}
```
