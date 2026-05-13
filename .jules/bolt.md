## 2025-05-22 - Optimize Model Update Validation
**Learning:** In Laravel/Livewire components, applying 'unique' or 'exists' validation rules on every save triggers a database query even if the value hasn't changed.
**Action:** Conditionally apply these rules by checking if the input value differs from the current model's value to avoid redundant database hits.
