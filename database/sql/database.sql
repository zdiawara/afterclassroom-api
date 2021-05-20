-- convert Laravel migrations to raw SQL scripts --

-- migration:2019_08_19_000000_create_failed_jobs_table --
create table `failed_jobs` (
  `id` bigint unsigned not null auto_increment primary key, 
  `connection` text not null, `queue` text not null, 
  `payload` longtext not null, `exception` longtext not null, 
  `failed_at` timestamp default CURRENT_TIMESTAMP not null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

-- migration:2020_03_07_165514_create_referentiels_table --
create table `referentiels` (
  `id` bigint unsigned not null auto_increment primary key, 
  `name` varchar(50) not null, 
  `code` varchar(20) not null, 
  `type` varchar(20) not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `referentiels` 
add 
  unique `referentiels_code_type_unique`(`code`, `type`);

-- migration:2020_03_07_165722_create_classes_table --
create table `classes` (
  `id` bigint unsigned not null auto_increment primary key, 
  `name` varchar(50) not null, 
  `abreviation` varchar(10) not null, 
  `code` varchar(20) not null, 
  `order` smallint not null, 
  `level_id` bigint unsigned not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `classes` 
add 
  constraint `classes_level_id_foreign` foreign key (`level_id`) references `referentiels` (`id`);
alter table 
  `classes` 
add 
  unique `classes_abreviation_unique`(`abreviation`);
alter table 
  `classes` 
add 
  unique `classes_code_unique`(`code`);

-- migration:2020_03_07_165838_create_matieres_table --
create table `matieres` (
  `id` bigint unsigned not null auto_increment primary key, 
  `name` varchar(50) not null, 
  `abreviation` varchar(20) not null, 
  `code` varchar(20) not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `matieres` 
add 
  unique `matieres_code_unique`(`code`);

-- migration:2020_03_07_170000_create_users_table --
create table `users` (
  `id` bigint unsigned not null auto_increment primary key, 
  `firstname` varchar(50) not null, 
  `lastname` varchar(50) not null, 
  `username` varchar(50) not null, 
  `gender_id` bigint unsigned not null, 
  `email` varchar(100) not null, 
  `email_verified_at` timestamp null, 
  `password` varchar(255) not null, 
  `avatar` varchar(255) not null, 
  `userable_id` int unsigned not null, 
  `userable_type` varchar(255) not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `users` 
add 
  constraint `users_gender_id_foreign` foreign key (`gender_id`) references `referentiels` (`id`);
alter table 
  `users` 
add 
  unique `users_username_unique`(`username`);
alter table 
  `users` 
add 
  unique `users_email_unique`(`email`);

-- migration:2020_03_07_170148_create_specialites_table --
create table `specialites` (
  `id` bigint unsigned not null auto_increment primary key, 
  `name` varchar(50) not null, 
  `code` varchar(20) not null, 
  `matiere_id` bigint unsigned not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `specialites` 
add 
  constraint `specialites_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`);

-- migration:2020_03_07_173723_create_subjects_table --
create table `subjects` (
  `id` bigint unsigned not null auto_increment primary key, 
  `name` varchar(50) not null, 
  `code` varchar(20) not null, 
  `classe_id` bigint unsigned null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `subjects` 
add 
  constraint `subjects_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`);
alter table 
  `subjects` 
add 
  unique `subjects_code_unique`(`code`);

-- migration:2020_03_08_153937_create_teachers_table --
create table `teachers` (
  `id` bigint unsigned not null auto_increment primary key, 
  `level_id` bigint unsigned not null, 
  `created_at` timestamp null, `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `teachers` 
add 
  constraint `teachers_level_id_foreign` foreign key (`level_id`) references `referentiels` (`id`);

-- migration:2020_03_08_172903_create_admins_table --
create table `admins` (
  `id` bigint unsigned not null auto_increment primary key, 
  `created_at` timestamp null, `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

-- migration:2020_03_08_192404_create_chapters_table --
create table `chapters` (
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
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `chapters` 
add 
  constraint `chapters_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`);
alter table 
  `chapters` 
add 
  constraint `chapters_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`);
alter table 
  `chapters` 
add 
  constraint `chapters_specialite_id_foreign` foreign key (`specialite_id`) references `specialites` (`id`);
alter table 
  `chapters` 
add 
  constraint `chapters_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`);

-- migration:2020_03_10_131622_create_matiere_teachers_table --
create table `matiere_teacher` (
  `matiere_id` bigint unsigned not null, 
  `teacher_id` bigint unsigned not null, 
  `etat_id` bigint unsigned not null, 
  `deleted_at` timestamp null, `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `matiere_teacher` 
add 
  constraint `matiere_teacher_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`);
alter table 
  `matiere_teacher` 
add 
  constraint `matiere_teacher_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`);
alter table 
  `matiere_teacher` 
add 
  constraint `matiere_teacher_etat_id_foreign` foreign key (`etat_id`) references `referentiels` (`id`);

-- migration:2020_03_18_080434_create_options_table --
create table `options` (
  `id` bigint unsigned not null auto_increment primary key, 
  `name` varchar(50) not null, 
  `classe_id` bigint unsigned not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `options` 
add 
  constraint `options_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`);

-- migration:2020_03_18_200355_create_enseignementables_table --
create table `enseignementables` (
  `enseignementable_id` bigint unsigned not null, 
  `option_id` bigint unsigned not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null, 
  `enseignementable_type` varchar(255) not null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `enseignementables` 
add 
  constraint `enseignementables_option_id_foreign` foreign key (`option_id`) references `options` (`id`);

-- migration:2020_03_19_090033_create_contents_table --
create table `contents` (
  `id` bigint unsigned not null auto_increment primary key, 
  `active` tinyint(1) not null, 
  `data` longtext not null, 
  `contentable_id` bigint unsigned not null, 
  `contentable_type` varchar(255) not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

-- migration:2020_03_20_071851_create_exercises_table --
create table `exercises` (
  `id` bigint unsigned not null auto_increment primary key, 
  `active` tinyint(1) not null, 
  `notion` varchar(255) null, 
  `prerequis` varchar(255) null, 
  `type_id` bigint unsigned not null, 
  `exercisable_id` bigint unsigned not null, 
  `exercisable_type` varchar(255) not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `exercises` 
add 
  constraint `exercises_type_id_foreign` foreign key (`type_id`) references `referentiels` (`id`);

-- migration:2020_03_20_154323_create_solutions_table --
create table `solutions` (
  `id` bigint unsigned not null auto_increment primary key, 
  `exercise_id` bigint unsigned null, 
  `controle_id` bigint unsigned null, 
  `created_at` timestamp null, `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `solutions` 
add 
  constraint `solutions_exercise_id_foreign` foreign key (`exercise_id`) references `exercises` (`id`);

-- migration:2020_03_23_120157_create_notions_table --
create table `notions` (
  `id` bigint unsigned not null auto_increment primary key, 
  `title` varchar(100) not null, 
  `teacher_id` bigint unsigned not null, 
  `matiere_id` bigint unsigned not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `notions` 
add 
  constraint `notions_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`);
alter table 
  `notions` 
add 
  constraint `notions_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`);

-- migration:2020_03_23_120426_create_exercise_notions_table --
create table `exercise_notion` (
  `exercise_id` bigint unsigned not null, 
  `notion_id` bigint unsigned not null, 
  `type` tinyint(1) not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `exercise_notion` 
add 
  constraint `exercise_notion_exercise_id_foreign` foreign key (`exercise_id`) references `exercises` (`id`);
alter table 
  `exercise_notion` 
add 
  constraint `exercise_notion_notion_id_foreign` foreign key (`notion_id`) references `notions` (`id`);

-- migration:2020_03_30_195842_create_controles_table --
create table `controles` (
  `id` bigint unsigned not null auto_increment primary key, 
  `active` tinyint(1) not null default '0', 
  `year` year not null, 
  `teacher_id` bigint unsigned not null, 
  `classe_id` bigint unsigned not null, 
  `matiere_id` bigint unsigned not null, 
  `subject_id` bigint unsigned null, 
  `type_id` bigint unsigned not null, 
  `option_id` bigint unsigned null, 
  `specialite_id` bigint unsigned null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `controles` 
add 
  constraint `controles_type_id_foreign` foreign key (`type_id`) references `referentiels` (`id`);
alter table 
  `controles` 
add 
  constraint `controles_subject_id_foreign` foreign key (`subject_id`) references `subjects` (`id`);
alter table 
  `controles` 
add 
  constraint `controles_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`);
alter table 
  `controles` 
add 
  constraint `controles_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`);
alter table 
  `controles` 
add 
  constraint `controles_option_id_foreign` foreign key (`option_id`) references `options` (`id`);
alter table 
  `controles` 
add 
  constraint `controles_specialite_id_foreign` foreign key (`specialite_id`) references `specialites` (`id`);
alter table 
  `controles` 
add 
  constraint `controles_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`);

-- migration:2020_04_01_105540_create_books_table --
create table `books` (
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
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `books` 
add 
  constraint `books_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`);
alter table 
  `books` 
add 
  constraint `books_specialite_id_foreign` foreign key (`specialite_id`) references `specialites` (`id`);
alter table 
  `books` 
add 
  constraint `books_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`);

-- migration:2020_04_01_165737_create_book_classes_table --
create table `book_classe` (
  `id` bigint unsigned not null auto_increment primary key, 
  `book_id` bigint unsigned not null, 
  `classe_id` bigint unsigned not null, 
  `created_at` timestamp null, `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `book_classe` 
add 
  constraint `book_classe_book_id_foreign` foreign key (`book_id`) references `books` (`id`);
alter table 
  `book_classe` 
add 
  constraint `book_classe_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`);

-- migration:2020_04_01_182834_create_book_classe_options_table --
create table `book_classe_option` (
  `book_classe_id` bigint unsigned not null, 
  `option_id` bigint unsigned not null, 
  `created_at` timestamp null, `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `book_classe_option` 
add 
  constraint `book_classe_option_book_classe_id_foreign` foreign key (`book_classe_id`) references `book_classe` (`id`);
alter table 
  `book_classe_option` 
add 
  constraint `book_classe_option_option_id_foreign` foreign key (`option_id`) references `options` (`id`);

-- migration:2020_04_01_225000_create_categories_table --
create table `categories` (
  `id` bigint unsigned not null auto_increment primary key, 
  `name` varchar(50) not null, 
  `code` varchar(20) not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `categories` 
add 
  unique `categories_code_unique`(`code`);

-- migration:2020_04_01_230126_create_book_categories_table --
create table `book_category` (
  `book_id` bigint unsigned not null, 
  `category_id` bigint unsigned not null, 
  `created_at` timestamp null, `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `book_category` 
add 
  constraint `book_category_book_id_foreign` foreign key (`book_id`) references `books` (`id`);
alter table 
  `book_category` 
add 
  constraint `book_category_category_id_foreign` foreign key (`category_id`) references `categories` (`id`);

-- migration:2020_04_03_084801_create_college_years_table --
create table `college_years` (
  `id` bigint unsigned not null auto_increment primary key, 
  `name` varchar(50) not null, 
  `year` year not null, 
  `etat_id` bigint unsigned not null, 
  `created_at` timestamp null, 
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `college_years` 
add 
  constraint `college_years_etat_id_foreign` foreign key (`etat_id`) references `referentiels` (`id`);
alter table 
  `college_years` 
add 
  unique `college_years_year_unique`(`year`);

-- migration:2020_04_03_223824_create_students_table --
create table `students` (
  `id` bigint unsigned not null auto_increment primary key, 
  `classe_id` bigint unsigned not null, 
  `option_id` bigint unsigned null, 
  `created_at` timestamp null, `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `students` 
add 
  constraint `students_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`);
alter table 
  `students` 
add 
  constraint `students_option_id_foreign` foreign key (`option_id`) references `options` (`id`);

-- migration:2020_04_04_072641_create_student_teachers_table --
create table `student_teacher` (
  `id` bigint unsigned not null auto_increment primary key, 
  `student_id` bigint unsigned not null, 
  `teacher_id` bigint unsigned not null, 
  `classe_id` bigint unsigned not null, 
  `matiere_id` bigint unsigned not null, 
  `college_year_id` bigint unsigned not null, 
  `created_at` timestamp null, `updated_at` timestamp null, 
  `deleted_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table 
  `student_teacher` 
add 
  constraint `student_teacher_teacher_id_foreign` foreign key (`teacher_id`) references `teachers` (`id`);
alter table 
  `student_teacher` 
add 
  constraint `student_teacher_student_id_foreign` foreign key (`student_id`) references `students` (`id`);
alter table 
  `student_teacher` 
add 
  constraint `student_teacher_classe_id_foreign` foreign key (`classe_id`) references `classes` (`id`);
alter table 
  `student_teacher` 
add 
  constraint `student_teacher_matiere_id_foreign` foreign key (`matiere_id`) references `matieres` (`id`);
alter table 
  `student_teacher` 
add 
  constraint `student_teacher_college_year_id_foreign` foreign key (`college_year_id`) references `college_years` (`id`);

-- migration:2020_04_20_153017_create_identifies_table --
create table `identifies` (
  `id` bigint unsigned not null auto_increment primary key, 
  `tranche` int not null, `current` int not null, 
  `created_at` timestamp null, `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
