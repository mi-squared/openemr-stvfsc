CREATE TABLE IF NOT EXISTS form_ros_exam_mi2 (
 forms_id        bigint(60)   NOT NULL,
 line_id         char(30)     NOT NULL,
 yes             tinyint(1)   NOT NULL DEFAULT 0,
 no              tinyint(1)   NOT NULL DEFAULT 0,
 comments        text         NOT NULL DEFAULT '',
 PRIMARY KEY (forms_id, line_id)
) ENGINE=MyISAM;