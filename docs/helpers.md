# Helper Functions

The following functions provide utility functionality that can be used throughout the application.

## `client_ip()`

This function returns the client's IP address. It checks if the HTTP_CF_CONNECTING_IP header is set (which is used by Cloudflare) and returns that value if available. Otherwise, it returns the IP address from the `request()->ip()` method.

Example usage:

```php
$ipAddress = client_ip();
```

## `is_json($string)`

This function checks if a given string is a valid JSON string. It uses the `json_decode()` function to attempt to parse the string, and returns `true` if the string is valid JSON and `false` otherwise.

Example usage:

```php
$jsonString = '{"name": "John", "age": 30}';
if (is_json($jsonString)) {
    echo "The string is valid JSON.";
} else {
    echo "The string is not valid JSON.";
}
```

## `setting($key = null, $default = null)`

This function is used to retrieve a setting value from the application's settings. It takes two optional parameters: `$key` (the key of the setting to retrieve) and `$default` (the default value to return if the setting is not found).

Example usage:

```php
$settingValue = setting('example_setting');

// or has a default value

$settingValue = setting('example_setting', 'default_value');
```

## `admin_url()`

Get admin url used in the application

Example usage:

```php
$url = admin_url();

// With custom path

$url = admin_url('dashboard');
```

## `languages()`

Get all languages used in the application

## `date_range()`

Used to generate a date range. E.g. `date_range('2022-01-01', '2022-01-31')` will return array of dates between '2022-01-01' and '2022-01-31'.

## `month_range()`

Used to generate a month range. E.g. `month_range('2022-01', '2022-12')` will return array of months between '2022-01' and '2022-12'.

## `title_from_key()`

Generate title from key, e.g. `title_from_key('users')` will return `Users`, `title_from_key('user_manager')` will return `User Manager`

## `cache_prefix()`

Get prefix for cache keys.

## `format_size_units()`

Used to format a size value with units (e.g., bytes, kilobytes, megabytes).

## `array_to_array_string()`

Used to convert an array to a string representation.

## `used_recaptcha()`

Used to check if reCAPTCHA is being used in the application.

## `is_super_admin()`

Used to check if the current user is a super admin.
