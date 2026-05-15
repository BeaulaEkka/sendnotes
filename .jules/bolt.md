## 2025-05-14 - Redundant Unique Validation Queries
**Learning:** In Laravel, the `unique` validation rule (even with an `ignore` clause) always executes a `SELECT COUNT(*)` query to the database. In Livewire components where the model data is already available, this query is redundant if the attribute value hasn't changed.
**Action:** Conditionally apply `unique` validation rules only when the input value differs from the existing model's value to save one database query per update operation.
