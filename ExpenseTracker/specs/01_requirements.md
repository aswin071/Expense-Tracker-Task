# Functional & Non-Functional Requirements

## Functional Requirements

### Authentication
- Users can register with name, email, password
- Users can log in with email and password (with "remember me")
- Users can log out via a POST request
- Unauthenticated users are redirected to /login

### Expense CRUD
- Users can create an expense with: amount, description, category, date, optional receipt
- Users can view a paginated list of their expenses (15 per page)
- Users can filter the expense list by month, year, and/or category
- Users can view the detail of a single expense
- Users can edit any of their own expenses
- Users can delete any of their own expenses (receipt file also deleted)

### Receipt Attachment
- Users can attach a receipt (JPG, JPEG, PNG, or PDF, max 2 MB) when creating or editing an expense
- The receipt is stored on the local public disk under `receipts/{user_id}/`
- Receipts are displayed as an image or download link on the expense detail page
- Deleting an expense also removes its receipt file from storage

### Recurring Expenses
- Users can define a recurring expense with: amount, description, category, day_of_month
- Users can toggle a recurring expense active/inactive
- Users can edit or delete a recurring expense
- An Artisan command (`expenses:log-recurring`) runs daily at 00:01 and auto-creates expense records for all active recurring expenses whose `day_of_month` matches today's date and have not yet been logged this month
- The dashboard shows a banner when recurring expenses were auto-logged today

### Pre-Spend Awareness
- Before the expense creation form is visible, a summary card shows how much the user has already spent in the selected category this month
- The user must click "I'm aware, proceed" to reveal the actual form (Alpine.js toggle)

### Reports
- Monthly report: category totals for a selected month/year
- Daily average: total monthly spend divided by days in the month
- All-time report: lifetime totals per category
- Bar charts rendered with Chart.js (CDN)

## Non-Functional Requirements

### Data Isolation
- Every Eloquent query that retrieves user data must be scoped with `where('user_id', auth()->id())`
- No user must ever see another user's expenses, recurring expenses, or reports
- Authorization checks must use `abort(403)` when the authenticated user does not own the resource

### Code Standards
- Use `php artisan make:*` for all generated files — never create them by hand
- All validation lives in dedicated Form Request classes
- All file I/O goes through Laravel's `Storage` facade
- No raw SQL — Eloquent only
- All redirects use the `route()` helper
- CSRF token included in every form with `@csrf`

### Performance
- Expense list is paginated (15 per page)
- Compound indexes on `(user_id, date)` and `(user_id, category)` for fast filtered queries
