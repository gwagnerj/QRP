
 /* Create a one to many data data base first we wont set the structure in sql just */

/*  Model
 CREATE TABLE SubjectCourseRel (
	subject_id int,
	course_id int,
	CONSTRAINT FOREIGN KEY (subject_id) REFERENCES Subject (subject_id) 
		ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT FOREIGN KEY (course_id) REFERENCES Course (course_id) 
		ON DELETE CASCADE ON UPDATE CASCADE,	
	PRIMARY KEY (subject_id,course_id)
) ENGINE = InnoDB CHARACTER SET = utf8; */


 
 /* CREATE TABLE School (
    school_id int NOT NULL AUTO_INCREMENT, 
    PRIMARY KEY (school_id),
    s_name varchar(127),
   CONSTRAINT s_name UNIQUE,
   INDEX USING BTREE (s_name)
 ) ENGINE = INNODB CHARACTER SET = utf8; */
 
 
  /*insert data into the school table just to play*/
 
 /* INSERT INTO School (s_name) VALUES('Rice University');
 INSERT INTO School (s_name) VALUES('University of Colorado');
 INSERT INTO School (s_name) VALUES('University of Houston');
 INSERT INTO School (s_name) VALUES('UHCL');
INSERT INTO School (s_name) VALUES('Trine University'); */
 
 

 CREATE TABLE IF NOT EXISTS `Users` (
  `users_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
   `first` varchar(50) NOT NULL,
    `last` varchar(50) NOT NULL,
	`email` varchar(50) NOT NULL,
	`forgot_pswd` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
   `university` varchar(255) NOT NULL,
    `security` varchar(15) NOT NULL,
   diff_stu_1 int,
   diff_stu_2 int,
   diff_stu_3 int,
   diff_stu_4 int,
   diff_stu_5 int,
   diff_inst_1 int,
   diff_inst_2 int,
   diff_inst_3 int,
   diff_inst_4 int,
   diff_inst_5 int,
   eff_stu_1 int,
   eff_stu_2 int,
   eff_stu_3 int,
   eff_stu_4 int,
   eff_stu_5 int,
   eff_inst_1 int,
   eff_inst_2 int,
   eff_inst_3 int,
   eff_inst_4 int,
   eff_inst_5 int,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`users_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 

 
 CREATE TABLE Problem (
   problem_id int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (problem_id),
    primary_concept VARCHAR(128),
   secondary_concept VARCHAR(128),
    title VARCHAR(128),
   status VARCHAR(16),
   nm_author VARCHAR(128),
   game_prob_flag INT,
   docxfilenm VARCHAR(128),
   infilenm VARCHAR(128),
   pdffilenm VARCHAR(128),
   users_id int,
   units_a VARCHAR(32),
   units_b VARCHAR(32),
   units_c VARCHAR(32),
   units_d VARCHAR(32),
   units_e VARCHAR(32),
   units_f VARCHAR(32),
   units_g VARCHAR(32),
   units_h VARCHAR(32),
   units_i VARCHAR(32),
   units_j VARCHAR(32),
   tol_a int,
   tol_b int,
   tol_c int,
   tol_d int,
   tol_e int,
   tol_f int,
   tol_g int,
   tol_h int,
   tol_i int,
   tol_j int,
   hint_a VARCHAR(64),
   hint_b VARCHAR(64),
   hint_c VARCHAR(64),
   hint_d VARCHAR(64),
   hint_e VARCHAR(64),
   hint_f VARCHAR(64),
   hint_g VARCHAR(64),
   hint_h VARCHAR(64),
   hint_i VARCHAR(64),
   hint_j VARCHAR(64),
   hint_pblm VARCHAR(64),
   soln_pblm VARCHAR(128),
   subject VARCHAR(128),
   course VARCHAR(128),
   diff_contrib int,
   diff_stu_1 int,
   diff_stu_2 int,
   diff_stu_3 int,
   diff_stu_4 int,
   diff_stu_5 int,
   diff_inst_1 int,
   diff_inst_2 int,
   diff_inst_3 int,
   diff_inst_4 int,
   diff_inst_5 int,
   eff_stu_1 int,
   eff_stu_2 int,
   eff_stu_3 int,
   eff_stu_4 int,
   eff_stu_5 int,
   eff_inst_1 int,
   eff_inst_2 int,
   eff_inst_3 int,
   eff_inst_4 int,
   eff_inst_5 int,
    
   CONSTRAINT FOREIGN KEY (users_id) REFERENCES Users (users_id) 
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARACTER SET = utf8;


 CREATE TABLE Qa (
    qa_id INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (qa_id),
    problem_id int,
	dex int,
	ans_a	double,
	ans_b	double,
	ans_c	double,
	ans_d	double,
	ans_e	double,
	ans_f	double,
	ans_g	double,
	ans_h	double,
	ans_i	double,
	ans_j	double,
	g1	VARCHAR(64),
	g2	VARCHAR(64),
	g3	VARCHAR(64),
	CONSTRAINT FOREIGN KEY (Problem_ID) REFERENCES Problem (problem_id) 
		ON DELETE CASCADE ON UPDATE CASCADE
 ) ENGINE = INNODB CHARACTER SET = utf8;
 
 
 
 
 
 
 
 
 /* Query the database with the join and on clauses*/
 
 SELECT Users2.name,Users2.email,Users2.password,Users2.docxfilenm,School.s_name FROM Users2 JOIN School ON Users2.school_id=School.school_id;
 
 
 /* inserting data into multiple tables */
 
 
 
INSERT INTO School (s_name) VALUES('Texas Tech');

SELECT LAST_INSERT_ID();

Insert INTO School (s_name)

