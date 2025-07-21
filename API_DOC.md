# API Endpoints Documentation

## Base URL

```
http://your-app-url/api/v1
```

---

## Authentication Endpoints

| Method | Endpoint                | Description                | Auth Required |
|--------|------------------------|----------------------------|--------------|
| POST   | /auth/register         | Register a new user        | No           |
| POST   | /auth/login            | Login                      | No           |
| POST   | /auth/admin-login      | Admin login (dashboard)    | No           |
| POST   | /auth/logout           | Logout                     | Yes          |
| GET    | /auth/profile          | Get user profile           | Yes          |

---

## Public Event Endpoints

| Method | Endpoint                        | Description             | Auth Required |
|--------|---------------------------------|-------------------------|--------------|
| GET    | /events                         | Get all events          | No           |
| GET    | /events/{id}                    | Get single event        | No           |
| GET    | /events/category/{categoryId}   | Events by category      | No           |
| GET    | /events/city/{city}             | Events by city          | No           |
| GET    | /events/search                  | Search events           | No           |
| GET    | /users                           | Get all users with role 'user' | No           |

---

## Admin Endpoints (Protected)

All endpoints below require authentication (Sanctum token).

### Users Management

| Method | Endpoint                        | Description             |
|--------|---------------------------------|-------------------------|
| GET    | /admin/users                    | Get all users           |
| GET    | /admin/users/{id}               | Get user details        |
| PUT    | /admin/users/{id}               | Update user             |
| DELETE | /admin/users/{id}               | Delete user             |
| PUT    | /admin/users/{id}/status        | Ban/Unban user          |

### Events Management

| Method | Endpoint                        | Description             |
|--------|---------------------------------|-------------------------|
| GET    | /admin/events                   | Get all events (admin)  |
| GET    | /admin/events/{id}              | Get event details       |
| PUT    | /admin/events/{id}              | Update event            |
| DELETE | /admin/events/{id}              | Delete event            |
| POST   | /admin/events/{id}/approve      | Approve event           |
| POST   | /admin/events/{id}/reject       | Reject event            |
| POST   | /admin/events/{id}/feature      | Feature event           |

### Categories Management

| Method | Endpoint                        | Description             |
|--------|---------------------------------|-------------------------|
| GET    | /admin/categories               | Get all categories      |
| POST   | /admin/categories               | Create category         |
| PUT    | /admin/categories/{id}          | Update category         |
| DELETE | /admin/categories/{id}          | Delete category         |

### Bookings Management

| Method | Endpoint                        | Description             |
|--------|---------------------------------|-------------------------|
| GET    | /admin/bookings                 | Get all bookings        |
| GET    | /admin/bookings/{id}            | Get booking details     |
| PUT    | /admin/bookings/{id}/status     | Update booking status   |

### Analytics & Reports

| Method | Endpoint                        | Description             |
|--------|---------------------------------|-------------------------|
| GET    | /admin/analytics/dashboard      | Dashboard stats         |
| GET    | /admin/analytics/revenue        | Revenue analytics       |
| GET    | /admin/analytics/users          | User analytics          |
| GET    | /admin/analytics/events         | Event analytics         |

### System Settings

| Method | Endpoint                        | Description             |
|--------|---------------------------------|-------------------------|
| PUT    | /admin/settings                 | Update settings         |

---

## Organizer Endpoints (Protected, role: organizer)

| Method | Endpoint                                | Description                 |
|--------|-----------------------------------------|-----------------------------|
| GET    | /organizer/events                       | Get organizer's events      |
| POST   | /organizer/events                       | Create new event            |
| GET    | /organizer/events/{id}                  | Get event details           |
| PUT    | /organizer/events/{id}                  | Update event                |
| DELETE | /organizer/events/{id}                  | Delete event                |
| POST   | /organizer/events/{id}/upload-image     | Upload event image          |
| GET    | /organizer/analytics/dashboard          | Organizer dashboard stats   |
| GET    | /organizer/analytics/event/{id}/stats   | Event analytics             |

---

## Special

| Method | Endpoint      | Description         | Auth Required |
|--------|--------------|---------------------|--------------|
| GET    | /user        | Get current user    | Yes          |
