## 2025-05-14 - Conditional Validation for Performance
**Learning:** Conditionally applying 'unique' or 'exists' validation rules in Laravel/Livewire components when the input value matches the existing model value avoids redundant database queries.
**Action:** Always check if the value has changed before applying database-intensive validation rules in update forms.
