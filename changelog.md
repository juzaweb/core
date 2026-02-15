### v5.0.3 
* fix(theme-loading): wrap theme booting, provider registration, and file inclusion in a try-catch block to handle exceptions.
* feat(notifications): Use UUIDs for polymorphic notifiable relationships and display dynamic notification titles in the navbar.
* refactor(notification): remove NotificationSubscription model
* chore(core): remove notification subscriptions table migration
* feat: remove NotificationSubscribeControllerTest.php
* chore: Remove notification subscription routes for cleanup and maintenance
* refactor: Move notification classes to Mail namespace for better organization
* feat: Add Menu facade to TestCase for improved accessibility in tests
* refactor(database): change personal_access_tokens table primary key from UUID to auto-incrementing ID.
* docs(menus): Create dedicated documentation for admin menus and refine navigation links.
* feat(admin-profile): dynamically register and render profile sidebar menu items
* feat(admin-menu): dynamically render profile menu items in the admin top navbar.
* refactor(navbar): optimize user authentication and notification handling
* feat(sidebar): add file size validation for avatar upload and improve notification messages
* feat(profile): add avatar change functionality and update profile UI
* feat(user-avatar): update avatar handling to use media library and improve image retrieval
* refactor(index.blade.php): comment out unused AJAX installation logic and reorder reload functionality
* feat(modules): add install route and controller method for module installation
* feat(blog): add post categories to MenuBox and update AdminServiceProvider icon
* Add unit test for module:update command using juzaweb/blog as subject
* ♻️ refactor(ThemeInstallCommand): Comment out theme update option and related code for clarity
* Add unit test for module:install command
* ♻️ refactor(ModuleInstallCommand): Comment out 'no-update' option and related logic for clarity
* ♻️ refactor(tests): Update response status assertions in ProfileControllerTest and SettingTest to reflect correct authentication errors
* ♻️ refactor: Introduce custom Authenticate middleware and streamline authentication handling in Admin routes
* fix(core): enhance internal URL validation by handling parse_url failures and restricting relative URL schemes.
* feat: Implement internal URL redirection handling in authentication flow and add is_internal_url helper function
* feat: Introduce a filter for post-login redirect URLs and remove the custom `RedirectIfAuthenticated` middleware.
* feat: Add permission keys for various admin menu items in AdminServiceProvider
* ♻️ refactor(PublishCoreCommand): Update command signature and move options to getOptions method for clarity
* ♻️ refactor(PermissionRegistrar): Update cache driver configuration to use 'cache.default' for improved clarity
* ♻️ refactor(PermissionRegistrar): Update teamId property type to allow null values
* ♻️ refactor(HasPermissions): Update hasPermissionTo method to check if instance is User for super admin validation
* ♻️ refactor: Enhance permission handling for dashboard access and clean up service provider
* ♻️ refactor(MenuRepository, sidebar): Improve code readability by updating comments and simplifying menu filtering logic
* feat: Enhance menu permissions handling and add Menu facade to composer.json
* fix(core): add missing permission checks to admin views and datatables
* feat: Enhance user creation response with redirect URL and structured message
* feat: Implement permission lookup by code, enhance role selection in user form, and refine role management UI.
* feat(roles): automatically create missing permissions when assigning to roles and add a collection method to the permission manager.
* docs(permissions): remove example for Laravel `can` middleware usage
* Add tests for Role management and fix related issues
* docs: Update route permission documentation to introduce the `permission()` macro and clarify middleware usage.
* refactor(routes): replace permission middleware array with dedicated permission method
* feat(permissions): automatically register permissions when using the route macro and update admin routes to use it
* feat: Add new translation strings for module installation and role management
* feat: Enhance user roles menu structure, update command naming, and improve form autocomplete functionality
* chore: Remove jules environment setup script
* chore: Ignore `setup.sh` in `.gitignore`.
* feat: Add argument parsing for PHP version and make Node.js installation optional.
* feat(setup): add jules environment setup script for PHP and Node.js installation
* feat(core): register Translations and Permission Service Providers.
* ♻️ refactor(FileRepository): Update property types for better type safety and clarity
* ♻️ refactor(FileRepository): Improve type hinting and update parameter descriptions in method docblocks
* refactor(modules): remove composer output configuration and enforce quiet mode for composer commands
* refactor(config): Update module configuration keys to use the 'dev-tool' prefix.
* ♻️ feat(commands): Add module and theme management commands for enabling, disabling, listing, and updating
* fix(core): Correct ModuleInstallCommand namespace, register it, and update modules stub path config in tests.
* refactor(modules): remove module generator classes.
* ♻️ refactor: Remove unused stubs configuration from modules.php and themes.php
* ♻️ refactor(Stub, ThemeServiceProvider): Update property types and remove unused command registrations
* ♻️ refactor(ModulesServiceProvider): Remove unused stub setup and namespace registration methods

### v5.0.2 
* feat(core): introduce PublishCoreCommand and register it in ConsoleServiceProvider
* feat(core-assets): add default thumbnail image asset

### v5.0.1 
* ♻️ refactor(Media): Remove unused website function import and enhance image optimization condition
* ♻️ refactor(upload): Add overwrite option for media uploads and update related event handling
* ♻️ refactor(UploadToCloudListener): Update cloud disk name from 'cloud_write' to 'cloud'
* ♻️ refactor(UploadToCloudJob): Improve logging for skipped cloud uploads when file exists
* ♻️ refactor(UploadToCloudJob): Include file path in exception message for existing uploads
* ♻️ refactor(controller): Remove websiteId parameter from controller methods for cleaner routing
* chore: update homepage link and add juzaweb/core dependency in composer.stub and related stubs
* chore: update font path in AddonController and enhance cloud path handling in helpers
* Remove usage examples and commands from README
* Improve README.md with comprehensive documentation

### v5.0.0 
* docs: improve clarity and consistency in installation and CRUD guides by clarifying optional steps, correcting module path casing, and simplifying headings.
* docs(installation): update example URL in installation guide
* docs(installation): add section on license key and marketplace configuration.
* fix(i18n): Correct marketplace description casing in English translation.
* feat: add title field to module stub JSON stub.
* fix(HasThumbnail): return original URL if thumbnail size is not provided
* refactor(thumbnail handling): Simplify thumbnail retrieval logic and improve default fallback
* refactor(modules): Add strict type hints to installer methods and disable composer script copying in the updater.
* refactor(modules): update PublishCommand parameter type hint and rename InstallCommand
* feat(theme): Add post-installation migration and publish steps for themes, and update module updater to support theme objects.
* feat(theme): refactor theme installation and update command to use ThemeContract
* refactor(theme installation): Extract theme installation logic to dedicated controller
* feat: implement marketplace feature with module listing and installation options
* feat(theme-marketplace): Display installed status for themes and disable install button in the marketplace view.
* feat: Allow static configuration of thumbnail size and provide an override option in the `getThumbnail` method.
* ✨ feat(HasThumbnail): Add support for dynamic thumbnail resizing
* refactor(media): simplify media item URL generation and PDF extension check in admin media item view.
* fix(core): enhance error reporting in AddonController and improve ImgProxyService robustness for directory creation and S3 operations.
* fix(imgproxy): improve cloud storage URL detection by checking endpoint if 'url' is missing
* feat(marketplace): add theme installation guide modal with copy command functionality and related translations.
* fix: update thumbnail placeholder size in marketplace item display
* refactor(admin/theme): Update placeholder image service to `placehold.co` and remove marketplace response log.
* feat(admin/themes): Introduce theme marketplace for browsing and installing themes
* feat(themes): Add configuration to conditionally display the theme upload button in the admin panel.
* refactor(theme): refine path validation logic in admin controller
* feat(core): add accepted file types translation string
* feat(api): enhance response structure by adding success flag to additional data
* ✨ feat(APIController): Add method to retrieve and validate request limit from request
* 🔧 chore(stubs): Update default license to GPL-2.0 in composer stubs
* ⚙️ chore(license): Update packages license to GPL-2.0
* ♻️ refactor(stubs): Simplify module admin route prefix and refine provider stub paths
* feat(process): use explicit PHP binary path for composer commands and improve JSON error handling in updater.
* refactor(modules): add parameter type hints to install command method
* feat(themes): add commands for theme installation and updating, leveraging a new repository adapter for compatibility with module management.
* fix: Use `php composer.phar` for composer dependency installation in the installer process.
* refactor(modules): introduce strict type declarations for properties and method return types in module installer and commands.
* 🐛 fix(Media): Use provided filename for cloud media downloads
* ✨ feat(mime-type): Add mime_type_from_extension helper and integrate into Media model
* ♻️ refactor(frontend/auth): Remove all frontend authentication views
* 🐛 fix(verification-notice): Clear form action for AJAX submission
* refactor(routing): update cloud media route registration condition and remove domain grouping
* ✨ feat(media): Implement cloud media streaming with range requests and caching
* 🧪 test(module path): Update assertion to expect kebab-case for non-existent modules
* perf: optimize JSON attribute retrieval in Setting/ThemeSetting
* docs(core): add documentation and contributing sections to README
* 🐛 fix(themes): Prevent app crash on missing theme modules
* ✨ feat(cloud): Support path-style endpoints for cloud storage write operations
* refactor(file-manager): remove redundant targetDisk parameter from UploadToCloudJob constructor
* feat(theme): make theme argument optional and default to current theme for publishing
* docs: Add comprehensive documentation for checking permissions and format helper function names in documentation.
* docs(the-basics): extract breadcrumb, thumbnail, and sitemap documentation into dedicated files and update menu navigation.
* docs(templates): enhance page template documentation with detailed examples, registration steps, and best practices.
* ♻️ refactor(core): Streamline module structure, API, and command registration
* refactor: remove unused `Juzaweb\Modules\Core\Models\Model` import from Guest model
* feat(modules): Enforce theme-required modules, remove controller middleware, and update frontend message key in module management.
* ♻️ refactor(core): Standardize admin message handling and add type hints
* ♻️ refactor(AddonController): Relocate from Frontend namespace and integrate message removal
* feat: Implement dismissible admin messages and improve module provider registration error handling with updated type hints.
* feat(docs): enhance CRUD guide with detailed steps, migration standards, and service pattern implementation.
* docs: remove API reference from CRUD documentation.
* fix(AddonController): adjust font size calculation based on character length
* feat(HasThumbnail): add `getDefaultThumbnail` method for custom default thumbnail retrieval.
* ✨ feat(media): Add temporary URLs and use for public module distribution
* 🐛 fix(Media): Return media path directly if it's a URL
* refactor: remove redundant try-catch block from UploadToCloudJob
* refactor: introduce `cloud()` helper for dynamic cloud storage disk selection and integrate it into `UploadToCloudJob`.
* feat(themes): add new stub files for changelog, package.json, webpack, and readme to theme configuration.
* feat: add `theme:make-widget` command to generate theme widgets and register them in the service provider.
* refactor(themes): Update generated theme file paths to include 'src/' and disable automatic theme activation.
* feat(themes): add warning for non-existent CSS files in DownloadStyleCommand
* docs: Document `theme:download-template` and `theme:download-style` commands.
* feat: Standardize theme naming to kebab-case and enable theme activation prompt after generation.
* feat: Enhance theme generation with new stubs for testing, composer, gitignore, and a theme-specific config file.
* refactor(generators): standardize module lower name replacement to kebab-case
* feat(module-scaffolding): enable and enhance the composer.json stub with added dependencies, autoloading, and configuration for new modules.
* refactor: Generate module paths using kebab-case instead of StudlyCase when a module is not found.
* feat(modules): add module test stubs and integrate them into the module generator.
* feat: Introduce new module stubs for webpack.mix.js, package.json, .gitignore, and README.md, and update module generation configuration to use Laravel Mix.
* docs: Document `Hook` facade usage for managing actions and filters with code examples.
* docs(hooks): Expand documentation for actions and filters with detailed helper functions and usage examples.
* docs(widgets): remove handler configuration example for recent posts widget
* docs(commands): Add documentation for the `language:make` Artisan command.
* docs(permissions): Remove specific location examples for permission registration.
* docs(permissions): add documentation for the PermissionManager and its usage.
* docs(resource-routes): Add docblocks to methods and properties in Resource and AdminResource classes.
* refactor(admin/roles): Update role form to fetch permissions via PermissionManager and adjust template for new data structure.
* feat(admin-resource): dynamically register permissions for index, edit, create, and destroy actions.
* feat(core): introduce a permission management system with contract, facade, and service implementation.
* refactor(documentation): remove redundant header from Google Analytics setup guide
* feat(documentation): update installation and translation documentation for clarity and conciseness
* docs(menu): Reorder 'Commands' and rename 'Google Analytics Setup' to 'Dashboard Analytics Setup' in documentation menu.
* docs(menu): update Installation URL to reflect correct path
* docs(menu): update Getting Started and Installation navigation URLs
* docs(installation): Move installation instructions to index.md and update menu navigation.
* docs(menu): Reorder 'Asset Compilation' and 'Commands' and remove 'RESTful API' from the documentation menu.
* docs(helpers): add explanation and example for HasThumbnail trait functionality
* docs(core): add documentation for the Breadcrumb facade, including usage and available methods.
* docs(themes): add documentation for templates, blocks, widgets, sidebars, and menus, and update the navigation menu.
* docs: Add documentation for theme menus, templates, widgets, and the thumbnail helper.
* docs: add documentation for Setting, Thumbnail, and Sitemap facades, and update the basic helpers documentation and menu.
* feat(docs): add comprehensive documentation for routing macros and their customization
* docs(themes): Add documentation for the ThemeSetting facade, update the navigation menu, and refine the Theme helpers description.
* docs: Add browser-based installation instructions and remove redundant installation steps from the index.
* docs(themes): add documentation for Theme helpers and update navigation menu.
* docs(installation): update vendor publish tags from `juzaweb-*` to `core-*`
* docs(menu): reorganize and expand documentation navigation with new sections and updated module/theme structures
* docs(themes): Document theme artisan commands and asset compilation, and update menu navigation.
* feat(docs): add documentation for module asset management using Laravel Mix.
* docs(themes): enhance theme information with an introduction, features, and folder structure.
* docs(theme): Update theme information from 'igame' to 'itech', including title, description, author, keywords, providers, and requirements.
* docs: create changelog reference documentation.
* docs(modules): document module management and generator commands.
* docs(modules): add new documentation files for helpers, commands, and CRUD, and refactor module facade and method documentation into helpers.md.
* docs: add getting started documentation for installation, updates, and CRUD modules
* docs(fields): update form field documentation with fluent API examples, global options, new field types, and custom field creation.
* docs(menu): update core documentation menu by replacing 'RESTful API' with 'Form Fields', 'Commands', and 'Hooks Actions'.
* feat(docs): add documentation for Artisan commands, form fields, and hooks, and update helper function descriptions.
* feat: implement user role management and permission assignment
* 📚 docs(README): Add features section and refine test instructions
* docs(menu): reorganize documentation navigation by moving existing items and adding new module-related entries.
* docs: Add Themes menu item to documentation.
* feat: remove amount field from user model for data consistency
* feat(tag): integrate HasFrontendUrl trait and update taggable method type hint
* ♻️ refactor(getMeta): Allow getMeta method to return null
* feat: Implement forced path functionality in MediaUploader to specify upload location and update existing media records.
* Add PostgreSQL to GitHub Actions test matrix
* ⚡ Bolt: Optimize setting batch updates
* Fix translations table migration for PostgreSQL compatibility
* fix(theme): Update theme thumbnail path to `assets/public/images` location.
* feat(theme): rename screenshotUrl to thumbnailUrl and update references in theme-item view
* perf: remove redundant query caching in HasViews trait
* feat(core): register Breadcrumb facade alias in composer.json
* refactor: increase locale column length in translation tables and generalize authentication middleware in theme routes
* feat: create before-init.php to initialize environment configuration
* ♻️ refactor(Translatable): Move trait implementation to core Traits and deprecate old one
* ♻️ refactor(stubs): Update Juzaweb namespaces from Admin to Core
* ✨ feat(admin/theme): Add loading state and integrate Dropzone error handling for theme upload
* feat(theme): implement theme installation from uploaded zip file with validation and error handling
* feat(upload): Increase theme upload file size, enhance Dropzone event handling, and improve temporary filename generation.
* feat(i18n): add upload theme and success message translations and fix middleware typo.
* feat(upload): implement chunked temporary file upload and integrate it for theme uploads, while also adjusting media upload chunk size.
* feat(theme): Introduce `screenshotUrl` method for themes and update thumbnail generation font path.
* feat(admin/modules): Implement admin interface for listing and toggling modules.
* ✨ feat(admin): Implement theme and module management; update theme display logic
* ✨ feat(assets): Update asset proxy paths, add vendor proxy, and type-hint helper
* 🐛 fix(themes): Add nullsafe operator to active_theme function
* ✨ feat(themes): Return first theme as fallback when current theme not found
* ✨ feat(imgproxy): Include filename in proxy URL and update helper function
* 🐛 fix(theme): Safely handle missing current theme
* feat: Introduce Juzaweb static asset proxy and update theme/module asset proxy routes to include specific identifiers.
* ✨ feat(access): Improve site access flow and unauthenticated admin handling
* fix(theme): Ensure active theme exists and remove unused imports
* refactor(themes): update theme stub paths to include 'src/' prefix and change mix.js to webpack.mix.js.
* fix: add null check for theme in AuthController and improve ThemeRepository logic
* feat(modules): change activator from file to database for improved module management
* feat(theme): update theme activator configuration and improve settings structure
* feat(themes): abstract theme activation logic with ThemeActivatorInterface and SettingActivator.
* Split CI tests into parallel unit and feature jobs
* ⚡ Optimize sitemap generation using batched queries
* Add unit tests for QueryCacheable trait
* feat(modules): update module paths and namespaces for improved structure and organization
* fix: update stub paths for modules and themes to use base_path for consistency
* feat(stubs): Add extensive stub templates for module and theme component generation and update related commands and configurations.
* Add AGENTS.md for agent optimization
* chore(license): remove LICENSE file from core package
* perf: optimize ThemeSettingRepository with request-level caching
* feat(routing): Add routes to proxy static assets for storage, themes, and modules.
* feat(static-assets): implement static file proxying with caching, range support, and extended MIME type detection.
* Add default scopeInApi to HasAPI trait and fix test setup
* Add default scopeInApi to HasAPI trait
* feat: Add static asset proxy for themes and modules with caching and range support.
* feat(storage): implement file proxy route and controller method with caching and range support
* Fix count online when Redis is not available
* perf: memoize settings to avoid N+1 hydration
* Add unit tests for commands in src/Commands and fix MakeUserCommand bugs
* Add unit test for RouteResource facade
* Add unit test for Thumbnail facade
* Add unit test for Widget facade
* Add unit tests for Sitemap facade
* Add unit test for NavMenu facade
* Add unit test for Sidebar Facade
* Add unit test for PageTemplate facade
* Add unit test for PageBlock Facade
* Add unit test for Menu facade
* Add unit test for MenuBox facade
* Update PHP DocBlocks for Facades in src/Facades
* Add unit test for Locale facade
* Add unit test for GlobalData Facade
* Add comprehensive unit tests for Field facade
* Add unit test for Field facade
* Add comprehensive unit tests for Chart facade methods
* Add unit test for Chart facade
* feat: add missing methods to Core contracts
* Add unit test for Breadcrumb Facade
* test: add comprehensive unit tests for FileRepository
* test: add unit tests for Module facade
* Add unit test for ThemeSetting facade
* Add unit test for Setting facade
* Add unit tests for Theme Facade
* refactor(tests): use core Guest model directly instead of a local substitute in notification subscribe test.
* Add tests for notification subscribe and verify endpoints
* feat: add Guest model with fillable attributes and UUID support
* Add feature tests for sitemap endpoints
* ci: Add Composer dependency caching to test workflow.
* Add HTTP tests for TranslationController routes
* Add Feature tests for SettingController routes
* feat: add feature tests for general settings routes
* feat(datatables): Conditionally hide the 'translate' bulk action based on the translator configuration.
* feat(translator): add enable/disable configuration option via environment variable
* feat: introduce UserStatus enum and add comprehensive feature tests for user management.
* fix: Correct UserFactory path and namespace in TestCase setup.
* test(pages): add feature tests for PageController covering CRUD operations, validation, and bulk actions.
* test(core): add feature tests for LanguageController covering CRUD and bulk actions
* test(profile): add feature tests for the ProfileController, covering index, update, and validation scenarios.
* fix(database): rename password_reset_tokens table to password_resets
* docs: add Google Analytics setup guide and update documentation menu
* fix(migrations): Add `taggable` table drop to `create_tags_table` migration rollback.
* refactor(tests): remove manual password_resets table creation from test case
* feat: implement basic user authentication scaffolding including registration, login, password management, and profile editing.
* Fix Composer tests and environment setup
* Optimize sitemap generation with caching
* perf: Cache parsed JSON content in Theme class
* Add feature tests for MediaController
* feat(imgproxy): implement image proxy service with new route and controller handler for dynamic image manipulation.
* chore(build): update admin.min.js asset hash in mix-manifest.json
* feat: Merge locale and country configurations and streamline core setup dependencies.
* refactor(admin/user): introduce dedicated user index and form views, update controller paths and translation keys, and remove unused helper.
* feat(users): implement UsersDataTable and register admin routes for user management.
* feat(setup): implement setup check middleware and mark setup as complete after initial configuration.
* feat: Implement page form block management with `page.js`, bundle it into `admin.min.js`, remove direct script inclusion, add `$commentableType` to `CommentController`, and update CSS asset paths.
* feat: add admin user management controller and remove website ID filtering from analytics charts and notification subscriptions
* refactor(core): streamline initial setup by removing member creation and standardizing default language and settings.
* docs(menu): reorganize documentation navigation for Modules and add Models section.
* docs: Update installation guide, add documentation menu, and refactor module registration error handling.
* feat(media): add `in_cloud` boolean column to media table with default false and a descriptive comment.
* refactor: make CommentController abstract and remove its direct admin routes.
* ✅ test(auth): Configure core user model for tests
* refactor(core/tests): remove unused RefreshDatabase trait from UploaderTest
* 🧪 test(activitylog): Register Activitylog service provider
* feat(database): add initial migrations for cache, jobs, password resets, sessions, and refactor user table with UUID.
* 🔥 chore(cleanup): Delete unused auth2 and admin domain views
* 🔥 refactor(core/admin): Remove unused data table classes and customize controller
* ✨ feat(media): Reactivate cloud URL usage and update delete button href
* fix(themes): Throw an exception when a required module for a theme is not found during registration.
* docs: add documentation for RESTful API response helpers, themes, translation, helpers, and modules.
* chore(composer): remove pint format script
* feat(testing): Configure test suite to support MySQL database connections and enable MySQL in CI workflows.
* test(auth): add feature tests for user authentication and create UserFactory
* ci(workflow): Remove code style check from CI workflow and delete Pint configuration file.
* fix: Enhance user migration with column existence checks, initialize permission registrar static properties, and configure testing environment filesystem disks.
* feat: Add initial .gitignore and composer.lock files.
* config: Set module activator to 'file'.
* feat(core): add country and locale configuration files and update theme contract and repository.
* chore(migrations): remove posts and post categories table creation migrations
* fix(themes): handle non-existent theme directory path gracefully by returning an empty collection.
* refactor(core): update Application import in LocaleRepository and add newline to UploaderTest.
* feat: Add `status` and `is_super_admin` columns to the users table and update CI workflow branches.
* chore(core): set up testing, code formatting, and CI/CD workflows with GitHub Actions and update existing test cases.
* refactor(core): make User model concrete and explicitly define its table
* refactor(theme): simplify getCurrentTheme method by directly returning the found theme.
* feat(core): add clear log command, refactor theme service provider, and update admin logo path.
* fix(helpers): update img_proxy encryption key to use app key hash fix(admin): correct asset path for TinyMCE script inclusion
* refactor(pages): remove HasNetworkWebsite trait from PageTranslation model
* fix(page-translation): Remove incomplete `where` clause from page query scope.
* feat(translation): introduce `Language::findCode` method and enable module filtering in `TranslationController`.
* refactor(language): relocate Language model to Translations namespace and add dynamic URL generation for language switching.
* refactor: update social login configuration references and remove website dependency in various controllers and views
* refactor(core): remove multi-website specific logic, support ticket datatable, and admin navigation links.
* Refactor Media Management and Remove Website ID Dependency
* refactor: remove website ID parameter from routes and actions and delete NetworkSubscription class.
* Refactor authentication views and routes for improved user experience
* chore(online-status): remove reCAPTCHA integration from online status updates
* feat(core): remove admin customization feature and Firebase notification logic, and update core assets
* refactor: streamline JS variables, simplify Google Analytics integration, disable Firebase bundling, and remove unused documentation images.
* refactor(controllers): Remove websiteId parameter from various admin controller methods.
* refactor: remove multi-site network logic and related configurations from core components
* fix(schema): Refine database schema by removing `post_categories.slug` unique constraint and adjusting `posts.views` column.
* refactor(database): remove websiteId column from multiple core package tables.
* refactor(database): remove website_id column and related unique constraints from multiple tables.
* feat: Add asset publishing method in CoreServiceProvider
* feat: Add mix-manifest.json and update webpack.mix.js for asset management
* Add Font Awesome Solid Font Files
* tada: CMS Version 5.x
* Refactor middleware usage in RouteServiceProvider and add file check in SettingRepository for installation status
* Refactor configuration references to use 'core' instead of 'app' for admin prefix and optimization settings across multiple files.
* tada: CMS Version 5.x

