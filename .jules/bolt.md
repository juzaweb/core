## 2024-05-22 - Setting Type Coercion Mystery
**Learning:** Even if `getValueAttribute` returns an integer, `Setting::get` (and `getAttribute`) might return a string in the test environment. This seems to be due to `Astrotomic\Translatable` or `QueryCacheable` interaction or potentially SQLite behavior in tests, where attributes are cast to string despite accessor logic.
**Action:** When testing Setting values, use loose equality (`assertEquals`) or expect strings for numeric values unless explicitly casted by helper methods like `Setting::integer()`.
