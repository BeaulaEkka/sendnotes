## 2025-05-14 - [Conditional Validation for Performance]
**Learning:** In Laravel/Livewire components, applying validation rules like `unique` unconditionally triggers a database query even if the value hasn't changed. By checking if the value is dirty before adding the rule, we can skip unnecessary queries.
**Action:** Always check if a field has actually changed before applying expensive validation rules like `unique` or `exists` when updating existing records.
