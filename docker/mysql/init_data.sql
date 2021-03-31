create schema if not exists testapp collate utf8mb4_0900_ai_ci;

create table if not exists task
(
    guid        varchar(40)  not null primary key,
    title       varchar(256) not null,
    description text         not null,
    assigneeId  varchar(40)  not null,
    status      varchar(40)  not null,
    dueDate     date         not null
)
    collate = utf8_unicode_ci;

