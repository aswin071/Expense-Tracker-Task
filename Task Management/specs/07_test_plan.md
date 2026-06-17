# Test Plan

Manual verification checklist. Each scenario should be tested in order after the full build is complete.

---

## 1. Authentication

### Register
- [ ] Navigate to `/register`
- [ ] Submit with all valid fields → redirected to `/dashboard`
- [ ] Submit with mismatched passwords → error shown inline
- [ ] Submit with duplicate email → error shown inline
- [ ] Submit with empty fields → field-level errors shown

### Login
- [ ] Navigate to `/login`
- [ ] Submit with correct credentials → redirected to `/dashboard`
- [ ] Submit with wrong password → error shown inline
- [ ] Submit with unregistered email → error shown inline
- [ ] Check "remember me" → session persists after browser close

### Access Control
- [ ] Open a private browser window and navigate to `/expenses` → redirected to `/login`
- [ ] Navigate to `/dashboard` without login → redirected to `/login`
- [ ] Try accessing another user's expense URL (e.g. `/expenses/1`) while logged in as a different user → 403

### Logout
- [ ] Click logout → redirected to `/login`
- [ ] Navigate to `/dashboard` after logout → redirected to `/login`

---

## 2. Expense CRUD

### Create
- [ ] Navigate to `/expenses/create`
- [ ] Confirm awareness card is visible before the form
- [ ] Click "I'm aware, proceed" → form becomes visible
- [ ] Fill all fields and submit → redirected to `/expenses` with success message
- [ ] Submit with `amount = 0` → error shown
- [ ] Submit with future date → error shown
- [ ] Submit with empty description → error shown

### List / Filter
- [ ] Navigate to `/expenses` → expenses are shown, paginated
- [ ] Filter by month and year → only matching expenses shown
- [ ] Filter by category → only matching expenses shown
- [ ] Combine filters → correct subset shown

### View
- [ ] Click an expense → `/expenses/{id}` shows all fields correctly
- [ ] If receipt was uploaded, it displays as image or PDF link

### Edit
- [ ] Click edit on an expense → form pre-filled with existing data
- [ ] Change amount and save → shows updated value on detail page
- [ ] Upload a new receipt → old receipt replaced (check `storage/app/public/receipts/`)

### Delete
- [ ] Click delete → expense removed from list
- [ ] Verify receipt file is deleted from `storage/app/public/receipts/`

---

## 3. Receipt Upload

- [ ] Create expense with a `.jpg` file → saved, displayed as `<img>` on detail page
- [ ] Create expense with a `.pdf` file → saved, displayed as download link
- [ ] Try uploading a `.gif` file → validation error shown
- [ ] Try uploading a file larger than 2 MB → validation error shown
- [ ] Create expense without a receipt → form submits successfully, no image shown on detail

---

## 4. Recurring Expenses

### Create
- [ ] Navigate to `/recurring`
- [ ] Fill in amount, description, category, day_of_month (e.g. 15) → saved, shown in list
- [ ] Try `day_of_month = 29` → validation error

### Toggle
- [ ] Click toggle on a recurring expense → status switches between active/inactive
- [ ] Inactive entries are visually distinct (e.g. greyed out)

### Auto-Log Command
- [ ] Set `day_of_month` to today's date (e.g. if today is the 17th, use 17)
- [ ] Run: `php artisan expenses:log-recurring`
- [ ] Navigate to `/expenses` → new expense auto-created matching the recurring template
- [ ] Run the command again → no duplicate created for the same month
- [ ] Navigate to `/dashboard` → banner shows count of auto-logged expenses

### Edit / Delete
- [ ] Edit a recurring expense → values updated
- [ ] Delete a recurring expense → removed from list, does not affect already-created expenses

---

## 5. Pre-Spend Awareness

- [ ] Create at least one expense in a specific category (e.g. food, ₹500)
- [ ] Navigate to `/expenses/create`
- [ ] Awareness card shows the correct total for the food category this month
- [ ] Click "I'm aware, proceed" → form appears
- [ ] Navigate to `/coach` → full category breakdown shown for current month

---

## 6. Reports

### Monthly Report
- [ ] Navigate to `/reports/monthly`
- [ ] Default view shows current month's data
- [ ] Bar chart renders with category labels and amounts
- [ ] Monthly total and daily average are correct

### Month Filter
- [ ] Change month/year using the filter form → chart and totals update correctly
- [ ] Select a month with no expenses → chart shows empty or zero values

### All-Time Totals
- [ ] All-time section on the monthly report page shows cumulative category totals
- [ ] Adding a new expense increases the relevant all-time category total

---

## 7. Edge Cases

- [ ] Delete all expenses → expense list shows empty state message
- [ ] User with no expenses views `/reports/monthly` → no errors, empty chart
- [ ] User with no recurring expenses views `/recurring` → empty state shown
- [ ] Receipt storage symlink is active: `php artisan storage:link` was run, `/storage/` URLs resolve in browser

---

## 8. Data Isolation

- [ ] Register two separate user accounts (User A and User B)
- [ ] Log expenses as User A
- [ ] Log in as User B → User B sees only their own expenses on all pages
- [ ] User B cannot access `/expenses/{id}` where the expense belongs to User A → 403
