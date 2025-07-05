# API Authentication with Laravel Sanctum

## Overview

This API uses Laravel Sanctum for token-based authentication. Your existing Laravel Breeze web authentication remains unchanged.

## Base URL

```
http://your-app-url/api/v1
```

## Authentication Endpoints

### 1. Register a new user

```http
POST /api/v1/auth/register
```

**Headers:**

```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "user"
}
```

**Available roles:**

-   `user` (default) - Regular user
-   `organizer` - Event organizer

**Response (201):**

```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
    },
    "token": "1|your-sanctum-token-here",
    "token_type": "Bearer"
}
```

### 2. Login

```http
POST /api/v1/auth/login
```

**Headers:**

```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**

```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
    },
    "token": "2|your-sanctum-token-here",
    "token_type": "Bearer"
}
```

### 3. Get user profile (Protected)

```http
GET /api/v1/auth/profile
```

**Headers:**

```
Authorization: Bearer your-sanctum-token-here
Accept: application/json
```

**Response (200):**

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "email_verified_at": null,
        "created_at": "2025-07-05T10:00:00.000000Z",
        "updated_at": "2025-07-05T10:00:00.000000Z"
    }
}
```

### 4. Logout (Protected)

```http
POST /api/v1/auth/logout
```

**Headers:**

```
Authorization: Bearer your-sanctum-token-here
Accept: application/json
```

**Response (200):**

```json
{
    "message": "Logged out successfully"
}
```

## Using the API Token

After login/register, you'll receive a token. Use this token in the `Authorization` header for all protected endpoints:

```
Authorization: Bearer your-sanctum-token-here
```

## Event Endpoints (Protected)

### For Organizers:

```http
GET /api/v1/organizer/events          # Get organizer's events
POST /api/v1/organizer/events         # Create new event
GET /api/v1/organizer/events/{id}     # Get specific event details
PUT /api/v1/organizer/events/{id}     # Update event
DELETE /api/v1/organizer/events/{id}  # Delete event
```

### Public Event Endpoints (No authentication required):

```http
GET /api/v1/events                    # Get all events
GET /api/v1/events/{id}               # Get single event
GET /api/v1/events/category/{id}      # Events by category
GET /api/v1/events/city/{city}        # Events by city
GET /api/v1/events/search             # Search events
```

## Postman Setup

1. **Environment Variables** (optional but recommended):

    - Create a Postman environment
    - Add variable: `base_url` = `http://your-app-url/api/v1`
    - Add variable: `token` = (leave empty, will be set after login)

2. **Register/Login:**

    - Use the register or login endpoint
    - Copy the token from the response
    - Set it as the `token` environment variable

3. **For protected endpoints:**
    - Go to Authorization tab
    - Select "Bearer Token"
    - Use `{{token}}` if using environment variables, or paste the actual token

## Token Management

-   Tokens are created with specific abilities based on user roles
-   Login automatically deletes old tokens and creates a new one
-   Logout deletes the current token
-   Tokens don't expire by default (you can configure this in `config/sanctum.php`)

## Error Responses

**Validation Error (422):**

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

**Authentication Error (401):**

```json
{
    "message": "Unauthenticated."
}
```

**Forbidden Error (403):**

```json
{
    "message": "This action is unauthorized."
}
```
