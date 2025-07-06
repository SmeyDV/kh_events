# KH Events - Admin API Documentation

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
All admin endpoints require Bearer token authentication using Laravel Sanctum.

### Option 1: Admin Login (Recommended)
```http
POST /auth/admin-login
```
**Body:**
```json
{
  "email": "admin@kh-events.local",
  "password": "admin123"
}
```
**Response:**
```json
{
  "message": "Admin login successful",
  "access_token": "your-token-here",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "name": "System Administrator",
    "email": "admin@kh-events.local",
    "role": "admin"
  }
}
```

### Option 2: Direct API Token
Use the pre-generated service token: `2|qzW2vSKPhArSjxveQmkILfOm3eDT26pKqwbIgMZQ6018d9e0`

**Headers Required:**
```
Authorization: Bearer YOUR_API_TOKEN
Content-Type: application/json
Accept: application/json
```

## Admin API Endpoints

### Users Management

#### Get All Users
```http
GET /admin/users
```
**Query Parameters:**
- `search` - Search by name or email
- `role` - Filter by role (user, organizer)
- `per_page` - Pagination limit (default: 15)

#### Get User Details
```http
GET /admin/users/{id}
```

#### Update User
```http
PUT /admin/users/{id}
```
**Body:**
```json
{
  "name": "string",
  "email": "string",
  "role_id": "integer"
}
```

#### Delete User
```http
DELETE /admin/users/{id}
```

#### Toggle User Status (Ban/Unban)
```http
PUT /admin/users/{id}/status
```
**Body:**
```json
{
  "is_active": true|false
}
```

---

### Events Management

#### Get All Events
```http
GET /admin/events
```
**Query Parameters:**
- `search` - Search by title or description
- `status` - Filter by status (pending, approved, rejected)
- `category_id` - Filter by category
- `per_page` - Pagination limit

#### Get Event Details
```http
GET /admin/events/{id}
```

#### Update Event
```http
PUT /admin/events/{id}
```
**Body:**
```json
{
  "title": "string",
  "description": "text",
  "date": "datetime",
  "location": "string",
  "city": "string",
  "category_id": "integer",
  "price": "decimal",
  "max_attendees": "integer"
}
```

#### Delete Event
```http
DELETE /admin/events/{id}
```

#### Approve Event
```http
POST /admin/events/{id}/approve
```

#### Reject Event
```http
POST /admin/events/{id}/reject
```
**Body:**
```json
{
  "reason": "string" // Optional rejection reason
}
```

#### Feature Event
```http
POST /admin/events/{id}/feature
```
**Body:**
```json
{
  "is_featured": true|false
}
```

---

### Categories Management

#### Get All Categories
```http
GET /admin/categories
```

#### Create Category
```http
POST /admin/categories
```
**Body:**
```json
{
  "name": "string",
  "description": "string", // Optional
  "color": "string" // Optional hex color
}
```

#### Update Category
```http
PUT /admin/categories/{id}
```
**Body:**
```json
{
  "name": "string",
  "description": "string",
  "color": "string"
}
```

#### Delete Category
```http
DELETE /admin/categories/{id}
```

---

### Bookings Management

#### Get All Bookings
```http
GET /admin/bookings
```
**Query Parameters:**
- `status` - Filter by status (pending, confirmed, cancelled)
- `event_id` - Filter by event
- `user_id` - Filter by user
- `per_page` - Pagination limit

#### Get Booking Details
```http
GET /admin/bookings/{id}
```

#### Update Booking Status
```http
PUT /admin/bookings/{id}/status
```
**Body:**
```json
{
  "status": "pending|confirmed|cancelled"
}
```

---

### Analytics & Reports

#### Dashboard Statistics
```http
GET /admin/analytics/dashboard
```
**Response:**
```json
{
  "total_users": "integer",
  "total_events": "integer",
  "total_bookings": "integer",
  "total_revenue": "decimal",
  "pending_events": "integer",
  "recent_registrations": "integer",
  "popular_categories": "array"
}
```

#### Revenue Analytics
```http
GET /admin/analytics/revenue
```
**Query Parameters:**
- `period` - daily, weekly, monthly, yearly
- `start_date` - Start date (Y-m-d)
- `end_date` - End date (Y-m-d)

#### User Analytics
```http
GET /admin/analytics/users
```
**Response:**
```json
{
  "total_users": "integer",
  "new_users_this_month": "integer",
  "active_users": "integer",
  "user_growth": "array",
  "user_roles_distribution": "object"
}
```

#### Event Analytics
```http
GET /admin/analytics/events
```
**Response:**
```json
{
  "total_events": "integer",
  "approved_events": "integer",
  "pending_events": "integer",
  "events_by_category": "array",
  "popular_locations": "array"
}
```

---

### System Settings

#### Get System Settings
```http
GET /admin/settings
```

#### Update System Settings
```http
PUT /admin/settings
```
**Body:**
```json
{
  "site_name": "string",
  "site_description": "string",
  "contact_email": "string",
  "default_event_approval": true|false,
  "max_events_per_organizer": "integer",
  "commission_rate": "decimal"
}
```

---

## Error Handling

All endpoints return standard HTTP status codes:

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

**Error Response Format:**
```json
{
  "message": "Error message",
  "errors": {
    "field": ["Validation error messages"]
  }
}
```

## Rate Limiting

API endpoints are rate limited to 60 requests per minute per user.

## Pagination

List endpoints return paginated results:

```json
{
  "data": [...],
  "current_page": 1,
  "last_page": 10,
  "per_page": 15,
  "total": 150,
  "next_page_url": "...",
  "prev_page_url": "..."
}
```
