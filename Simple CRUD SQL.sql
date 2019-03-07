
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
   soln_book VARCHAR(128),
   subject VARCHAR(128),
   course VARCHAR(128),
   time_est_contrib int,
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
   link_to_web_full VARCHAR(128),
    specif_ref VARCHAR(128),
   nv_1 VARCHAR(32),
   nv_2 VARCHAR(32),
   nv_3 VARCHAR(32),
   nv_4 VARCHAR(32),
   nv_5 VARCHAR(32),
   nv_6 VARCHAR(32),
   nv_7 VARCHAR(32),
   nv_8 VARCHAR(32),
   nv_9 VARCHAR(32),
   nv_10 VARCHAR(32),
   nv_11 VARCHAR(32),
   nv_12 VARCHAR(32),
   nv_13 VARCHAR(32),
   nv_14 VARCHAR(32),
   htmlfilenm VARCHAR(128),
   tertiary_concept VARCHAR(128),
 
   CONSTRAINT FOREIGN KEY (users_id) REFERENCES Users (users_id) 
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARACTER SET = utf8;



ALTER TABLE Problem 
	ADD preprob_3 VARCHAR(127),
	ADD preprob_4 VARCHAR(127),
	ADD postprob_1 VARCHAR(127),
	ADD postprob_2 VARCHAR(127),
	ADD postprob_3 VARCHAR(127)
  ;





ALTER TABLE Problem 
  ADD not_perfect_1 int,
  ADD not_perfect_2 int,
  ADD not_perfect_3 int,
  ADD not_perfect_4 int,
  ADD not_perfect_5 int,
  ADD not_perfect_6 int,
  ADD not_perfect_7 int,
  ADD not_perfect_8 int,
  ADD not_perfect_9 int,
  
  ADD t_take1_1 int,
  ADD t_take1_2 int,
  ADD t_take1_3 int,
  ADD t_take1_4 int,
  ADD t_take1_5 int,
  ADD t_take1_6 int,
  ADD t_take1_7 int,
  
  
  ADD t_take1_np_1 int,
  ADD t_take1_np_2 int,
  ADD t_take1_np_3 int,
  ADD t_take1_np_4 int,
  ADD t_take1_np_5 int,
  ADD t_take1_np_6 int,
  ADD t_take1_np_7 int,
 
  
  ADD t_take2_1 int,
  ADD t_take2_2 int,
  ADD t_take2_3 int,
  ADD t_take2_4 int,
  ADD t_take2_5 int,
  ADD t_take2_6 int,
  ADD t_take2_7 int,
  
  
  ADD t_b4due_1 int,
  ADD t_b4due_2 int,
  ADD t_b4due_3 int,
  ADD t_b4due_4 int,
  ADD t_b4due_5 int,
  ADD t_b4due_6 int,
  ADD t_b4due_7 int,
  
  
  ADD t_b4due_np_1 int,
  ADD t_b4due_np_2 int,
  ADD t_b4due_np_3 int,
  ADD t_b4due_np_4 int,
  ADD t_b4due_np_5 int,
  ADD t_b4due_np_6 int,
  ADD t_b4due_np_7 int,
 
  
  ADD confidence_1 int,
  ADD confidence_2 int,
  ADD confidence_3 int,
  ADD confidence_4 int,
  ADD confidence_5 int,
  
  
  ADD confidence_np_1 int,
  ADD confidence_np_2 int,
  ADD confidence_np_3 int,
  ADD confidence_np_4 int,
  ADD confidence_np_5 int,
  
  ADD too_long_1 int,
  ADD too_long_2 int,
  ADD too_long_3 int,
  ADD too_long_4 int,
  ADD too_long_5 int,
  ADD too_long_6 int,
  ADD too_long_7 int,
  ADD too_long_8 int,
  ADD too_long_9 int,
  
  ADD prob_comments text,
  ADD sug_hints text,
  ADD qr_comments text
  ;


CREATE TABLE IF NOT EXISTS Activity (
  activity_id int(11) NOT NULL AUTO_INCREMENT,
	problem_id int,
	pin int,
   iid int, 
	 dex int,
	 stu_name varchar (120),
	assign_id int,
  instr_last varchar(50),
   university varchar(255),
   pp1 int,
   pp2 int,
   pp3 int,
   pp4 int,
   time_created timestamp DEFAULT CURRENT_TIMESTAMP,
   time_pp1 timestamp,
   time_pp2 timestamp,
   time_pp3 timestamp,
   time_pp4 timestamp,
   guess_a double,
    guess_b double,
	guess_c double,
    guess_d double,
	 guess_e double,
    guess_f double,
	 guess_g double,
	guess_h double,
    guess_i double,
	 guess_j double,
	 time_est int,
	 t_b4due int,
	 confidence int,
   score int,
	post_pblm1 int,
	post_pblm2 int,
	post_pblm3 int,
 time_post_pblm1 timestamp,
  time_post_pblm2 timestamp,
  time_post_pblm3 timestamp,
   help_coins_used int,
   assist_coins_gained int,
PRIMARY KEY (`activity_id`))
ENGINE=InnoDB CHARACTER SET = utf8;


ALTER TABLE Activity
  ADD guess_g double;



CREATE TABLE IF NOT EXISTS `Coinbank` (
 'coinbank_id' int(11) NOT NULL AUTO_INCREMENT,
 `student_id` int,
  `instr_last` varchar(50) NOT NULL,
   `iid` varchar(16) NOT NULL,
   `university` varchar(255) NOT NULL,
   help_coins_bal int,
   assist_coins_bal int,
  PRIMARY KEY (`student_id`)
  );









CREATE TABLE IF NOT EXISTS `Assign` (
  `assign_id` int(11) NOT NULL AUTO_INCREMENT,
  `instr_last` varchar(50) NOT NULL,
   `iid` varchar(16) NOT NULL,
   `university` varchar(255) NOT NULL,
   assign_t_created time,
    assign_num int,
   prob_num int,
   pp_flag1 int,
   pp_flag2 int,
    pp_flag3 int,
    pp_flag4 int,
	reflect_flag int,
	explore_flag int,
	connect_flag int,
	society_flag int,
	 postp_flag1 int,
	 postp_flag2 int,
	 postp_flag3 int,
  PRIMARY KEY (`assign_id`)
  );






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
	g1	VARCHAR(512),
	g2	VARCHAR(512),
	g3	VARCHAR(512),
	CONSTRAINT FOREIGN KEY (Problem_ID) REFERENCES Problem (problem_id) 
		ON DELETE CASCADE ON UPDATE CASCADE
 ) ENGINE = INNODB CHARACTER SET = utf8;
 
 CREATE TABLE Input (
    input_id INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (input_id),
    problem_id int,
	dex int,
	v_1	VARCHAR(512),
	v_2	VARCHAR(512),
	v_3	VARCHAR(512),
	v_4	VARCHAR(512),
	v_5	VARCHAR(512),
	v_6	VARCHAR(512),
	v_7	VARCHAR(512),
	v_8	VARCHAR(512),
	v_9	VARCHAR(512),
	v_10	VARCHAR(512),
	v_11	VARCHAR(512),
	v_12	VARCHAR(512),
	v_13	VARCHAR(512),
	v_14	VARCHAR(512),
	
	CONSTRAINT FOREIGN KEY (Problem_ID) REFERENCES Problem (problem_id) 
		ON DELETE CASCADE ON UPDATE CASCADE
 ) ENGINE = INNODB CHARACTER SET = utf8;
 
 
 
 ALTER TABLE `problem` ADD `nv_1` VARCHAR(32) NOT NULL AFTER `specif_ref`, ADD `nv_2` VARCHAR(32) NOT NULL AFTER `nv_1`, ADD `nv_3` VARCHAR(32) NOT NULL AFTER `nv_2`, ADD `nv_4` VARCHAR(32) NOT NULL AFTER `nv_3`, ADD `nv_5` VARCHAR(32) NOT NULL AFTER `nv_4`, ADD `nv_6` VARCHAR(32) NOT NULL AFTER `nv_5`, ADD `nv_7` VARCHAR(32) NOT NULL AFTER `nv_6`, ADD `nv_8` VARCHAR(32) NOT NULL AFTER `nv_7`, ADD `nv_9` VARCHAR(32) NOT NULL AFTER `nv_8`, ADD `nv_10` VARCHAR(32) NOT NULL AFTER `nv_9`, ADD `nv_11` VARCHAR(32) NOT NULL AFTER `nv_10`, ADD `nv_12` VARCHAR(32) NOT NULL AFTER `nv_11`, ADD `nv_13` VARCHAR(32) NOT NULL AFTER `nv_12`, ADD `nv_14` VARCHAR(32) NOT NULL AFTER `nv_13`;
 
 
 
 /* Query the database with the join and on clauses*/
 
 SELECT Users2.name,Users2.email,Users2.password,Users2.docxfilenm,School.s_name FROM Users2 JOIN School ON Users2.school_id=School.school_id;
 
 
 /* inserting data into multiple tables */
 
 
 
INSERT INTO School (s_name) VALUES('Texas Tech');

SELECT LAST_INSERT_ID();

Insert INTO School (s_name)

