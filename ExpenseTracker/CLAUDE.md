# CLAUDE.md
## Build Instructions for Claude Code

### Read Before Coding
Read every file in `/specs/` before writing any PHP code.
Build in this exact order:
1. `specs/02_database_schema.md` → migrations
2. `specs/04_features/auth.md` → Breeze auth
3. `specs/04_features/expense_crud.md` → ExpenseController
4. `specs/04_features/receipt_attachment.md` → file upload
5. `specs/04_features/recurring_expense.md` → RecurringExpenseController + command
6. `specs/04_features/pre_spend_awareness.md` → BudgetCoachService + controller
7. `specs/04_features/reports.md` → ReportController
8. `specs/07_test_plan.md` → verify everything

### Laravel Rules
- Use `php artisan make:` for ALL file generation
- Never write raw SQL — use Eloquent only
- Always use Form Request classes for validation
- Always use Storage facade for file operations
- Always scope queries with `auth()->id()`
- Use `@csrf` in every form
- Use `route()` helper for all redirects
- Use `$errors->first('field')` for error display
