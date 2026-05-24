## 2026-05-16 - Conditional Validation for Performance
**Learning:** Laravel's `Rule::unique(...)->ignore($id)` still executes a database query even if the value hasn't changed. In forms like profile updates where the value (email) is often unchanged, this results in a redundant query.
**Action:** Apply the `unique` rule conditionally by checking if the input value differs from the model's current value (e.g., `$this->email !== $user->email ? Rule::unique(...) : null`). Use `null` as the fallback to ensure the validator ignores the entry.

## 2026-05-17 - Bail Validation Rule for Redundant Checks
**Learning:** Validation rules like `unique` or `current_password` involve database queries or expensive hashing. Without the `bail` rule, these are executed even if previous rules (like `required` or `email` format) fail.
**Action:** Always include `bail` at the start of validation rule arrays for attributes that have database-driven or computationally expensive rules to ensure early exit on failure.

## 2026-05-18 - Optimized Password Confirmation Query
**Learning:** Using `Auth::guard('web')->validate(['email' => $user->email, 'password' => $password])` triggers a database query to re-fetch the user by email. The `current_password` validation rule performs the same check using the already-authenticated user instance, saving one query.
**Action:** Replace manual `Auth::validate` or `Auth::guard()->validate` calls with the `current_password` validation rule whenever checking the authenticated user's password.
