# Process on how HRBMS backend works

## 👤 1. User Registration

-   User registers (ADMIN, FRONT DESK, or GUEST).  
    → See: [`RegisterRepository.php`](../../../app/Repositories/Auth/RegisterRepository.php)

### 🧩 Repositories, Controllers & APIs

| Feature  | Repository                                                                        | Controller               | API                                                    |
| -------- | --------------------------------------------------------------------------------- | ------------------------ | ------------------------------------------------------ |
| Register | [`RegisterRepository.php`](../../../app/Repositories/Auth/RegisterRepository.php) | `RegisterController.php` | [Register Account API](../../Auth/UserRegistration.md) |

## 👤 2. User Login

-   Users can log in as either a GUEST or as FRONT DESK/ADMIN.  
    → See: [`LoginRepository.php`](../../../app/Repositories/Auth/LoginRepository.php)

### 🧩 Repositories, Controllers & APIs

| Feature                | Repository                                                                            | Controller            | API                                         |
| ---------------------- | ------------------------------------------------------------------------------------- | --------------------- | ------------------------------------------- |
| Guest Login            | [`GuestLoginRepository.php`](../../../app/Repositories/Auth/GuestLoginRepository.php) | `LoginController.php` | [Guest Login API](../../Auth/GuestLogin.md) |
| Front Desk/Admin Login | [`LoginRepository.php`](../../../app/Repositories/Auth/LoginRepository.php)           | `LoginController.php` | [Admin Login API](../../Auth/UserLogin.md)  |

<!-- ## 3. After Login

[Click for admin/front desk/guest flow](../../Auth/UserLogin.md#flow) -->
