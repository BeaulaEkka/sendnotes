## 2026-05-16 - Conditional Validation for Performance
**Learning:** Laravel's `Rule::unique(...)->ignore($id)` still executes a database query even if the value hasn't changed. In forms like profile updates where the value (email) is often unchanged, this results in a redundant query.
**Action:** Apply the `unique` rule conditionally by checking if the input value differs from the model's current value (e.g., `$this->email !== $user->email ? Rule::unique(...) : null`). Use `null` as the fallback to ensure the validator ignores the entry.

## 2026-05-16 - Optimization with the Bail Rule
**Learning:** Laravel's `unique` validation rule executes a database query even if preceding rules (like `required` or `email`) have already failed. This results in unnecessary database overhead.
**Action:** Use the `bail` rule at the beginning of validation arrays for attributes with database-backed rules. This stops validation after the first failure, preventing expensive queries when basic requirements aren't met.
