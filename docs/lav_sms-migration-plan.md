# lav_sms to SchoolApp Migration Plan

## Scope Summary
- Target: `SchoolApp` (Laravel 12 + Inertia)
- Source: `lav_sms` (Laravel 8)
- Schema: normalize to newer conventions
- Auth: Spatie roles/permissions
- Data migration: not in scope for now
- UI: rebuild in Inertia with modern design

## Role Model (Spatie)
Roles to seed:
- super_admin
- admin
- teacher
- accountant
- parent
- librarian

Permissions (sample grouping):
- manage.users
- manage.roles
- manage.settings
- manage.classes
- manage.sections
- manage.subjects
- manage.exams
- manage.grades
- manage.marks
- manage.promotions
- manage.students
- view.students
- manage.payments
- view.payments
- manage.receipts
- manage.timetables
- view.timetables
- manage.library
- view.library
- manage.dorms

## Feature Map (lav_sms → SchoolApp)
Users & Auth
- Source: `lav_sms/app/Http/Controllers/SupportTeam/UserController.php`
- Tables: `users`, `user_types`
- Target: `users` + Spatie roles

Profiles
- Source: `lav_sms/app/Http/Controllers/MyAccountController.php`
- Tables: `users`
- Target: `users` + profile components

Students
- Source: `SupportTeam/StudentRecordController.php`, `PromotionController.php`
- Tables: `student_records`, `promotions`
- Target: `students`, `student_enrollments`, `promotions`

Staff
- Source: `SupportTeam/UserController.php` + `staff_records`
- Tables: `staff_records`
- Target: `staff_profiles` linked to `users`

Classes & Sections
- Source: `MyClassController.php`, `SectionController.php`
- Tables: `my_classes`, `sections`, `class_types`
- Target: `classes`, `sections`, `class_levels`

Subjects
- Source: `SubjectController.php`
- Tables: `subjects`
- Target: `subjects`, `subject_assignments`

Exams, Marks, Grades, Skills
- Source: `ExamController.php`, `MarkController.php`, `GradeController.php`
- Tables: `exams`, `marks`, `grades`, `skills`, `exam_records`
- Target: `exams`, `assessment_items`, `marks`, `grades`, `exam_results`, `skills`

Pins
- Source: `PinController.php`
- Tables: `pins`
- Target: `access_pins` (optional)

Payments & Receipts
- Source: `PaymentController.php`
- Tables: `payments`, `payment_records`, `receipts`
- Target: `fee_definitions`, `fee_charges`, `payments`, `receipts`

Timetables
- Source: `TimeTableController.php`
- Tables: `time_table_records`, `time_slots`, `time_tables`
- Target: `timetables`, `timeslots`, `timetable_entries`

Library
- Source: `BookController.php`, `BookRequestController.php`
- Tables: `books`, `book_requests`
- Target: `library_books`, `library_loans`

Dorms
- Source: `DormController.php`
- Tables: `dorms`
- Target: `dormitories`, `dorm_assignments`

Settings
- Source: `SettingController.php`
- Tables: `settings`
- Target: `settings`

Reference Data
- Source: `states`, `lgas`, `nationalities`, `blood_groups`
- Target: `states`, `lgas`, `nationalities`, `blood_groups`

## Normalized Schema Proposal (high level)
Core
- users
- roles, permissions (Spatie)
- user_profiles (optional split from users)

Academics
- class_levels
- classes
- sections
- subjects
- subject_assignments
- academic_years
- terms
- exams
- assessment_items
- marks
- grades
- exam_results
- skills

Students & Staff
- students
- student_enrollments
- staff_profiles

Payments
- fee_definitions
- fee_charges
- payments
- receipts

Timetables
- timetables
- timeslots
- timetable_entries

Library
- library_books
- library_loans

Dorms
- dormitories
- dorm_assignments

Settings
- settings

## Implementation Order
1. Auth + roles + base layout
2. Reference data + classes/sections/subjects
3. Students + enrollments + promotions
4. Exams + grades + marks + results
5. Payments + receipts
6. Timetables
7. Library
8. Dorms
9. Settings

## UI Design Direction
- Typography: high-contrast serif for headings, geometric sans for body
- Palette: deep slate + warm sand + vivid teal accents
- Layout: dashboard cards with strong information density and clear hierarchy
- Motion: page-load fade + staggered list reveal
- Components: data tables with sticky headers, compact filters, summary chips
- UI kit: PrimeVue components with Tailwind for layout and spacing

## Parity Checklist
- User management
- Roles/permissions
- Student CRUD
- Promotions
- Class/section/subject CRUD
- Exams + grades + marks
- Results views
- Payments + receipts
- Timetables
- Library + loans
- Dorms
- Settings
