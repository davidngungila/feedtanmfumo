# Login API Documentation
## FEEDTAN DIGITAL Mobile App - Authentication API

### Overview

This document describes how to authenticate the FEEDTAN DIGITAL **React Native** mobile app against the backend using **Laravel Sanctum personal access tokens**.

After successful login, the API returns a token. The app must send that token on every authenticated request using the `Authorization: Bearer <token>` header.

## Table of Contents

- [Base URL](#base-url)
- [Common Request Headers](#common-request-headers)
- [Authentication](#authentication)
- [Accessing Protected Services](#accessing-protected-services)
- [Endpoints](#endpoints)
  - [Login](#login-endpoint)
  - [Get Current User](#get-current-user-endpoint)
  - [Logout](#logout-endpoint)
  - [API Guide](#api-guide-endpoint)
  - [Dashboard](#dashboard-endpoints)
  - [Loans](#loans-endpoints)
  - [Savings](#savings-endpoints)
  - [Investments](#investments-endpoints)
  - [Welfare](#welfare-endpoints)
  - [Issues](#issues-endpoints)
  - [Transactions](#transactions-endpoints)
  - [Profile](#profile-endpoints)
  - [Notifications](#notification-endpoints)
  - [File Uploads](#file-upload-endpoints)
- [React Native (TypeScript) Integration](#react-native-typescript-integration)
  - [Environment configuration](#environment-configuration)
  - [Secure token storage](#secure-token-storage)
  - [HTTP client (Axios) with interceptors](#http-client-axios-with-interceptors)
  - [Typical auth flow](#typical-auth-flow)
- [Error Handling](#error-handling)
- [Security Best Practices](#security-best-practices)
- [Troubleshooting (React Native)](#troubleshooting-react-native)

---

## Base URL

```
Production: https://digital.feedtancmg.org/api/mobile/v1
Development: http://localhost:8000/api/mobile/v1
```

All endpoint paths in this document are **relative to the Base URL**.

Example (development login):

`POST http://localhost:8000/api/mobile/v1/auth/login`

---

## Common Request Headers

For JSON APIs:

```
Content-Type: application/json
Accept: application/json
```

---

## Authentication

This API uses **Laravel Sanctum** personal access tokens.

- **Public endpoints** do not require a token (login/register/forgot-password/guide).
- **Protected endpoints** require `Authorization: Bearer {token}`.

---

## Accessing Protected Services

For every protected request, send:

```bash
Accept: application/json
Authorization: Bearer {token}
```

Notes:

- If you receive `401 Unauthorized`, your token is missing/invalid/expired/revoked.
- The recommended mobile pattern is:
  - Store token in Keychain
  - Load token on app start
  - Call `GET /auth/user` to validate the session

---

## Endpoints

## Login Endpoint

### POST `/auth/login`

Authenticates a user with email and password, returning a Sanctum authentication token.

#### Request

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123",
  "remember": true
}
```

**Request Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `email` | string | Yes | User's email address |
| `password` | string | Yes | User's password |
| `remember` | boolean | No | Whether to extend token expiration (default: false) |

#### Success Response

**Status Code:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "phone": "+255123456789",
      "role": "user"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  }
}
```

**Response Fields:**

| Field | Type | Description |
|-------|------|-------------|
| `success` | boolean | Indicates if the request was successful |
| `message` | string | Success message |
| `data.user.id` | integer | User's unique identifier |
| `data.user.name` | string | User's full name |
| `data.user.email` | string | User's email address |
| `data.user.phone` | string | User's phone number (nullable) |
| `data.user.role` | string | User's role (e.g., "user", "admin") |
| `data.token` | string | Sanctum authentication token (Bearer token) |

#### Error Responses

##### 422 Unprocessable Entity - Validation Error

**Invalid Credentials:**
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The provided credentials do not match our records."
    ]
  }
}
```

**Missing Required Fields:**
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ],
    "password": [
      "The password field is required."
    ]
  }
}
```

**Invalid Email Format:**
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email must be a valid email address."
    ]
  }
}
```

##### 500 Internal Server Error

```json
{
  "success": false,
  "message": "Server error occurred. Please try again later."
}
```

---

## API Guide Endpoint

### GET `/guide`

Returns a simple API contract payload (base URL + key endpoint groups). This is mainly for client bootstrapping and human verification.

This endpoint is **public**.

---

## Get Current User Endpoint

### GET `/auth/user`

Returns the authenticated user's profile.

#### Request

**Headers:**

```bash
Accept: application/json
Authorization: Bearer {token}
```

#### Success Response

**Status Code:** `200 OK`

```json
{
  "success": true,
  "message": "User retrieved successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "phone": "+255123456789",
      "role": "user"
    }
  }
}
```

#### Error Responses

- **401 Unauthorized**
  - Missing/invalid/expired token

---

## Logout Endpoint

### POST `/auth/logout`

Revokes the current access token.

#### Request

**Headers:**

```bash
Accept: application/json
Authorization: Bearer {token}
```

#### Success Response

**Status Code:** `200 OK`

```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

#### Error Responses

- **401 Unauthorized**
  - Missing/invalid/expired token

---

## Dashboard Endpoints

All dashboard endpoints are **protected**.

- `GET /dashboard`
  - Returns KPIs, alerts and recent transactions
- `GET /dashboard/stats`
  - Returns aggregated counts and totals
- `GET /dashboard/alerts`
  - Returns alerts only

---

## Loans Endpoints

All loan endpoints are **protected**.

- `GET /loans`
  - List current user's loans
- `GET /loans/{loan}`
  - Get loan details (includes loan transactions)
- `POST /loans`
  - Create loan application
  - Body:
    - `principal_amount` (number, min 1000)
    - `purpose` (string)
    - `term_months` (int, 1-60)
    - `payment_frequency` (`monthly` | `weekly` | `bi-weekly`)
- `GET /loans/{loan}/schedule`
  - Returns repayment schedule (array of installments)
- `POST /loans/{loan}/pay`
  - Body:
    - `amount` (number)
    - `payment_method` (`cash` | `mobile_money` | `bank_transfer` | `cheque`)
    - `reference_number` (optional)

---

## Savings Endpoints

All savings endpoints are **protected**.

- `GET /savings`
  - List savings accounts
- `GET /savings/{savings}`
  - Get savings account details
- `POST /savings`
  - Create savings account (server-side TODO)
- `POST /savings/{savings}/deposit`
  - Body:
    - `amount` (number)
    - `payment_method` (`cash` | `mobile_money` | `bank_transfer`)
    - `reference_number` (optional)
- `POST /savings/{savings}/withdraw`
  - Body:
    - `amount` (number)
    - `payment_method` (`cash` | `mobile_money` | `bank_transfer`)
- `GET /savings/{savings}/statements`
  - Returns account transactions

---

## Investments Endpoints

All investment endpoints are **protected**.

- `GET /investments`
  - List investments
- `GET /investments/{investment}`
  - Get investment details
- `POST /investments`
  - Create investment subscription (server-side TODO)

---

## Welfare Endpoints

All welfare endpoints are **protected**.

- `GET /welfare`
  - List welfare contributions/benefits
- `GET /welfare/{welfare}`
  - Get welfare record details
- `POST /welfare`
  - Submit welfare request (server-side TODO)

---

## Issues Endpoints

All issue endpoints are **protected**.

- `GET /issues`
  - List issues submitted by the user
- `GET /issues/{issue}`
  - Get issue details
- `POST /issues`
  - Body:
    - `title` (string)
    - `description` (string)
    - `category` (string)
    - `priority` (`low` | `medium` | `high` | `urgent`)

---

## Transactions Endpoints

All transactions endpoints are **protected**.

- `GET /transactions`
  - Paginated list (currently `paginate(20)` on server)

---

## Profile Endpoints

All profile endpoints are **protected**.

- `GET /profile`
  - Get profile + preferences
- `PUT /profile`
  - Update profile fields
  - Body may include:
    - `name`, `email`, `phone`, `address`, `language` (`en` | `sw`)
- `PUT /profile/password`
  - Body:
    - `current_password`
    - `password`
    - `password_confirmation`
- `GET /profile/documents`
  - Returns documents list (server-side TODO)

---

## Notification Endpoints

All notification endpoints are **protected**.

- `POST /notifications/register-device`
  - Body:
    - `device_token` (string)
    - `device_type` (optional: `mobile` | `web`)
    - `platform` (optional: `ios` | `android` | `web`)
    - `app_version` (optional)
- `DELETE /notifications/unregister-device/{deviceToken}`
- `GET /notifications`
  - Returns notification preferences + registered devices
- `PUT /notifications/preferences`
  - Body (any of):
    - `email_notifications` (boolean)
    - `sms_notifications` (boolean)
    - `push_notifications` (boolean)

---

## File Upload Endpoints

All file upload endpoints are **protected** and use `multipart/form-data`.

Allowed file types: `jpg`, `jpeg`, `png`, `pdf`.
Max file size: **5MB** (`max:5120`).

- `POST /upload/kyc`
  - FormData:
    - `file` (required)
    - `document_type` (required: `national_id` | `passport` | `selfie` | `other`)
- `POST /upload/loan-document`
  - FormData:
    - `file` (required)
    - `loan_id` (optional)
    - `document_type` (required: `application_letter` | `payment_slip` | `standing_order` | `other`)
- `POST /upload/welfare-document`
  - FormData:
    - `file` (required)
    - `welfare_id` (optional)
- `POST /upload/issue-attachment`
  - FormData:
    - `file` (required)
    - `issue_id` (optional)

---

## React Native (TypeScript) Integration

This section is a **mobile app implementation guide** intended for React Native (TypeScript).

### Environment configuration

Use environment variables to swap base URLs per build type.

Recommended:

- `react-native-config`

Example `.env`:

```bash
API_BASE_URL=http://localhost:8000/api/mobile/v1
```

In production:

```bash
API_BASE_URL=https://digital.feedtancmg.org/api/mobile/v1
```

### Secure Token Storage

Use `react-native-keychain` for the token (secure). If you store user profile for UX purposes, store it separately (e.g. `@react-native-async-storage/async-storage`).

Install packages (example):

- `react-native-keychain`
- `@react-native-async-storage/async-storage`
- `axios`

### HTTP Client (Axios) with Interceptors

Create a central API client that:

- Injects `Authorization` header automatically
- Normalizes errors
- Detects `401` and triggers logout

```ts
import axios, { AxiosError } from 'axios';
import * as Keychain from 'react-native-keychain';

export const API_BASE_URL = process.env.API_BASE_URL ?? 'http://localhost:8000/api/mobile/v1';

export const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
  timeout: 30000,
});

async function getAuthToken(): Promise<string | null> {
  const creds = await Keychain.getGenericPassword();
  return creds ? creds.password : null;
}

export async function setAuthToken(token: string): Promise<void> {
  await Keychain.setGenericPassword('auth_token', token);
}

export async function clearAuthToken(): Promise<void> {
  await Keychain.resetGenericPassword();
}

api.interceptors.request.use(async (config) => {
  const token = await getAuthToken();
  if (token) {
    config.headers = config.headers ?? {};
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export type ApiError = {
  status?: number;
  message: string;
  errors?: Record<string, string[]>;
};

export function toApiError(err: unknown): ApiError {
  if (!axios.isAxiosError(err)) {
    return { message: 'Unexpected error' };
  }

  const axErr = err as AxiosError<any>;
  const status = axErr.response?.status;
  const data = axErr.response?.data;

  return {
    status,
    message: data?.message ?? axErr.message ?? 'Request failed',
    errors: data?.errors,
  };
}

// Optional: plug this into your auth state management (Redux/Zustand/Context)
api.interceptors.response.use(
  (res) => res,
  async (err) => {
    const status = err?.response?.status;
    if (status === 401) {
      await clearAuthToken();
      // trigger navigation to Login screen from your app layer
    }
    return Promise.reject(err);
  }
);
```

### Auth API functions

```ts
import AsyncStorage from '@react-native-async-storage/async-storage';
import { api, setAuthToken, clearAuthToken } from './apiClient';

export type User = {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  role: string;
};

export type LoginResponse = {
  success: boolean;
  message: string;
  data: {
    user: User;
    token: string;
  };
};

export async function login(email: string, password: string): Promise<User> {
  const res = await api.post<LoginResponse>('/auth/login', {
    email,
    password,
    remember: true,
  });

  if (!res.data.success) {
    throw new Error(res.data.message || 'Login failed');
  }

  await setAuthToken(res.data.data.token);
  await AsyncStorage.setItem('user_data', JSON.stringify(res.data.data.user));
  return res.data.data.user;
}

export type UserResponse = {
  success: boolean;
  message: string;
  data: {
    user: User;
  };
};

export async function getCurrentUser(): Promise<User> {
  const res = await api.get<UserResponse>('/auth/user');
  if (!res.data.success) {
    throw new Error(res.data.message || 'Failed to load user');
  }
  await AsyncStorage.setItem('user_data', JSON.stringify(res.data.data.user));
  return res.data.data.user;
}

export type LogoutResponse = {
  success: boolean;
  message: string;
};

export async function logout(): Promise<void> {
  try {
    await api.post<LogoutResponse>('/auth/logout');
  } finally {
    await clearAuthToken();
    await AsyncStorage.removeItem('user_data');
  }
}
```

### Typical Auth Flow

- **App start**
  - Check if token exists in Keychain
  - If token exists, call `GET /auth/user`
  - If it returns `200`, show authenticated screens
  - If it returns `401`, clear token and show login

- **Login screen**
  - Call `POST /auth/login`
  - Store token in Keychain
  - Navigate to authenticated area

- **Logout**
  - Call `POST /auth/logout`
  - Clear token + cached user data

---

## React Native Examples (Calling Services)

### Example: load dashboard

```ts
import { api } from './apiClient';

export async function getDashboard() {
  const res = await api.get('/dashboard');
  return res.data;
}
```

### Example: list loans + get loan detail

```ts
import { api } from './apiClient';

export async function listLoans() {
  const res = await api.get('/loans');
  return res.data;
}

export async function getLoan(loanId: number) {
  const res = await api.get(`/loans/${loanId}`);
  return res.data;
}
```

### Example: create an issue

```ts
import { api } from './apiClient';

export async function createIssue(input: {
  title: string;
  description: string;
  category: string;
  priority: 'low' | 'medium' | 'high' | 'urgent';
}) {
  const res = await api.post('/issues', input);
  return res.data;
}
```

### Example: upload a KYC document (multipart)

```ts
import { api } from './apiClient';

export async function uploadKyc(fileUri: string, fileName: string, mimeType: string, documentType: 'national_id' | 'passport' | 'selfie' | 'other') {
  const form = new FormData();
  form.append('document_type', documentType);
  form.append('file', {
    uri: fileUri,
    name: fileName,
    type: mimeType,
  } as any);

  const res = await api.post('/upload/kyc', form, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  });

  return res.data;
}
```

---

## Error Handling

### Common Error Scenarios

1. **Invalid Credentials**
   - Status: `422`
   - Action: Show error message, allow user to retry

2. **Network Error**
   - Status: N/A (connection failed)
   - Action: Check internet connection, show retry option

3. **Server Error**
   - Status: `500`
   - Action: Log error, show generic error message, allow retry

4. **Token Expired**
   - Status: `401`
   - Action: Clear stored token, redirect to login

### Error Handling Example (React Native)

```ts
import { login } from './authApi';
import { toApiError } from './apiClient';

try {
  await login(email, password);
  // navigate to home
} catch (e) {
  const err = toApiError(e);
  if (err.status === 422) {
    // show validation errors
  } else {
    // show err.message
  }
}
```

---

## Related Endpoints

- **Register:** `POST /auth/register`
- **Get User:** `GET /auth/user` (requires authentication)
- **Logout:** `POST /auth/logout` (requires authentication)
- **Forgot Password:** `POST /auth/forgot-password`

---

## Support

For additional help:
- **Email:** dev-support@feedtan.com
- **Documentation:** See `MOBILE_APP_DOCUMENTATION.md`
- **API Reference:** See `API_QUICK_REFERENCE.md`

---

**Last Updated:** January 2024  
**API Version:** v1  
**Authentication Method:** Laravel Sanctum (Bearer Token)
