## 2026-05-16 - Conditional Validation for Performance
**Learning:** Laravel's `Rule::unique(...)->ignore($id)` still executes a database query even if the value hasn't changed. In forms like profile updates where the value (email) is often unchanged, this results in a redundant query.
**Action:** Apply the `unique` rule conditionally by checking if the input value differs from the model's current value (e.g., `$this->email !== $user->email ? Rule::unique(...) : null`). Use `null` as the fallback to ensure the validator ignores the entry.

## 2026-05-18 - Validation Rule Ordering with 'bail'
**Learning:** Using the 'bail' rule in Laravel validation ensures that once a rule fails for a given attribute, the remaining rules for that attribute are not executed. This is particularly effective for preventing expensive database-driven rules (like 'unique' or 'exists') from running if basic format/requirement rules have already failed.
**Action:** Always include 'bail' at the beginning of validation rule arrays when expensive rules are present. For example: `['bail', 'required', 'email', 'unique:users']`.
