# Agent Instructions for Juzaweb Core

This document guides agents (AI or human) on how to work effectively with the `juzaweb/core` package. It consolidates architectural patterns, testing protocols, and specific codebase quirks.

## 1. Architecture & Design Patterns

*   **Facade -> Contract -> Repository Pattern**:
    *   Most core functionalities are exposed via Facades resolving to Contracts, which bind to specific Repositories.
    *   *Example*: `Juzaweb\Modules\Core\Facades\Theme` -> `Juzaweb\Modules\Core\Contracts\Theme` -> `Juzaweb\Modules\Core\Themes\ThemeRepository`.
    *   *Key Bindings*:
        *   `GlobalData` -> `GlobalDataRepository`
        *   `Sidebar` -> `SidebarRepository`
        *   `MenuBox` -> `MenuBoxRepository`
        *   `Thumbnail` -> `ThumbnailRepository`
        *   `Setting` -> `SettingRepository`
    *   *Note*: When testing Facades, look in `tests/Unit/Facades`.

*   **GlobalData & Configuration**:
    *   The `GlobalDataRepository` acts as a central registry.
    *   `SettingRepository` iterates over keys in `GlobalData`.
    *   `ThemeSettingRepository` uses in-memory memoization.

*   **Traits**:
    *   Traits should implement default *empty* hook methods (e.g., `scopeInApi`) rather than relying on `method_exists` checks. This allows models to override them optionally.

## 2. Testing Protocols

*   **Running Tests**:
    *   **ALWAYS** use `vendor/bin/phpunit` directly.
    *   Do **not** rely on the global `phpunit` or `testbench` binary for execution context, as they may miss the local configuration.

*   **Database & Environment**:
    *   Tests use `sqlite` in `:memory:` (defined in `phpunit.xml`).
    *   **Important**: "Unit" tests in `tests/Unit` are often effectively **Integration** tests. They boot the app and interact with the DB.
    *   **Hygiene**: When manually creating tables in `setUp` (using `Schema::create`), **ALWAYS** call `Schema::dropIfExists` first to prevent "table already exists" errors.

*   **Mocking & File Systems**:
    *   For tests involving file repositories (e.g., `FileRepository`), set `modules.paths.modules` to a temp dir using `sys_get_temp_dir()`.
    *   Settings tests: You may need to mock `storage_path('app/installed')` or create/delete this file in `setUp`/`tearDown`.

*   **Common Test Failures & Fixes**:
    *   **Language Constraints**: When creating `Language` models in tests, use `Language::updateOrCreate` to avoid unique constraint violations on the `code` column.
    *   **Request Binding**: For components dependent on `Request` (e.g., `LocaleRepository`), bind a real `Illuminate\Http\Request` via `Request::create()` instead of mocking it to avoid `setUserResolver` errors.
    *   **Notifications**: Verification tests must use `Notification::assertSentOnDemand` for `NotificationSubscribeController`.

## 3. Coding Conventions

*   **Artisan Commands**:
    *   Must return `int` (e.g., `Command::SUCCESS`).
    *   Avoid `exit()`; use `return` to ensure testability with `assertExitCode`.

*   **Repositories**:
    *   `MenuRepository` constructor arguments are strictly ignored by the service provider; do not rely on them.
    *   `GlobalDataRepository::set` strictly requires the value to be an `array`.

## 4. Specific Codebase Quirks

*   **On-Demand Notifications**: The system heavily relies on on-demand notifications for subscriptions.
*   **Redis Handling**: `AddonController` tracks online users via Redis (`site:users_online`). Code touching this **must** wrap operations in `try-catch` to handle Redis unavailability gracefully.
*   **Legacy/Specific Implementations**:
    *   `LocaleRepository` does *not* explicitly implement the `Locale` interface (check this if `assertInstanceOf` fails).
    *   `MenuRepository::make` signature is `make(string $key, callable $callback)`.
