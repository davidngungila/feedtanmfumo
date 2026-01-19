# Mobile App Development Documentation
## FEEDTAN DIGITAL - Member Services Mobile App

### Table of Contents
1. [Overview](#overview)
2. [API Authentication](#api-authentication)
3. [API Endpoints](#api-endpoints)
4. [Data Models](#data-models)
5. [User Flows](#user-flows)
6. [UI/UX Guidelines](#uiux-guidelines)
7. [Integration Guide](#integration-guide)
8. [Error Handling](#error-handling)
9. [Security Best Practices](#security-best-practices)
10. [Testing Guidelines](#testing-guidelines)

---

## Overview

The FEEDTAN DIGITAL mobile app provides members with access to:
- **Loans**: Apply, track, and make payments
- **Savings**: Manage accounts, deposit, withdraw
- **Investments**: Subscribe to plans, track returns
- **Social Welfare**: Request benefits, track contributions
- **Issues/Feedback**: Submit complaints and track resolution
- **Profile Management**: Update KYC, documents, settings

### Base URL
```
Production: https://your-domain.com/api/mobile/v1
Development: http://localhost:8000/api/mobile/v1
```

### API Version
Current version: `v1`

### Response Format
All API responses follow this structure:
```json
{
  "success": true|false,
  "message": "Optional message",
  "data": { ... }
}
```

---

## API Authentication

### 1. Laravel Sanctum (Installed & Configured)

Laravel Sanctum is already installed and configured for token-based authentication. The User model includes the `HasApiTokens` trait, and all authentication endpoints use Sanctum tokens.

### 2. Login Flow

**Endpoint:** `POST /api/mobile/v1/auth/login`

**Request:**
```json
{
  "email": "member@example.com",
  "password": "password123",
  "remember": true
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "member@example.com",
      "phone": "+255123456789",
      "role": "user"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  }
}
```

### 3. Register Device Token

**Endpoint:** `POST /api/mobile/v1/notifications/register-device`

**Headers:**
```
Authorization: Bearer {token}
```

**Request:**
```json
{
  "device_token": "fcm_token_or_apns_token",
  "device_type": "mobile",
  "platform": "android|ios",
  "app_version": "1.0.0"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Device registered successfully",
  "data": {
    "device_id": 1
  }
}
```

### 4. Using Authentication Token

Include the token in all authenticated requests:
```
Authorization: Bearer {token}
```

### 5. Logout

**Endpoint:** `POST /api/mobile/v1/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

## API Endpoints

### Dashboard

#### Get Dashboard Overview
**Endpoint:** `GET /api/mobile/v1/dashboard`

**Response:**
```json
{
  "success": true,
  "data": {
    "kpis": {
      "loan_outstanding": 500000.00,
      "savings_balance": 250000.00,
      "next_due_date": "2024-02-15",
      "next_due_amount": 50000.00
    },
    "alerts": [
      {
        "type": "warning",
        "title": "1 Overdue Loan(s)",
        "message": "You have loans that are past their due date",
        "action": "view_loans"
      }
    ],
    "recent_transactions": [
      {
        "id": 1,
        "type": "loan_payment",
        "amount": 50000.00,
        "status": "completed",
        "date": "2024-01-15"
      }
    ]
  }
}
```

#### Get Statistics
**Endpoint:** `GET /api/mobile/v1/dashboard/stats`

**Response:**
```json
{
  "success": true,
  "data": {
    "loans": {
      "total": 5,
      "active": 2,
      "pending": 1,
      "total_amount": 1000000.00,
      "remaining_amount": 500000.00
    },
    "savings": {
      "total_accounts": 3,
      "total_balance": 250000.00,
      "active_accounts": 2
    },
    "investments": {
      "total": 2,
      "active": 1,
      "total_principal": 500000.00
    },
    "welfare": {
      "contributions": 100000.00,
      "benefits": 50000.00
    },
    "issues": {
      "pending": 1,
      "total": 3
    }
  }
}
```

#### Get Alerts
**Endpoint:** `GET /api/mobile/v1/dashboard/alerts`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "type": "warning",
      "title": "1 Overdue Loan(s)",
      "message": "You have loans that are past their due date",
      "action": "view_loans"
    },
    {
      "type": "info",
      "title": "2 Payment(s) Due Soon",
      "message": "You have payments due within the next 7 days",
      "action": "view_loans"
    }
  ]
}
```

### Loans

#### List Loans
**Endpoint:** `GET /api/mobile/v1/loans`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "loan_number": "LN-ABC12345",
      "principal_amount": 500000.00,
      "total_amount": 550000.00,
      "remaining_amount": 300000.00,
      "status": "active",
      "application_date": "2024-01-01",
      "maturity_date": "2024-12-31"
    }
  ]
}
```

#### Get Loan Details
**Endpoint:** `GET /api/mobile/v1/loans/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "loan_number": "LN-ABC12345",
    "principal_amount": 500000.00,
    "total_amount": 550000.00,
    "remaining_amount": 300000.00,
    "status": "active",
    "purpose": "Business expansion",
    "term_months": 12,
    "payment_frequency": "monthly",
    "application_date": "2024-01-01",
    "maturity_date": "2024-12-31",
    "transactions": [
      {
        "id": 1,
        "amount": 50000.00,
        "type": "loan_payment",
        "date": "2024-01-15",
        "status": "completed"
      }
    ]
  }
}
```

#### Apply for Loan
**Endpoint:** `POST /api/mobile/v1/loans`

**Request:**
```json
{
  "principal_amount": 500000,
  "purpose": "Business expansion",
  "term_months": 12,
  "payment_frequency": "monthly"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Loan application submitted successfully",
  "data": {
    "loan_id": 1,
    "loan_number": "LN-ABC12345"
  }
}
```

#### Get Repayment Schedule
**Endpoint:** `GET /api/mobile/v1/loans/{id}/schedule`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "installment_number": 1,
      "due_date": "2024-02-01",
      "amount": 45833.33
    },
    {
      "installment_number": 2,
      "due_date": "2024-03-01",
      "amount": 45833.33
    }
  ]
}
```

#### Make Loan Payment
**Endpoint:** `POST /api/mobile/v1/loans/{id}/pay`

**Request:**
```json
{
  "amount": 50000,
  "payment_method": "mobile_money",
  "reference_number": "MM123456789"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Payment processed successfully"
}
```

### Savings

#### List Savings Accounts
**Endpoint:** `GET /api/mobile/v1/savings`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "account_number": "SAV-001",
      "account_type": "emergency",
      "balance": 250000.00,
      "status": "active"
    }
  ]
}
```

#### Get Savings Account Details
**Endpoint:** `GET /api/mobile/v1/savings/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "account_number": "SAV-001",
    "account_type": "emergency",
    "balance": 250000.00,
    "status": "active"
  }
}
```

#### Create Savings Account
**Endpoint:** `POST /api/mobile/v1/savings`

**Request:**
```json
{
  "account_type": "emergency",
  "initial_deposit": 10000
}
```

#### Make Deposit
**Endpoint:** `POST /api/mobile/v1/savings/{id}/deposit`

**Request:**
```json
{
  "amount": 50000,
  "payment_method": "mobile_money",
  "reference_number": "MM123456789"
}
```

#### Make Withdrawal
**Endpoint:** `POST /api/mobile/v1/savings/{id}/withdraw`

**Request:**
```json
{
  "amount": 20000,
  "payment_method": "mobile_money"
}
```

#### Get Statements
**Endpoint:** `GET /api/mobile/v1/savings/{id}/statements`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "type": "savings_deposit",
      "amount": 50000.00,
      "date": "2024-01-15",
      "status": "completed"
    }
  ]
}
```

### Investments

#### List Investments
**Endpoint:** `GET /api/mobile/v1/investments`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "investment_number": "INV-001",
      "plan_type": "4_year",
      "principal_amount": 500000.00,
      "expected_return": 750000.00,
      "status": "active"
    }
  ]
}
```

#### Get Investment Details
**Endpoint:** `GET /api/mobile/v1/investments/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "investment_number": "INV-001",
    "plan_type": "4_year",
    "principal_amount": 500000.00,
    "expected_return": 750000.00,
    "profit_share": 250000.00,
    "status": "active",
    "maturity_date": "2028-01-15"
  }
}
```

#### Subscribe to Investment
**Endpoint:** `POST /api/mobile/v1/investments`

**Request:**
```json
{
  "plan_type": "4_year",
  "principal_amount": 500000,
  "start_date": "2024-01-15",
  "payment_method": "bank_transfer"
}
```

### Social Welfare

#### List Welfare Records
**Endpoint:** `GET /api/mobile/v1/welfare`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "welfare_number": "WEL-001",
      "type": "benefit",
      "benefit_type": "medical",
      "amount": 100000.00,
      "status": "approved"
    }
  ]
}
```

#### Request Welfare Benefit
**Endpoint:** `POST /api/mobile/v1/welfare`

**Request:**
```json
{
  "type": "benefit",
  "benefit_type": "medical",
  "amount": 100000,
  "description": "Medical emergency expenses"
}
```

### Issues/Feedback

#### List Issues
**Endpoint:** `GET /api/mobile/v1/issues`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Account access issue",
      "category": "technical",
      "priority": "high",
      "status": "pending",
      "created_at": "2024-01-15 10:30:00"
    }
  ]
}
```

#### Get Issue Details
**Endpoint:** `GET /api/mobile/v1/issues/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Account access issue",
    "description": "Unable to login to my account",
    "category": "technical",
    "priority": "high",
    "status": "pending"
  }
}
```

#### Submit Issue
**Endpoint:** `POST /api/mobile/v1/issues`

**Request:**
```json
{
  "title": "Account access issue",
  "description": "Unable to login to my account",
  "category": "technical",
  "priority": "high"
}
```

### Transactions

#### List Transactions
**Endpoint:** `GET /api/mobile/v1/transactions`

**Query Parameters:**
- `page` (optional): Page number
- `per_page` (optional): Items per page (default: 20)

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "transaction_number": "TXN-001",
        "type": "loan_payment",
        "amount": 50000.00,
        "status": "completed",
        "date": "2024-01-15"
      }
    ],
    "total": 50,
    "per_page": 20
  }
}
```

### Profile

#### Get Profile
**Endpoint:** `GET /api/mobile/v1/profile`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "member@example.com",
    "phone": "+255123456789",
    "address": "123 Main St",
    "preferences": {
      "email_notifications": true,
      "sms_notifications": false,
      "push_notifications": true,
      "language": "en"
    }
  }
}
```

#### Update Profile
**Endpoint:** `PUT /api/mobile/v1/profile`

**Request:**
```json
{
  "name": "John Doe",
  "email": "member@example.com",
  "phone": "+255123456789",
  "address": "123 Main St",
  "language": "en"
}
```

#### Update Password
**Endpoint:** `PUT /api/mobile/v1/profile/password`

**Request:**
```json
{
  "current_password": "oldpassword123",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

#### Get Documents
**Endpoint:** `GET /api/mobile/v1/profile/documents`

**Response:**
```json
{
  "success": true,
  "data": {
    "documents": [
      {
        "type": "statement",
        "name": "January Statement",
        "url": "https://...",
        "date": "2024-01-31"
      }
    ]
  }
}
```

### File Uploads

#### Upload KYC Document
**Endpoint:** `POST /api/mobile/v1/upload/kyc`

**Content-Type:** `multipart/form-data`

**Request:**
```
file: [binary file]
document_type: national_id|passport|selfie|other
```

**Response:**
```json
{
  "success": true,
  "message": "KYC document uploaded successfully",
  "data": {
    "file_path": "kyc/1/uuid.jpg",
    "file_url": "https://...",
    "document_type": "national_id"
  }
}
```

#### Upload Loan Document
**Endpoint:** `POST /api/mobile/v1/upload/loan-document`

**Request:**
```
file: [binary file]
loan_id: 1 (optional)
document_type: application_letter|payment_slip|standing_order|other
```

#### Upload Welfare Document
**Endpoint:** `POST /api/mobile/v1/upload/welfare-document`

**Request:**
```
file: [binary file]
welfare_id: 1 (optional)
```

#### Upload Issue Attachment
**Endpoint:** `POST /api/mobile/v1/upload/issue-attachment`

**Request:**
```
file: [binary file]
issue_id: 1 (optional)
```

### Notifications

#### Get Notification Preferences
**Endpoint:** `GET /api/mobile/v1/notifications`

**Response:**
```json
{
  "success": true,
  "data": {
    "email_notifications": true,
    "sms_notifications": false,
    "push_notifications": true,
    "registered_devices": [
      {
        "id": 1,
        "platform": "android",
        "device_type": "mobile",
        "last_used": "2024-01-15 10:30:00"
      }
    ]
  }
}
```

#### Update Notification Preferences
**Endpoint:** `PUT /api/mobile/v1/notifications/preferences`

**Request:**
```json
{
  "email_notifications": true,
  "sms_notifications": false,
  "push_notifications": true
}
```

#### Unregister Device
**Endpoint:** `DELETE /api/mobile/v1/notifications/unregister-device/{deviceToken}`

---

## Data Models

### User Model
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "member@example.com",
  "phone": "+255123456789",
  "role": "user",
  "preferences": {
    "email_notifications": true,
    "sms_notifications": false,
    "push_notifications": true,
    "language": "en"
  }
}
```

### Loan Model
```json
{
  "id": 1,
  "loan_number": "LN-ABC12345",
  "principal_amount": 500000.00,
  "interest_rate": 10.00,
  "total_amount": 550000.00,
  "paid_amount": 250000.00,
  "remaining_amount": 300000.00,
  "term_months": 12,
  "payment_frequency": "monthly",
  "status": "active|pending|approved|disbursed|completed|overdue|rejected",
  "application_date": "2024-01-01",
  "maturity_date": "2024-12-31"
}
```

### Savings Account Model
```json
{
  "id": 1,
  "account_number": "SAV-001",
  "account_type": "emergency|rda|flex|business",
  "balance": 250000.00,
  "interest_rate": 5.00,
  "status": "active|inactive|closed"
}
```

### Investment Model
```json
{
  "id": 1,
  "investment_number": "INV-001",
  "plan_type": "4_year|6_year",
  "principal_amount": 500000.00,
  "expected_return": 750000.00,
  "profit_share": 250000.00,
  "status": "active|matured|completed",
  "maturity_date": "2028-01-15"
}
```

### Transaction Model
```json
{
  "id": 1,
  "transaction_number": "TXN-001",
  "transaction_type": "loan_payment|loan_disbursement|savings_deposit|savings_withdrawal|investment_deposit|investment_disbursement|welfare_contribution|welfare_benefit",
  "amount": 50000.00,
  "payment_method": "cash|mobile_money|bank_transfer|cheque",
  "status": "pending|completed|failed|cancelled",
  "transaction_date": "2024-01-15"
}
```

### Issue Model
```json
{
  "id": 1,
  "title": "Account access issue",
  "description": "Unable to login",
  "category": "technical|financial|other",
  "priority": "low|medium|high|urgent",
  "status": "pending|in_progress|resolved|closed"
}
```

---

## User Flows

### 1. Registration & KYC Flow

```
1. User opens app → Registration screen
2. Enter: Name, Email, Phone, Password
3. POST /api/mobile/v1/auth/register
4. Receive token → Store securely
5. KYC Screen → Upload documents
   - National ID
   - Passport Photo
   - Selfie
6. POST /api/mobile/v1/upload/kyc (for each document)
7. Complete profile information
8. PUT /api/mobile/v1/profile
```

### 2. Login Flow

```
1. User enters email/password
2. POST /api/mobile/v1/auth/login
3. Receive token → Store securely
4. Register device for push notifications
5. POST /api/mobile/v1/notifications/register-device
6. Navigate to Dashboard
```

### 3. Loan Application Flow

```
1. Navigate to Loans → Apply
2. Fill form:
   - Amount
   - Purpose
   - Term (months)
   - Repayment frequency
3. Upload supporting documents (optional)
   - POST /api/mobile/v1/upload/loan-document
4. Submit application
   - POST /api/mobile/v1/loans
5. Show confirmation
6. Track status in Loans list
```

### 4. Loan Payment Flow

```
1. Navigate to Loans → Select loan
2. View repayment schedule
   - GET /api/mobile/v1/loans/{id}/schedule
3. Tap "Make Payment"
4. Enter amount, payment method, reference
5. POST /api/mobile/v1/loans/{id}/pay
6. Show confirmation
7. Refresh loan details
```

### 5. Savings Deposit Flow

```
1. Navigate to Savings → Select account
2. Tap "Deposit"
3. Enter amount, payment method, reference
4. POST /api/mobile/v1/savings/{id}/deposit
5. Show confirmation
6. Refresh account balance
```

### 6. Issue Submission Flow

```
1. Navigate to Support → Submit Issue
2. Fill form:
   - Title
   - Description
   - Category
   - Priority
3. Attach files (optional)
   - POST /api/mobile/v1/upload/issue-attachment
4. Submit
   - POST /api/mobile/v1/issues
5. Track status in Issues list
```

---

## UI/UX Guidelines

### Color Scheme
- **Primary:** `#015425` (Green)
- **Secondary:** `#027a3a` (Light Green)
- **Success:** `#10b981` (Green)
- **Warning:** `#f59e0b` (Orange)
- **Error:** `#ef4444` (Red)
- **Info:** `#3b82f6` (Blue)

### Typography
- **Headings:** Bold, 18-24px
- **Body:** Regular, 14-16px
- **Captions:** Regular, 12px

### Screen Structure

#### Home/Dashboard Screen
```
┌─────────────────────────┐
│  Welcome, [Name]       │
│  Member Since: [Date]  │
├─────────────────────────┤
│  Quick Stats            │
│  ┌─────┐ ┌─────┐       │
│  │Loan │ │Sav. │       │
│  │500K │ │250K │       │
│  └─────┘ └─────┘       │
├─────────────────────────┤
│  Alerts                 │
│  ⚠️ 1 Overdue Loan      │
│  ℹ️ 2 Payments Due     │
├─────────────────────────┤
│  Recent Transactions    │
│  • Payment - 50,000     │
│  • Deposit - 25,000    │
└─────────────────────────┘
```

#### Navigation Structure
```
Bottom Tab Bar:
[🏠 Home] [💰 Loans] [💳 Savings] [📈 Investments] [👤 Profile]
```

### Key Screens

1. **Dashboard/Home**
   - KPIs at top
   - Alerts section
   - Recent transactions
   - Quick actions

2. **Loans List**
   - Filter by status
   - Search functionality
   - Pull to refresh
   - Each item shows: Loan number, amount, status, due date

3. **Loan Details**
   - Loan information
   - Repayment schedule
   - Transaction history
   - Make payment button

4. **Savings List**
   - Account cards with balance
   - Quick deposit/withdraw actions

5. **Profile**
   - Personal information
   - KYC status
   - Documents
   - Settings (notifications, language)

---

## Integration Guide

### Step 1: Setup Authentication

```dart
// Flutter Example
class AuthService {
  static const String baseUrl = 'https://your-domain.com/api/mobile/v1';
  
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'email': email,
        'password': password,
        'remember': true,
      }),
    );
    
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      // Store token securely
      await _storeToken(data['data']['token']);
      return data;
    }
    throw Exception('Login failed');
  }
  
  Future<void> _storeToken(String token) async {
    // Use secure storage (flutter_secure_storage)
    final storage = FlutterSecureStorage();
    await storage.write(key: 'auth_token', value: token);
  }
}
```

### Step 2: API Client Setup

```dart
class ApiClient {
  static const String baseUrl = 'https://your-domain.com/api/mobile/v1';
  
  Future<String?> _getToken() async {
    final storage = FlutterSecureStorage();
    return await storage.read(key: 'auth_token');
  }
  
  Future<Map<String, String>> _getHeaders() async {
    final token = await _getToken();
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }
  
  Future<Response> get(String endpoint) async {
    return await http.get(
      Uri.parse('$baseUrl$endpoint'),
      headers: await _getHeaders(),
    );
  }
  
  Future<Response> post(String endpoint, Map<String, dynamic> body) async {
    return await http.post(
      Uri.parse('$baseUrl$endpoint'),
      headers: await _getHeaders(),
      body: jsonEncode(body),
    );
  }
}
```

### Step 3: File Upload

```dart
Future<Map<String, dynamic>> uploadKyc(File file, String documentType) async {
  final token = await _getToken();
  final request = http.MultipartRequest(
    'POST',
    Uri.parse('$baseUrl/upload/kyc'),
  );
  
  request.headers['Authorization'] = 'Bearer $token';
  request.files.add(
    await http.MultipartFile.fromPath('file', file.path),
  );
  request.fields['document_type'] = documentType;
  
  final streamedResponse = await request.send();
  final response = await http.Response.fromStream(streamedResponse);
  
  return jsonDecode(response.body);
}
```

### Step 4: Push Notifications Setup

```dart
// Register device token
Future<void> registerDevice(String fcmToken) async {
  final apiClient = ApiClient();
  await apiClient.post('/notifications/register-device', {
    'device_token': fcmToken,
    'device_type': 'mobile',
    'platform': Platform.isAndroid ? 'android' : 'ios',
    'app_version': '1.0.0',
  });
}
```

---

## Error Handling

### Standard Error Response
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized (Token expired/invalid)
- `403` - Forbidden (No permission)
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

### Error Handling Example

```dart
try {
  final response = await apiClient.get('/dashboard');
  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    return data;
  } else if (response.statusCode == 401) {
    // Token expired - redirect to login
    await _logout();
    Navigator.pushReplacementNamed(context, '/login');
  } else {
    throw Exception('Request failed: ${response.statusCode}');
  }
} catch (e) {
  // Handle network errors
  showErrorDialog('Network error: ${e.toString()}');
}
```

---

## Security Best Practices

### 1. Token Storage
- **Never** store tokens in SharedPreferences or UserDefaults
- Use secure storage:
  - Flutter: `flutter_secure_storage`
  - React Native: `react-native-keychain`
  - Native iOS: Keychain
  - Native Android: EncryptedSharedPreferences

### 2. HTTPS Only
- Always use HTTPS in production
- Implement certificate pinning for extra security

### 3. Token Refresh
- Implement automatic token refresh before expiration
- Handle 401 errors gracefully

### 4. Input Validation
- Validate all user inputs client-side
- Never trust client-side validation alone

### 5. Sensitive Data
- Never log sensitive information
- Clear sensitive data from memory when not needed

---

## Testing Guidelines

### Unit Tests
Test individual API calls and data transformations.

### Integration Tests
Test complete user flows (login → dashboard → apply loan).

### Test Data
Use test accounts provided by the backend team.

### Test Scenarios

1. **Authentication**
   - Valid login
   - Invalid credentials
   - Token expiration
   - Logout

2. **Loans**
   - Apply loan
   - View loan details
   - Make payment
   - View schedule

3. **File Uploads**
   - Upload KYC documents
   - Upload loan documents
   - Handle upload failures

4. **Offline Mode**
   - Cache data for offline viewing
   - Queue actions when offline
   - Sync when back online

---

## Additional Resources

### API Documentation Endpoint
**GET** `/api/mobile/v1/guide` - Returns complete API contract

### Support
- Email: dev-support@feedtan.com
- Documentation: https://docs.feedtan.com
- GitHub: https://github.com/feedtan/mobile-api

### Version History
- **v1.0.0** (Current) - Initial release with all core features

---

## Quick Reference

### Common Endpoints
```
POST   /auth/login                    - Login
POST   /auth/register                  - Register
GET    /dashboard                      - Dashboard overview
GET    /loans                          - List loans
POST   /loans                          - Apply loan
GET    /savings                        - List savings
POST   /savings/{id}/deposit           - Deposit
POST   /upload/kyc                     - Upload KYC
POST   /notifications/register-device  - Register device
```

### Status Values
- **Loan Status:** pending, approved, disbursed, active, completed, overdue, rejected
- **Transaction Status:** pending, completed, failed, cancelled
- **Issue Status:** pending, in_progress, resolved, closed
- **Priority:** low, medium, high, urgent

---

**Last Updated:** January 2024
**Version:** 1.0.0

