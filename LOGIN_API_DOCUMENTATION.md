# Login API Documentation
## FEEDTAN DIGITAL Mobile App - Authentication API

### Overview

The Login API provides secure token-based authentication for the FEEDTAN DIGITAL mobile application using Laravel Sanctum. Upon successful login, users receive an authentication token that must be included in all subsequent API requests.

---

## Base URL

```
Production: https://your-domain.com/api/mobile/v1
Development: http://localhost:8000/api/mobile/v1
```

---

## Login Endpoint

### POST `/auth/login`

Authenticates a user with email and password, returning a Sanctum authentication token.

#### Request

**URL:** `POST /api/mobile/v1/auth/login`

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

## Using the Authentication Token

After successful login, you must include the token in all authenticated API requests.

### Authorization Header

Include the token in the `Authorization` header using the Bearer token format:

```
Authorization: Bearer {token}
```

**Example:**
```
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### Token Storage

**⚠️ Security Best Practice:** Store tokens securely using platform-specific secure storage:

- **Flutter:** Use `flutter_secure_storage`
- **React Native:** Use `react-native-keychain`
- **iOS (Native):** Use Keychain Services
- **Android (Native):** Use EncryptedSharedPreferences

**❌ Never store tokens in:**
- SharedPreferences / UserDefaults
- Plain text files
- Local storage / AsyncStorage (unencrypted)
- URL parameters
- Browser cookies (for mobile apps)

---

## Code Examples

### Flutter (Dart)

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class AuthService {
  static const String baseUrl = 'https://your-domain.com/api/mobile/v1';
  final _storage = const FlutterSecureStorage();

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/login'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'email': email,
          'password': password,
          'remember': true,
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        
        // Store token securely
        await _storage.write(
          key: 'auth_token',
          value: data['data']['token'],
        );
        
        // Store user data (optional)
        await _storage.write(
          key: 'user_data',
          value: jsonEncode(data['data']['user']),
        );
        
        return data;
      } else {
        final error = jsonDecode(response.body);
        throw Exception(error['message'] ?? 'Login failed');
      }
    } catch (e) {
      throw Exception('Network error: ${e.toString()}');
    }
  }

  Future<String?> getToken() async {
    return await _storage.read(key: 'auth_token');
  }

  Future<void> logout() async {
    final token = await getToken();
    if (token != null) {
      await http.post(
        Uri.parse('$baseUrl/auth/logout'),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );
    }
    await _storage.delete(key: 'auth_token');
    await _storage.delete(key: 'user_data');
  }
}
```

### React Native (JavaScript/TypeScript)

```typescript
import AsyncStorage from '@react-native-async-storage/async-storage';
import * as Keychain from 'react-native-keychain';

const BASE_URL = 'https://your-domain.com/api/mobile/v1';

interface LoginResponse {
  success: boolean;
  message: string;
  data: {
    user: {
      id: number;
      name: string;
      email: string;
      phone: string | null;
      role: string;
    };
    token: string;
  };
}

export const login = async (
  email: string,
  password: string
): Promise<LoginResponse> => {
  try {
    const response = await fetch(`${BASE_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        email,
        password,
        remember: true,
      }),
    });

    const data: LoginResponse = await response.json();

    if (response.ok && data.success) {
      // Store token securely
      await Keychain.setGenericPassword('auth_token', data.data.token);
      
      // Store user data
      await AsyncStorage.setItem(
        'user_data',
        JSON.stringify(data.data.user)
      );
      
      return data;
    } else {
      throw new Error(data.message || 'Login failed');
    }
  } catch (error) {
    throw new Error(`Network error: ${error}`);
  }
};

export const getToken = async (): Promise<string | null> => {
  try {
    const credentials = await Keychain.getGenericPassword();
    return credentials ? credentials.password : null;
  } catch (error) {
    return null;
  }
};

export const logout = async (): Promise<void> => {
  const token = await getToken();
  if (token) {
    await fetch(`${BASE_URL}/auth/logout`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });
  }
  await Keychain.resetGenericPassword();
  await AsyncStorage.removeItem('user_data');
};
```

### iOS (Swift)

```swift
import Foundation

class AuthService {
    static let baseURL = "https://your-domain.com/api/mobile/v1"
    
    struct LoginRequest: Codable {
        let email: String
        let password: String
        let remember: Bool
    }
    
    struct LoginResponse: Codable {
        let success: Bool
        let message: String
        let data: LoginData
        
        struct LoginData: Codable {
            let user: User
            let token: String
            
            struct User: Codable {
                let id: Int
                let name: String
                let email: String
                let phone: String?
                let role: String
            }
        }
    }
    
    func login(email: String, password: String) async throws -> LoginResponse {
        guard let url = URL(string: "\(Self.baseURL)/auth/login") else {
            throw URLError(.badURL)
        }
        
        let requestBody = LoginRequest(
            email: email,
            password: password,
            remember: true
        )
        
        var request = URLRequest(url: url)
        request.httpMethod = "POST"
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")
        request.setValue("application/json", forHTTPHeaderField: "Accept")
        request.httpBody = try JSONEncoder().encode(requestBody)
        
        let (data, response) = try await URLSession.shared.data(for: request)
        
        guard let httpResponse = response as? HTTPURLResponse else {
            throw URLError(.badServerResponse)
        }
        
        if httpResponse.statusCode == 200 {
            let loginResponse = try JSONDecoder().decode(LoginResponse.self, from: data)
            
            // Store token in Keychain
            KeychainHelper.save(token: loginResponse.data.token)
            
            return loginResponse
        } else {
            throw URLError(.badServerResponse)
        }
    }
}

// Keychain Helper
import Security

class KeychainHelper {
    static func save(token: String) {
        let data = token.data(using: .utf8)!
        let query: [String: Any] = [
            kSecClass as String: kSecClassGenericPassword,
            kSecAttrAccount as String: "auth_token",
            kSecValueData as String: data
        ]
        SecItemDelete(query as CFDictionary)
        SecItemAdd(query as CFDictionary, nil)
    }
    
    static func getToken() -> String? {
        let query: [String: Any] = [
            kSecClass as String: kSecClassGenericPassword,
            kSecAttrAccount as String: "auth_token",
            kSecReturnData as String: true
        ]
        
        var result: AnyObject?
        let status = SecItemCopyMatching(query as CFDictionary, &result)
        
        if status == errSecSuccess,
           let data = result as? Data,
           let token = String(data: data, encoding: .utf8) {
            return token
        }
        return nil
    }
}
```

### Android (Kotlin)

```kotlin
import okhttp3.*
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.RequestBody.Companion.toRequestBody
import org.json.JSONObject
import android.content.Context
import androidx.security.crypto.EncryptedSharedPreferences
import androidx.security.crypto.MasterKey

class AuthService(private val context: Context) {
    private val baseUrl = "https://your-domain.com/api/mobile/v1"
    private val client = OkHttpClient()
    
    data class LoginRequest(
        val email: String,
        val password: String,
        val remember: Boolean = true
    )
    
    data class LoginResponse(
        val success: Boolean,
        val message: String,
        val data: LoginData
    ) {
        data class LoginData(
            val user: User,
            val token: String
        ) {
            data class User(
                val id: Int,
                val name: String,
                val email: String,
                val phone: String?,
                val role: String
            )
        }
    }
    
    suspend fun login(email: String, password: String): LoginResponse {
        val json = JSONObject().apply {
            put("email", email)
            put("password", password)
            put("remember", true)
        }
        
        val requestBody = json.toString()
            .toRequestBody("application/json".toMediaType())
        
        val request = Request.Builder()
            .url("$baseUrl/auth/login")
            .post(requestBody)
            .addHeader("Content-Type", "application/json")
            .addHeader("Accept", "application/json")
            .build()
        
        client.newCall(request).execute().use { response ->
            val responseBody = response.body?.string() ?: throw Exception("Empty response")
            
            if (response.isSuccessful) {
                val jsonResponse = JSONObject(responseBody)
                val token = jsonResponse.getJSONObject("data").getString("token")
                
                // Store token securely
                saveToken(token)
                
                // Parse and return response
                return parseLoginResponse(responseBody)
            } else {
                throw Exception("Login failed: ${response.code}")
            }
        }
    }
    
    private fun saveToken(token: String) {
        val masterKey = MasterKey.Builder(context)
            .setKeyScheme(MasterKey.KeyScheme.AES256_GCM)
            .build()
        
        val sharedPreferences = EncryptedSharedPreferences.create(
            context,
            "auth_prefs",
            masterKey,
            EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
            EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
        )
        
        sharedPreferences.edit()
            .putString("auth_token", token)
            .apply()
    }
    
    fun getToken(): String? {
        val masterKey = MasterKey.Builder(context)
            .setKeyScheme(MasterKey.KeyScheme.AES256_GCM)
            .build()
        
        val sharedPreferences = EncryptedSharedPreferences.create(
            context,
            "auth_prefs",
            masterKey,
            EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
            EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
        )
        
        return sharedPreferences.getString("auth_token", null)
    }
    
    private fun parseLoginResponse(json: String): LoginResponse {
        // Implement JSON parsing
        // This is a simplified version - use proper JSON parsing library
        return LoginResponse(
            success = true,
            message = "Login successful",
            data = LoginResponse.LoginData(
                user = LoginResponse.LoginData.User(
                    id = 1,
                    name = "",
                    email = "",
                    phone = null,
                    role = ""
                ),
                token = ""
            )
        )
    }
}
```

### cURL Example

```bash
curl -X POST https://your-domain.com/api/mobile/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123",
    "remember": true
  }'
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
      "email": "user@example.com",
      "phone": "+255123456789",
      "role": "user"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  }
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

### Error Handling Example (Flutter)

```dart
try {
  final response = await authService.login(email, password);
  // Handle success
  Navigator.pushReplacementNamed(context, '/dashboard');
} on ValidationException catch (e) {
  // Handle validation errors
  showErrorDialog(e.message);
} on NetworkException catch (e) {
  // Handle network errors
  showErrorDialog('Please check your internet connection');
} catch (e) {
  // Handle other errors
  showErrorDialog('An unexpected error occurred');
}
```

---

## Security Best Practices

1. **Always use HTTPS** in production
2. **Store tokens securely** using platform-specific secure storage
3. **Never log tokens** in console or logs
4. **Implement token refresh** if needed (check token expiration)
5. **Clear tokens on logout** and app uninstall
6. **Validate input** on client-side before sending
7. **Handle 401 errors** by redirecting to login
8. **Use certificate pinning** for extra security (optional)

---

## Token Management

### Token Expiration

By default, Sanctum tokens do not expire. However, you can:

1. **Set expiration** in `config/sanctum.php`:
   ```php
   'expiration' => 60 * 24, // 24 hours in minutes
   ```

2. **Check token validity** by making an authenticated request

3. **Handle expired tokens** by catching 401 responses and redirecting to login

### Logout

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

After logout:
1. Delete the token from secure storage
2. Clear any cached user data
3. Redirect to login screen

---

## Testing

### Test Credentials

Contact your backend team for test account credentials.

### Test Environment

```
Base URL: http://localhost:8000/api/mobile/v1
```

### Testing with Postman

1. Create a new POST request to `/api/mobile/v1/auth/login`
2. Set headers:
   - `Content-Type: application/json`
   - `Accept: application/json`
3. Add body (raw JSON):
   ```json
   {
     "email": "test@example.com",
     "password": "password123",
     "remember": true
   }
   ```
4. Send request
5. Copy the token from response
6. Use token in subsequent requests with `Authorization: Bearer {token}` header

---

## Troubleshooting

### Issue: "The provided credentials do not match our records"

**Solutions:**
- Verify email and password are correct
- Check if account exists and is active
- Ensure password hasn't been changed
- Check for typos in email (case-sensitive)

### Issue: Token not working in subsequent requests

**Solutions:**
- Verify token is being sent in `Authorization: Bearer {token}` header
- Check token wasn't deleted from storage
- Ensure token format is correct (no extra spaces)
- Verify API endpoint is correct

### Issue: Network errors

**Solutions:**
- Check internet connection
- Verify base URL is correct
- Check if server is running
- Verify SSL certificate (for HTTPS)

### Issue: 500 Server Error

**Solutions:**
- Contact backend team
- Check server logs
- Verify database connection
- Retry after a few moments

---

## Related Endpoints

- **Register:** `POST /api/mobile/v1/auth/register`
- **Get User:** `GET /api/mobile/v1/auth/user` (requires authentication)
- **Logout:** `POST /api/mobile/v1/auth/logout` (requires authentication)
- **Forgot Password:** `POST /api/mobile/v1/auth/forgot-password`

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

