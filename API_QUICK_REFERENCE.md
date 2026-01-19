# API Quick Reference Card
## FEEDTAN DIGITAL Mobile API

### Base URL
```
https://your-domain.com/api/mobile/v1
```

### Authentication
```http
Authorization: Bearer {token}
```

---

## Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/login` | Login with email/password |
| POST | `/auth/register` | Register new user |
| POST | `/auth/logout` | Logout current session |
| GET | `/auth/user` | Get current user info |

**📖 For detailed login API documentation with code examples, see:** `LOGIN_API_DOCUMENTATION.md`

---

## Dashboard

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/dashboard` | Get dashboard overview (KPIs, alerts, transactions) |
| GET | `/dashboard/stats` | Get detailed statistics |
| GET | `/dashboard/alerts` | Get user alerts |

---

## Loans

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/loans` | List all user loans |
| GET | `/loans/{id}` | Get loan details |
| POST | `/loans` | Apply for new loan |
| GET | `/loans/{id}/schedule` | Get repayment schedule |
| POST | `/loans/{id}/pay` | Make loan payment |

**Loan Status Values:**
- `pending` - Awaiting approval
- `approved` - Approved, not yet disbursed
- `disbursed` - Funds released
- `active` - Currently active
- `completed` - Fully paid
- `overdue` - Past due date
- `rejected` - Application rejected

---

## Savings

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/savings` | List all savings accounts |
| GET | `/savings/{id}` | Get account details |
| POST | `/savings` | Create new account |
| POST | `/savings/{id}/deposit` | Make deposit |
| POST | `/savings/{id}/withdraw` | Make withdrawal |
| GET | `/savings/{id}/statements` | Get account statements |

**Account Types:**
- `emergency` - Emergency Savings
- `rda` - Recurrent Deposit Account
- `flex` - Flex Account
- `business` - Business Savings

---

## Investments

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/investments` | List all investments |
| GET | `/investments/{id}` | Get investment details |
| POST | `/investments` | Subscribe to investment plan |

**Plan Types:**
- `4_year` - 4-Year Investment Plan
- `6_year` - 6-Year Investment Plan

---

## Social Welfare

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/welfare` | List welfare records |
| GET | `/welfare/{id}` | Get welfare details |
| POST | `/welfare` | Request welfare benefit |

**Benefit Types:**
- `medical` - Medical Support
- `funeral` - Funeral Support
- `educational` - Educational Support
- `other` - Other Support

---

## Issues/Feedback

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/issues` | List all issues |
| GET | `/issues/{id}` | Get issue details |
| POST | `/issues` | Submit new issue |

**Priority Levels:**
- `low` - Low priority
- `medium` - Medium priority
- `high` - High priority
- `urgent` - Urgent

---

## Transactions

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/transactions` | List all transactions (paginated) |

**Query Parameters:**
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 20)

---

## Profile

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/profile` | Get user profile |
| PUT | `/profile` | Update profile |
| PUT | `/profile/password` | Change password |
| GET | `/profile/documents` | Get user documents |

---

## File Uploads

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/upload/kyc` | Upload KYC document |
| POST | `/upload/loan-document` | Upload loan document |
| POST | `/upload/welfare-document` | Upload welfare document |
| POST | `/upload/issue-attachment` | Upload issue attachment |

**Content-Type:** `multipart/form-data`

**KYC Document Types:**
- `national_id`
- `passport`
- `selfie`
- `other`

---

## Notifications

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/notifications/register-device` | Register device for push notifications |
| DELETE | `/notifications/unregister-device/{token}` | Unregister device |
| GET | `/notifications` | Get notification preferences |
| PUT | `/notifications/preferences` | Update preferences |

---

## Payment Methods

- `cash` - Cash payment
- `mobile_money` - Mobile money (M-Pesa, Tigo Pesa, etc.)
- `bank_transfer` - Bank transfer
- `cheque` - Cheque payment

---

## Common Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error details"]
  }
}
```

---

## HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized (Invalid/expired token) |
| 403 | Forbidden (No permission) |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## Error Handling

### 401 Unauthorized
- Token expired or invalid
- Solution: Redirect to login, request new token

### 422 Validation Error
- Invalid input data
- Check `errors` object for field-specific errors

### 500 Server Error
- Server-side issue
- Log error and show user-friendly message

---

## Rate Limiting

- **Default:** 60 requests per minute per user
- **Headers:**
  - `X-RateLimit-Limit` - Request limit
  - `X-RateLimit-Remaining` - Remaining requests

---

## Localization

### Supported Languages
- `en` - English
- `sw` - Swahili

### Setting Language
Include in profile update or as header:
```
Accept-Language: en
```

---

## Testing

### Test Credentials
Contact backend team for test accounts.

### Test Environment
```
https://test.feedtan.com/api/mobile/v1
```

---

**For complete documentation, see:** `MOBILE_APP_DOCUMENTATION.md`  
**For detailed login API documentation, see:** `LOGIN_API_DOCUMENTATION.md`

