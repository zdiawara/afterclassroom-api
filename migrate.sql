Migration table created successfully.CreateUsersTable: create table `users` (
  `id` bigint unsigned not null auto_increment primary key,
  `firstname` varchar(50) not null,
  `lastname` varchar(50) not null,
  `username` varchar(50) not null,
  `email` varchar(100) not null,
  `email_verified_at` timestamp null,
  `password` varchar(255) not null,
  `userable_id` int unsigned not null,
  `userable_type` varchar(255) not null,
  `created_at` timestamp null,
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateUsersTable:
alter table
  `users`
add
  unique `users_username_unique`(`username`) CreateUsersTable:
alter table
  `users`
add
  unique `users_email_unique`(`email`) CreateFailedJobsTable: create table `failed_jobs` (
    `id` bigint unsigned not null auto_increment primary key,
    `connection` text not null,
    `queue` text not null,
    `payload` longtext not null,
    `exception` longtext not null,
    `failed_at` timestamp default CURRENT_TIMESTAMP not null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateReferentielsTable: create table `referentiels` (
    `id` bigint unsigned not null auto_increment primary key,
    `name` varchar(50) not null,
    `code` varchar(20) not null,
    `type` varchar(20) not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateReferentielsTable:
alter table
  `referentiels`
add
  unique `referentiels_code_type_unique`(`code`, `type`) CreateClassesTable: create table `classes` (
    `id` bigint unsigned not null auto_increment primary key,
    `name` varchar(50) not null,
    `abreviation` varchar(10) not null,
    `code` varchar(20) not null,
    `order` smallint not null,
    `level_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateClassesTable:
alter table
  `classes`
add
  constraint `classes_level_id_foreign` foreign key (`level_id`) references `referentiels` (`id`) CreateClassesTable:
alter table
  `classes`
add
  unique `classes_abreviation_unique`(`abreviation`) CreateClassesTable:
alter table
  `classes`
add
  unique `classes_code_unique`(`code`) CreateMatieresTable: create table `matieres` (
    `id` bigint unsigned not null auto_increment primary key,
    `name` varchar(50) not null,
    `abreviation` varchar(20) not null,
    `code` varchar(20) not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateMatieresTable:
alter table
  `matieres`
add
  unique `matieres_code_unique`(`code`) CreateSpecialitesTable: create table `specialites` (
    `id` bigint unsigned not null auto_increment primary key,
    `name` varchar(50) not null,
    `matiere_id` bigint unsigned not null,
    `level_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateSpecialitesTable:
alter table
  `specialites`
add
  constraint `specialites_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`) CreateSpecialitesTable:
alter table
  `specialites`
add
  constraint `specialites_level_id_foreign` foreign key (`level_id`) references `referentiels` (`id`) CreateTeachersTable: create table `teachers` (
    `id` bigint unsigned not null auto_increment primary key,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateAdminsTable: create table `admins` (
    `id` bigint unsigned not null auto_increment primary key,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateChaptersTable: create table `chapters` (
    `id` bigint unsigned not null auto_increment primary key,
    `title` varchar(100) not null,
    `resume` varchar(255) null,
    `active` tinyint(1) not null default '0',
    `teacher_id` bigint unsigned not null,
    `classe_id` bigint unsigned not null,
    `specialite_id` bigint unsigned null,
    `matiere_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateChaptersTable:
alter table
  `chapters`
add
  constraint `chapters_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`) CreateChaptersTable:
alter table
  `chapters`
add
  constraint `chapters_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`) CreateChaptersTable:
alter table
  `chapters`
add
  constraint `chapters_specialite_id_foreign` foreign key (`specialite_id`) references `specialites` (`id`) CreateChaptersTable:
alter table
  `chapters`
add
  constraint `chapters_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`) CreateTeacherMatieresTable: create table `matiere_teacher` (
    `matiere_id` bigint unsigned not null,
    `teacher_id` bigint unsigned not null,
    `etat_id` bigint unsigned not null,
    `justificatif` varchar(255) not null,
    `deleted_at` timestamp null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateTeacherMatieresTable:
alter table
  `matiere_teacher`
add
  constraint `matiere_teacher_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`) CreateTeacherMatieresTable:
alter table
  `matiere_teacher`
add
  constraint `matiere_teacher_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`) CreateTeacherMatieresTable:
alter table
  `matiere_teacher`
add
  constraint `matiere_teacher_etat_id_foreign` foreign key (`etat_id`) references `referentiels` (`id`) CreateOptionsTable: create table `options` (
    `id` bigint unsigned not null auto_increment primary key,
    `name` varchar(50) not null,
    `classe_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateOptionsTable:
alter table
  `options`
add
  constraint `options_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`) CreateEnseignementablesTable: create table `enseignementables` (
    `enseignementable_id` bigint unsigned not null,
    `option_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null,
    `enseignementable_type` varchar(255) not null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateEnseignementablesTable:
alter table
  `enseignementables`
add
  constraint `enseignementables_option_id_foreign` foreign key (`option_id`) references `options` (`id`) CreateContentsTable: create table `contents` (
    `id` bigint unsigned not null auto_increment primary key,
    `active` tinyint(1) not null,
    `data` longtext not null,
    `contentable_id` bigint unsigned not null,
    `contentable_type` varchar(255) not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateExercisesTable: create table `exercises` (
    `id` bigint unsigned not null auto_increment primary key,
    `active` tinyint(1) not null,
    `type_id` bigint unsigned not null,
    `exercisable_id` bigint unsigned not null,
    `exercisable_type` varchar(255) not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateExercisesTable:
alter table
  `exercises`
add
  constraint `exercises_type_id_foreign` foreign key (`type_id`) references `referentiels` (`id`) CreateSolutionsTable: create table `solutions` (
    `id` bigint unsigned not null auto_increment primary key,
    `exercise_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateSolutionsTable:
alter table
  `solutions`
add
  constraint `solutions_exercise_id_foreign` foreign key (`exercise_id`) references `exercises` (`id`) CreateNotionsTable: create table `notions` (
    `id` bigint unsigned not null auto_increment primary key,
    `title` varchar(100) not null,
    `teacher_id` bigint unsigned not null,
    `matiere_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateNotionsTable:
alter table
  `notions`
add
  constraint `notions_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`) CreateNotionsTable:
alter table
  `notions`
add
  constraint `notions_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`) CreateExerciseNotionsTable: create table `exercise_notion` (
    `exercise_id` bigint unsigned not null,
    `notion_id` bigint unsigned not null,
    `type` tinyint(1) not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateExerciseNotionsTable:
alter table
  `exercise_notion`
add
  constraint `exercise_notion_exercise_id_foreign` foreign key (`exercise_id`) references `exercises` (`id`) CreateExerciseNotionsTable:
alter table
  `exercise_notion`
add
  constraint `exercise_notion_notion_id_foreign` foreign key (`notion_id`) references `notions` (`id`) CreateControlesTable: create table `controles` (
    `id` bigint unsigned not null auto_increment primary key,
    `active` tinyint(1) not null default '0',
    `year` year not null,
    `type_id` bigint unsigned not null,
    `teacher_id` bigint unsigned not null,
    `classe_id` bigint unsigned not null,
    `matiere_id` bigint unsigned not null,
    `option_id` bigint unsigned null,
    `specialite_id` bigint unsigned null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateControlesTable:
alter table
  `controles`
add
  constraint `controles_type_id_foreign` foreign key (`type_id`) references `referentiels` (`id`) CreateControlesTable:
alter table
  `controles`
add
  constraint `controles_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`) CreateControlesTable:
alter table
  `controles`
add
  constraint `controles_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`) CreateControlesTable:
alter table
  `controles`
add
  constraint `controles_option_id_foreign` foreign key (`option_id`) references `options` (`id`) CreateControlesTable:
alter table
  `controles`
add
  constraint `controles_specialite_id_foreign` foreign key (`specialite_id`) references `specialites` (`id`) CreateControlesTable:
alter table
  `controles`
add
  constraint `controles_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`) CreateBooksTable: create table `books` (
    `id` bigint unsigned not null auto_increment primary key,
    `title` varchar(100) not null,
    `resume` longtext null,
    `active` tinyint(1) not null,
    `price` int null,
    `cover` varchar(255) null,
    `teacher_id` bigint unsigned not null,
    `specialite_id` bigint unsigned null,
    `matiere_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateBooksTable:
alter table
  `books`
add
  constraint `books_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`) CreateBooksTable:
alter table
  `books`
add
  constraint `books_specialite_id_foreign` foreign key (`specialite_id`) references `specialites` (`id`) CreateBooksTable:
alter table
  `books`
add
  constraint `books_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`) CreateBookClassesTable: create table `book_classe` (
    `id` bigint unsigned not null auto_increment primary key,
    `book_id` bigint unsigned not null,
    `classe_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateBookClassesTable:
alter table
  `book_classe`
add
  constraint `book_classe_book_id_foreign` foreign key (`book_id`) references `books` (`id`) CreateBookClassesTable:
alter table
  `book_classe`
add
  constraint `book_classe_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`) CreateBookClasseOptionsTable: create table `book_classe_option` (
    `book_classe_id` bigint unsigned not null,
    `option_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateBookClasseOptionsTable:
alter table
  `book_classe_option`
add
  constraint `book_classe_option_book_classe_id_foreign` foreign key (`book_classe_id`) references `book_classe` (`id`) CreateBookClasseOptionsTable:
alter table
  `book_classe_option`
add
  constraint `book_classe_option_option_id_foreign` foreign key (`option_id`) references `options` (`id`) CreateCategoriesTable: create table `categories` (
    `id` bigint unsigned not null auto_increment primary key,
    `name` varchar(50) not null,
    `code` varchar(20) not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateCategoriesTable:
alter table
  `categories`
add
  unique `categories_code_unique`(`code`) CreateBookCategoriesTable: create table `book_category` (
    `book_id` bigint unsigned not null,
    `category_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateBookCategoriesTable:
alter table
  `book_category`
add
  constraint `book_category_book_id_foreign` foreign key (`book_id`) references `books` (`id`) CreateBookCategoriesTable:
alter table
  `book_category`
add
  constraint `book_category_category_id_foreign` foreign key (`category_id`) references `categories` (`id`) CreateCollegeYearsTable: create table `college_years` (
    `id` bigint unsigned not null auto_increment primary key,
    `name` varchar(50) not null,
    `year` year not null,
    `etat_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateCollegeYearsTable:
alter table
  `college_years`
add
  constraint `college_years_etat_id_foreign` foreign key (`etat_id`) references `referentiels` (`id`) CreateCollegeYearsTable:
alter table
  `college_years`
add
  unique `college_years_year_unique`(`year`) CreateStudentsTable: create table `students` (
    `id` bigint unsigned not null auto_increment primary key,
    `classe_id` bigint unsigned not null,
    `option_id` bigint unsigned null,
    `created_at` timestamp null,
    `updated_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateStudentsTable:
alter table
  `students`
add
  constraint `students_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`) CreateStudentsTable:
alter table
  `students`
add
  constraint `students_option_id_foreign` foreign key (`option_id`) references `options` (`id`) CreateStudentTeachersTable: create table `student_teacher` (
    `id` bigint unsigned not null auto_increment primary key,
    `student_id` bigint unsigned not null,
    `teacher_id` bigint unsigned not null,
    `classe_id` bigint unsigned not null,
    `matiere_id` bigint unsigned not null,
    `college_year_id` bigint unsigned not null,
    `created_at` timestamp null,
    `updated_at` timestamp null,
    `deleted_at` timestamp null
  ) default character set utf8mb4 collate 'utf8mb4_unicode_ci' CreateStudentTeachersTable:
alter table
  `student_teacher`
add
  constraint `student_teacher_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`) CreateStudentTeachersTable:
alter table
  `student_teacher`
add
  constraint `student_teacher_student_id_foreign` foreign key (`student_id`) references `students` (`id`) CreateStudentTeachersTable:
alter table
  `student_teacher`
add
  constraint `student_teacher_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`) CreateStudentTeachersTable:
alter table
  `student_teacher`
add
  constraint `student_teacher_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`) CreateStudentTeachersTable:
alter table
  `student_teacher`
add
  constraint `student_teacher_college_year_id_foreign` foreign key (`college_year_id`) references `college_years` (`id`)