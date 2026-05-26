## 2026-05-16 - Conditional Validation for Performance
**Learning:** Laravel's `Rule::unique(...)->ignore($id)` still executes a database query even if the value hasn't changed. In forms like profile updates where the value (email) is often unchanged, this results in a redundant query.
**Action:** Apply the `unique` rule conditionally by checking if the input value differs from the model's current value (e.g., `$this->email !== $user->email ? Rule::unique(...) : null`). Use `null` as the fallback to ensure the validator ignores the entry.

## 2026-05-17 - Bail Validation Rule for Redundant Checks
**Learning:** Validation rules like `unique` or `current_password` involve database queries or expensive hashing. Without the `bail` rule, these are executed even if previous rules (like `required` or `email` format) fail.
**Action:** Always include `bail` at the start of validation rule arrays for attributes that have database-driven or computationally expensive rules to ensure early exit on failure.

## 2026-05-18 - Efficient Password Confirmation
**Learning:** Manual password validation using `Auth::validate(['email' => $user->email, 'password' => $password])` triggers a redundant database query to re-fetch the user by email.
**Action:** Use the Laravel `current_password` validation rule for authenticated users to verify their password without an extra database lookup.
