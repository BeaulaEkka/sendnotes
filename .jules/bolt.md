## 2026-05-16 - Conditional Validation for Performance
**Learning:** Laravel's `Rule::unique(...)->ignore($id)` still executes a database query even if the value hasn't changed. In forms like profile updates where the value (email) is often unchanged, this results in a redundant query.
**Action:** Apply the `unique` rule conditionally by checking if the input value differs from the model's current value (e.g., `$this->email !== $user->email ? Rule::unique(...) : null`). Use `null` as the fallback to ensure the validator ignores the entry.

## 2024-05-18 - Using Bail for Expensive Validations
**Learning:** Laravel's validator continues to check all rules for an attribute by default. When using expensive rules like `unique` (database query) or `current_password` (BCrypt hash comparison), this results in wasted resources if basic validations like `required` or `email` already failed.
**Action:** Always include the `bail` rule as the first rule for attributes that use expensive validation logic.
