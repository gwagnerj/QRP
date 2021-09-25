
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
	ADD video_clip int,
	ADD simulation int,
	ADD demonstration_directions VARCHAR(127),
	ADD activity_directions VARCHAR(127)
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

ALTER TABLE Problem 
	ADD computation_name VARCHAR(64)
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

ALTER TABLE Activity	
	ADD rtn_code varchar(16),
	ADD est_what_concepts varchar(500),
	ADD est_most_diff varchar(500),
	ADD est_do_first varchar(500),
	ADD est_how_long int,
	ADD est_start int,
	ADD est_conf int
	;
	
ALTER TABLE Activity
  ADD time_complete timestamp;


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
   `iid` int(11) NOT NULL,
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


ALTER TABLE Assign
  ADD ref_choice int;



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


CREATE TABLE IF NOT EXISTS `Concept` (
  `concept_id` int(11) NOT NULL AUTO_INCREMENT,
  `concept_name` varchar(50) NOT NULL,
  `synonym1` varchar(50),
  `synonym2` varchar(50),
  `synonym3` varchar(50),
  `synonym4` varchar(50),
  `synonym5` varchar(50),
  `synonym6` varchar(50),
  `synonym7` varchar(50),
  PRIMARY KEY (`concept_id`),
  UNIQUE KEY unique_concept (`concept_name`)
  );

CREATE TABLE IF NOT EXISTS `Course` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(50) NOT NULL,
  `synonym1` varchar(50),
  `synonym2` varchar(50),
  `synonym3` varchar(50),
  `synonym4` varchar(50),
  `synonym5` varchar(50),
  `synonym6` varchar(50),
  `synonym7` varchar(50),
  PRIMARY KEY (`course_id`),
  INDEX (`course_name`),
  UNIQUE KEY unique_course (`course_name`)
  );


CREATE TABLE CourseConceptConnect (
	course_id INTEGER,
	concept_id INTEGER,
	CONSTRAINT FOREIGN KEY (course_id) REFERENCES Course (course_id),
	CONSTRAINT FOREIGN KEY (concept_id) REFERENCES Concept (concept_id),
	PRIMARY KEY (`course_id`,`concept_id`)
) ENGINE=InnoDB CHARACTER SET = utf8;



CREATE TABLE IF NOT EXISTS `Discipline` (
  `discipline_id` int(11) NOT NULL AUTO_INCREMENT,
  `discipline_name` varchar(50) NOT NULL,
  `synonym1` varchar(50),
  `synonym2` varchar(50),
  `synonym3` varchar(50),
  `synonym4` varchar(50),
  `synonym5` varchar(50),
  `synonym6` varchar(50),
  `synonym7` varchar(50),
  PRIMARY KEY (`discipline_id`),
  INDEX (`discipline_name`),
  UNIQUE KEY unique_discipline (`discipline_name`)
  );


CREATE TABLE DisciplineCourseConnect (
	`discipline_id` INTEGER,
	`course_id` INTEGER,
	CONSTRAINT FOREIGN KEY (`discipline_id`) REFERENCES Discipline (`discipline_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT FOREIGN KEY (`course_id`) REFERENCES Course (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (`discipline_id`,`course_id`)
) ENGINE=InnoDB CHARACTER SET = utf8;


INSERT INTO `Course` (`course_name`, `synonym1`,`synonym2`) VALUES ('Fluid Mechanics','Fluid Dynamics','Fluids');
INSERT INTO `Course` (`course_name`, `synonym1`) VALUES ('Mass Balances','Material Balances');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Energy Balances');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Thermodynamics');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Statistics');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Heat Transfer');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Mass Transfer');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Materials');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Stagewise Separations');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Engineering Economics');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Reactor Design');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Statics');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Dynamics');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Circuits');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Solid Mechanics');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Dynamics & Control');
INSERT IGNORE INTO `Course` (`course_name`) VALUES ('Particle Technology');



INSERT INTO `Discipline` (`discipline_name`) VALUES ('Chemical Engineering');
INSERT INTO `Discipline` (`discipline_name`) VALUES ('Mechanical Engineering');
INSERT INTO `Discipline` (`discipline_name`) VALUES ('Chemistry');
INSERT INTO `Discipline` (`discipline_name`) VALUES ('Civil Engineering');
INSERT INTO `Discipline` (`discipline_name`) VALUES ('Electrical Engineering');
INSERT INTO `Discipline` (`discipline_name`) VALUES ('Biomedical Engineering');
INSERT INTO `Discipline` (`discipline_name`) VALUES ('Physics');

CREATE TABLE IF NOT EXISTS `Computation` (
  `computation_id` int(11) NOT NULL AUTO_INCREMENT,
  `computation_name` varchar(50) NOT NULL,
  `computation_order` int(11) NOT NULL,
  `synonym1` varchar(50),
  `synonym2` varchar(50),
  `synonym3` varchar(50),
  `synonym4` varchar(50),
  `synonym5` varchar(50),
  `synonym6` varchar(50),
  `synonym7` varchar(50),
  PRIMARY KEY (`computation_id`),
  INDEX (`computation_name`),
  UNIQUE KEY unique_computation (`computation_name`)
  );
  
  
  INSERT INTO `Computation` (`computation_order`,`computation_name`) VALUES (10,'Single Algebraic Analytic');
  INSERT INTO `Computation` (`computation_order`,`computation_name`) VALUES (20,'Multiple Algebraic Analytic');
  INSERT INTO `Computation` (`computation_order`,`computation_name`,`synonym1`,`synonym2`) VALUES (30,'Algebraic Iterative','Trial and Error','Root Solving');
  INSERT INTO `Computation` (`computation_order`,`computation_name`,`synonym1`,`synonym2`) VALUES (40,'Regression','Curve Fitting','Least Squares');
  INSERT INTO `Computation` (`computation_order`,`computation_name`,`synonym1`,`synonym2`,`synonym3`) VALUES (50,'Statistical Tests','Likelyhood','Probability','Stochastic');
 INSERT INTO `Computation` (`computation_order`,`computation_name`,`synonym1`,`synonym2`) VALUES (60,'Integration','Quadrature','Area Under the Curve');
  INSERT INTO `Computation` (`computation_order`,`computation_name`,`synonym1`,`synonym2`) VALUES (70,'Optimization','Minimum Maximum','Objective Function');
   INSERT INTO `Computation` (`computation_order`,`computation_name`) VALUES (80,'Single ODE IVP');
    INSERT INTO `Computation` (`computation_order`,`computation_name`) VALUES (90,'Multiple ODE IVP');
   INSERT INTO `Computation` (`computation_order`,`computation_name`) VALUES (100,'Ordinary Boundary Value Problems');
   INSERT INTO `Computation` (`computation_order`,`computation_name`) VALUES (110,'Partial Differential or Partial Integral');
   
    Mass Balance Concepts
	
	INSERT INTO `Concept` (`concept_name`) VALUES ('Unit Conversion');
    INSERT INTO `Concept` (`concept_name`,`synonym1`) VALUES ('Quantity Conversion','Mass Mole Volume Conversion');
    INSERT INTO `Concept` (`concept_name`) VALUES ('Concentration Conversion');
    INSERT INTO `Concept` (`concept_name`) VALUES ('Flow Rate');
	INSERT INTO `Concept` (`concept_name`) VALUES ('Fluid Velocity');
    INSERT INTO `Concept` (`concept_name`) VALUES ('Relative Absolute T or P');
	INSERT INTO `Concept` (`concept_name`) VALUES ('Data Visualiation');
    INSERT INTO `Concept` (`concept_name`) VALUES ('Ideal Gas Law');
	INSERT INTO `Concept` (`concept_name`) VALUES ('Manometry');
	INSERT INTO `Concept` (`concept_name`) VALUES ('Single Unit Nonreacting MB'); 
	INSERT INTO `Concept` (`concept_name`) VALUES ('Multiple Unit Nonreacting MB');  
	INSERT INTO `Concept` (`concept_name`,`synonym1`) VALUES ('Single Unit Reacting MB','Stoichiometry');   
	INSERT INTO `Concept` (`concept_name`) VALUES ('Multiple Unit Reacting MB');   
	INSERT INTO `Concept` (`concept_name`) VALUES ('Recycle or Bypass');    
	INSERT INTO `Concept` (`concept_name`) VALUES ('Equations of State');    
	INSERT INTO `Concept` (`concept_name`) VALUES ('Phase Equilibrium');     
	INSERT IGNORE INTO `Concept` (`concept_name`) VALUES ('Conservation of Mass');    
	INSERT IGNORE INTO `Concept` (`concept_name`) VALUES ('Conservation of Linear Momentum');   	

	INSERT INTO `Concept` (`concept_name`) VALUES ('Conduction in 1 Dimension');
    INSERT INTO `Concept` (`concept_name`) VALUES ('Conduction in 2 Dimensions');
    INSERT INTO `Concept` (`concept_name`) VALUES ('Transient Conduction');
    INSERT INTO `Concept` (`concept_name`) VALUES ('Convection - Internal Flows');
	INSERT INTO `Concept` (`concept_name`) VALUES ('Convection - External Flows');
    INSERT INTO `Concept` (`concept_name`) VALUES ('Free Convection');
	INSERT INTO `Concept` (`concept_name`) VALUES ('Boiling and Convection');
    INSERT INTO `Concept` (`concept_name`) VALUES ('Heat Exchangers');
	INSERT INTO `Concept` (`concept_name`) VALUES ('Radiation');
	
	INSERT INTO `Concept` (`concept_name`) VALUES ('Psychrometrics');
    INSERT INTO `Concept` (`concept_name`) VALUES ('E Balances on Reacting Systems');
    INSERT INTO `Concept` (`concept_name`) VALUES ('Transient Energy Balances');
  
  INSERT INTO `Concept` (`concept_name`) VALUES ('Properties of Pure Substances');
	 INSERT INTO `Concept` (`concept_name`) VALUES ('Closed System 1st law Analysis');
	 INSERT INTO `Concept` (`concept_name`) VALUES ('Open System 1st law Analysis');
	 INSERT INTO `Concept` (`concept_name`) VALUES ('Control Volumes');
	 INSERT INTO `Concept` (`concept_name`) VALUES ('Second Law');
	 INSERT INTO `Concept` (`concept_name`) VALUES ('Entropy');
	 INSERT INTO `Concept` (`concept_name`) VALUES ('Exergy');
	 INSERT INTO `Concept` (`concept_name`) VALUES ('Refrigeration and Heat Pumps');
	 INSERT INTO `Concept` (`concept_name`) VALUES ('Power Cycles');
	 INSERT INTO `Concept` (`concept_name`) VALUES ('Properties of Mixtures');
	 INSERT INTO `Concept` (`concept_name`) VALUES ('Thermodynamic Relationships');
	 


	
	
	
	INSERT IGNORE INTO `CourseConceptConnect` (`course_id`,`concept_id`) VALUES
	(1,2),
	(1,4),
	(1,5),
	(1,6),
	(1,7),
	(1,8),
	(1,9),
	(1,10),
	(1,11),
	(1,12),
	(1,13),
	(1,14),
	(1,15),
	(1,16),
	(1,17),
	(1,18),
	(1,19),
	(1,20),
	(1,21),
	(1,22),
	(1,23),
	(1,24),
	(2,25),
	(2,26),
	(2,27),
	(2,28),
	(2,29),
	(2,30),
	(2,31),
	(2,32),
	(2,33),
	(2,34),
	(2,35),
	(2,36),
	(2,37),
	(2,38),
	(2,39),
	(1,40),
	(1,41),
	;   
	
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('6', '42');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('6', '43');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('6', '44');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('6', '45');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('6', '46');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('6', '47');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('6', '48');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('6', '49');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('6', '50');
	
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('3', '51');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('3', '52');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('3', '53');
	
	
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('3', '55');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('3', '54');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('3', '55');
	
	
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '54');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '55');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '56');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '57');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '58');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '59');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '60');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '61');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '62');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '63');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '64');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '9');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '38');
	INSERT INTO `CourseConceptConnect` (`course_id`, `concept_id`) VALUES ('4', '39');
	
	
	
	
	INSERT INTO `courseconceptconnect` (`course_id`, `concept_id`) VALUES ('4', '9');
	
	
	
	INSERT IGNORE INTO `DisciplineCourseConnect` (`discipline_id`,`course_id`) VALUES
	(1,3),
	(1,4),
	(1,5),
	(1,6),
	(1,7),
	(1,8),
	(1,9),
	(1,10),
	(1,16),
	(2,4),
	(2,5),
	(2,6),
	(2,8),
	(2,10),
	(2,12),
	(2,13),
	(2,15),
	(2,16)	
	
	;   
	
	
	Selecting across a many to many relationship with a 
	
	SELECT Concept.concept_name 
	FROM Course JOIN CourseConceptConnect JOIN Concept
	ON CourseConceptConnect.course_id = Course.course_id AND CourseConceptConnect.concept_id = Concept.concept_id
	WHERE Course.course_id = '2'	
	ORDER BY Concept.concept_name ;
	
	
	
	
	INSERT IGNORE INTO `DisciplineCourseConnect` (`discipline_id`,`course_id`) VALUES
	(1,1),
	(1,2),
	(2,1),
	(4,1),
	(6,1)
	; 

	SELECT Course.course_name 
	FROM Discipline JOIN DisciplineCourseConnect JOIN Course
	ON DisciplineCourseConnect.discipline_id = Discipline.discipline_id AND DisciplineCourseConnect.course_id = Course.course_id
	WHERE Discipline.discipline_id = '2';	
	
	
CREATE TABLE IF NOT EXISTS `Author` (
  `author_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_name` varchar(50) NOT NULL,
  `synonym1` varchar(50),
  `synonym2` varchar(50),
  `synonym3` varchar(50),
  `synonym4` varchar(50),
  `synonym5` varchar(50),
  `synonym6` varchar(50),
  `synonym7` varchar(50),
  PRIMARY KEY (`author_id`),
  UNIQUE KEY unique_author (`author_name`)
  );


CREATE TABLE CourseAuthorConnect (
	`course_id` INTEGER,
	`author_id` INTEGER,
	CONSTRAINT FOREIGN KEY (`course_id`) REFERENCES Course (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT FOREIGN KEY (`author_id`) REFERENCES Author (`author_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (`course_id`,`author_id`)
) ENGINE=InnoDB CHARACTER SET = utf8;




	INSERT INTO `Author` (`author_name`,`synonym1`) VALUES ('Felder R.M. et al.','Felder and Rousseau' );
    INSERT INTO `Author` (`author_name`) VALUES ('Munson B.R. et al.');
	INSERT INTO `Author` (`author_name`) VALUES ('Crowl D.A. et al.');
	INSERT INTO `Author` (`author_name`) VALUES ('Himmelblau D.M. et al.');
	INSERT INTO `Author` (`author_name`) VALUES ('Gerhart P.M. et al.');
    INSERT INTO `Author` (`author_name`, `synonym1`) VALUES ('Smith J.M. et al.','Smith and Van Ness');
	INSERT INTO `Author` (`author_name`) VALUES ('Baratuci W.B.');
	INSERT INTO `Author` (`author_name`,`synonym1`) VALUES ('McCabe W. et al.','McCabe and Smith');
	INSERT INTO `Author` (`author_name`) VALUES ('Geankoplis C.J. et al.');
	INSERT INTO `Author` (`author_name`,`synonym1`) VALUES ('Incropera F.P. et al.','Incropera and DeWitt');
	INSERT INTO `Author` (`author_name`,`synonym1`) VALUES ('Elliott R.J. et al.','Elliott and Lira');
	INSERT INTO `Author` (`author_name`) VALUES ('Wankat P.C.');
    INSERT INTO `Author` (`author_name`, `synonym1`) VALUES ('Seader J.D. et al.','Seader and Henley');


INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('1', '2');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('4', '7');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('9', '12');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('2', '4');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('3', '4');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('2', '1');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('3', '1');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('4', '11');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('1', '9');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('6', '9');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('7', '9');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('6', '10');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('7', '10');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('1', '8');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('6', '8');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('7', '8');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('7', '13');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('4', '6');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('7', '12');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('1', '5');


INSERT INTO `Course` (`course_name`) VALUES ('Process Safety');
INSERT INTO `CourseAuthorConnect` (`course_id`, `author_id`) VALUES ('17', '3');





CREATE TABLE IF NOT EXISTS `University` (
  `university_id` int(11) NOT NULL AUTO_INCREMENT,
  `university_name` varchar(50) NOT NULL,
  `synonym1` varchar(50),
  `synonym2` varchar(50),
  `synonym3` varchar(50),
  `synonym4` varchar(50),
  `synonym5` varchar(50),
  `synonym6` varchar(50),
  `synonym7` varchar(50),
  PRIMARY KEY (`university_id`),
  UNIQUE KEY unique_university (`university_name`)
  );

 INSERT INTO `University` (`university_name`) VALUES ('Trine University');
 INSERT INTO `University` (`university_name`) VALUES ('Oklahoma State University');
  INSERT INTO `University` (`university_name`) VALUES ('Northeastern University');
 INSERT INTO `University` (`university_name`) VALUES ('West Virginia Univeristy Institute of Technology');


ALTER TABLE Problem	ADD unpubl_auth varchar(128);
	
	ALTER TABLE Problem 
  ADD num_try_1 int,
  ADD num_try_2 int,
  ADD num_try_3 int,
  ADD num_try_4 int,
  ADD num_try_5 int,
  ADD num_try_6 int,
  ADD num_try_7 int;
 
	ALTER TABLE Activity 
  ADD num_try int;
	
	
	CREATE TABLE IF NOT EXISTS `Gamep` (
		`gamep_id` int(11) NOT NULL AUTO_INCREMENT,
		`problem_id` int(11) NOT NULL,
		`iid` int(11) NOT NULL,
		`dex` int not Null,
		`rect` varchar(30),
		`oval` varchar(30),
		`trap` varchar(30),
		`hexa` varchar(30),
		`rect_vnum` varchar(5),
		`oval_vnum` varchar(5),
		`trap_vnum` varchar(5),
		`hexa_vnum` varchar(5),
		`rect_length` int, -- length of characters of the 
		`oval_length` int,
		`trap_length` int,
		`hexa_length` int,
		`prep_time` int, -- time in minutes that students can discuss the problem without being shown their numbers 
		`work_time` int, -- default number of minutes students have to work on the problem before auto submit
		`post_time` int, -- time in minutes for - post problem numerical problem analysis
		`exp_date` date, -- date the problem will be removed from the active game table
		`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`gamep_id`),
		CONSTRAINT FOREIGN KEY (`problem_id`) REFERENCES `Problem` (`problem_id`) 
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- use this code for template to get rid of problems past their expiration date also

		CREATE EVENT IF NOT EXISTS `wagnerj_qrp`.`delete_old`  -- the database name
		ON SCHEDULE
		EVERY 1 DAY -- or 1 HOUR
		COMMENT 'gets rid of all game entries past the expiration date'
		DO
		BEGIN

		DELETE FROM `wagnerj_qrp`.`Game` WHERE `exp_date` < NOW();

		END

		-- NOTE that MySQL Event Scheduler need to be enabled on your server:

		SET GLOBAL event_scheduler = ON;
		
		
		
	CREATE TABLE IF NOT EXISTS `Checker` (
		`checker_id` int(11) NOT NULL AUTO_INCREMENT,
		`problem_id` int(11) NOT NULL,
		`iid` int(11) NOT NULL,
		`pin` int(11) NOT NULL,
		`resp_a` varchar(10),
		`resp_b` varchar(10),
		`resp_c` varchar(10),
		`resp_d` varchar(10),
		`resp_e` varchar(10),
		`resp_f` varchar(10),
		`resp_g` varchar(10),
		`resp_h` varchar(10),
		`resp_i` varchar(10),
		`resp_j` varchar(10),
		`counts` int,
		`wcount_a` int,
		`wcount_b` int,
		`wcount_c` int,
		`wcount_d` int,
		`wcount_e` int,
		`wcount_f` int,
		`wcount_g` int,
		`wcount_h` int,
		`wcount_i` int,
		`wcount_j` int,
		`score` int,
		`rand1` int,
		`rand2` int,
		`exp_date` date, -- date the problem will be removed from the active game table
		`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`checker_id`),
		CONSTRAINT FOREIGN KEY (`problem_id`) REFERENCES `Problem` (`problem_id`) 
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	
	
	ALTER TABLE Problem 
		  ADD cumm_wcount_a int,
		  ADD cumm_wcount_b int,
		  ADD cumm_wcount_c int,
		  ADD cumm_wcount_d int,
		  ADD cumm_wcount_e int,
		  ADD cumm_wcount_f int,
		  ADD cumm_wcount_g int,
		  ADD cumm_wcount_h int,
		  ADD cumm_wcount_i int,
		  ADD cumm_wcount_j int;
		  

-- need to set the event scheduler

SET GLOBAL event_scheduler = ON;


-- dropping rows from a table after a certain time

CREATE EVENT clean_game
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
-- ON COMPLETION PRESERVE
DO 
DELETE LOW_PRIORITY FROM wagnerj_qrp.game WHERE `exp_date` < CURRENT_TIMESTAMP;

ALter TABLE `Assign`
	ADD exp_date date;  -- the time the activation of the problem ends

-- the above doen 








	-- next fileds are added for the password recovery system - I changed a filed I had called forgoten password to token








CREATE TABLE IF NOT EXISTS `Pswdreset` (
		`pswdreset_id` int(11) NOT NULL AUTO_INCREMENT,
		`email` VARCHAR(64) NOT NULL,
		`selector` VARCHAR(32) NOT NULL,
		`token` VARCHAR(64) NOT NULL,
		`token_exp` BIGINT(20) NOT NULL,
		PRIMARY KEY (`pswdreset_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `Problem` 
	ADD `allow_clone` INT NOT NULL AFTER `cumm_wcount_j`, 
	ADD `allow_edit` INT NOT NULL AFTER `allow_clone`;
		 
ALTER TABLE `Users` 
	ADD `grade_level` VARCHAR(16) NOT NULL AFTER `security`, 
	ADD `allow_clone_default` INT NOT NULL AFTER `grade_level`, 
	ADD `allow_edit_default` INT NOT NULL AFTER `allow_clone_default`, 
	ADD `sponsor_id` INT NOT NULL AFTER `allow_edit_default`;

ALTER TABLE `Problem` 
	ADD `parent` INT NOT NULL AFTER `allow_edit`, 
	ADD `children` VARCHAR(256) NOT NULL AFTER `parent`,
	ADD `orig_contr_id` INT AFTER `children`;
	
ALTER TABLE `Problem` 
	ADD `edit_id1` INT  AFTER `orig_contr_id`, 
	ADD `edit_id2` INT  AFTER `edit_id1`, 
	ADD `edit_id3` INT  AFTER `edit_id2`;
	
	
ALTER TABLE `Users` 
	ADD `TA_course_1` VARCHAR(64) AFTER `sponsor_id`, 
	ADD `TA_course_2` VARCHAR(64) AFTER `TA_course_1`, 
	ADD `TA_course_3` VARCHAR(64) AFTER `TA_course_2`, 
	ADD `TA_course_4` VARCHAR(64) AFTER `TA_course_3`, 
	ADD `exp_date` DATETIME AFTER `created_at`,
	ADD `suspended` BOOLEAN NOT NULL AFTER `exp_date`;
	
	
	ALTER TABLE `Problem` 
	ADD `grade` INT  AFTER `edit_id3`; 
	
CREATE TABLE IF NOT EXISTS `Threat` (
		`threat_id` int(11) NOT NULL AUTO_INCREMENT,
		`threat_level` int(11) NOT NULL,
		`users_id` int(11) NOT NULL,
		`threat_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`threat_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;	
	
	
	ALTER TABLE `Assign` 
	ADD `grader_id1` INT  AFTER `postp_flag3`, 
	ADD `grader_id2` INT  AFTER `grader_id1`, 
	ADD `grader_id3` INT  AFTER `grader_id2`;
	
	ALTER TABLE `Assign` 
	ADD `alias_num` INT  AFTER `assign_num`;
	
	
	CREATE TABLE IF NOT EXISTS `CurrentClass` (
		`currentclass_id` int(11) NOT NULL AUTO_INCREMENT,
		`iid` int(11) NOT NULL,
		`name` VARCHAR(64),
		`sec_desig_1` VARCHAR(32),
		`sec_desig_2` VARCHAR(32),
		`sec_desig_3` VARCHAR(32),
		`sec_desig_4` VARCHAR(32),
		`sec_desig_5` VARCHAR(32),
		`sec_desig_6` VARCHAR(32),
		`exp_date` DATETIME,
		`input_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`currentclass_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;	
	
	
	-- should use a index and a connector table but do it simply for now --
	ALTER TABLE `Assign` 
	ADD `currentclass_id` int(11) AFTER `prob_num`, 
	ADD `sec_desig_1` VARCHAR(32)  AFTER `currentclass_id`, 
	ADD `sec_desig_2` VARCHAR(32)  AFTER `sec_desig_1`, 
	ADD `sec_desig_3` VARCHAR(32)  AFTER `sec_desig_2`, 
	ADD `sec_desig_4` VARCHAR(32)  AFTER `sec_desig_3`, 
	ADD `sec_desig_5` VARCHAR(32)  AFTER `sec_desig_4`, 
	ADD `sec_desig_6` VARCHAR(32)  AFTER `sec_desig_5`;

	ALTER TABLE `Activity` 
	ADD `currentclass_id` INT  AFTER `assign_id`,
	ADD `alias_num` INT  AFTER `currentclass_id`;
	
	ALTER TABLE Activity ADD CONSTRAINT `currentclass_id`
FOREIGN KEY ( `currentclass_id` ) REFERENCES CurrentClass (`currentclass_id`) ON DELETE CASCADE ON UPDATE CASCADE;

	ALTER TABLE Assign ADD CONSTRAINT `currentclass_id`
FOREIGN KEY ( `currentclass_id` ) REFERENCES CurrentClass (`currentclass_id`) ON DELETE CASCADE ON UPDATE CASCADE;

SELECT DISTINCT last, first FROM Users INNER JOIN Assign ON Users.users_id = Assign.iid
-- sets all of the previous problems to college problems
UPDATE Problem set grade = 4

ALTER TABLE `Problem` 
	ADD `solnaux` VARCHAR(256) AFTER `grade`;
	
UPDATE Problem 
SET 
    allow_edit = 1
WHERE
    problem_id < 320;
    
 CREATE TABLE IF NOT EXISTS `Gameactivity` (
		`gameactivity_id` int(11) NOT NULL AUTO_INCREMENT,
		`game_id` int(11) NOT NULL,
		`team_id` int(11) NOT NULL,
        `gmact_id` int(11) NOT NULL,
        `problem_id` int(11) NOT NULL,
        `name` VARCHAR(64),
        `player_id` int(11),
        `iid` int(11) NOT NULL,
        `team_size` int(2),
        `team_size_error` INT(1) NOT NULL,
		`pin` int(8) NOT NULL,
		`dex` INT(4) NOT NULL,
         `ans_b` DOUBLE,
        `ans_last` DOUBLE,
        `ans_sumb` DOUBLE,
        `ans_sumlast` DOUBLE,
        `score` int(4),
        `team_cohesivity` int(4),
        `kahoot_score` int(8),
        `team_score` int(4),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`gameactivity_id`),
		CONSTRAINT FOREIGN KEY (`game_id`) REFERENCES `Game` (`game_id`) 
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;   
    
   



	
     CREATE TABLE IF NOT EXISTS `Gmact` (
		`gmact_id` int(11) NOT NULL AUTO_INCREMENT,
		`game_id` int(11) NOT NULL,
		`iid` int(11) NOT NULL,
		`phase` INT(2) NOT NULL,
        `on_the_fly` INT(1) NOT NULL,
        `prep_time` INT(4),
        `prep_time_talk` INT(4),
        `work_time` INT(4),
        `post_time` INT(4),
        `post_time_talk` INT(4),
        `class_time_talk` INT(4),
         `end_of_phase` TIMESTAMP DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`gmact_id`),
		CONSTRAINT FOREIGN KEY (`game_id`) REFERENCES `Game` (`game_id`) 
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;   
    
    
    CREATE TABLE IF NOT EXISTS `Exam` (
  `exam_id` int(11) NOT NULL AUTO_INCREMENT,
  `instr_last` varchar(50) NOT NULL,
   `iid` int(11) NOT NULL,
   `university` varchar(255) NOT NULL,
   exam_num int,
   alias_num int,
   currentclass_id int,
   problem_id int,
   proctor_id1 int,
   proctor_id2 int,
   proctor_id3 int,
   	`exp_date` DATETIME,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`exam_id`)
  )ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
      CREATE TABLE IF NOT EXISTS `Examtime` (
		`examtime_id` int(11) NOT NULL AUTO_INCREMENT,
		`exam_num` int(11) NOT NULL,
		`iid` int(11) NOT NULL,
        `currentclass_id` int(11) NOT NULL,
		`globephase` INT(2) NOT NULL,
        `work_time` INT(4),
         `attempt_type` INT(2),
          `num_attempts` INT(3),
          `exam_code` int(11), 
         `end_of_phase` TIMESTAMP DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`examtime_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;   
    
    
    	ALTER TABLE `Examactivity` 
	ADD `extend_time_flag` INT(2) NOT NULL  AFTER `suspend_flag`
    
    
  -- need to look at the Checker, some of this is already  (can we put the checkerid as a foriegn key and have it.  accomplished when we look at show in the repo
  
 CREATE TABLE IF NOT EXISTS `Examactivity` (
		`examactivity_id` int(11) NOT NULL AUTO_INCREMENT,
		`examtime_id` int(11) NOT NULL,
      
        `name` VARCHAR(64),
        `taker_id` int(11),
        `iid` int(11) NOT NULL,
		`pin` int(8) NOT NULL,
		`dex` INT(4) NOT NULL,
        `exam_code` int(11) NOT NULL,
        `currentclass_id` int(11) NOT NULL,
        `work_time` INT(4),
        `suspend_flag` INT(2) NOT NULL,
        `extend_time_flag` INT(2),
       `problem_id1` int(11) NOT NULL,
        `problem_id2` int(11) NOT NULL,
        `problem_id3` int(11) NOT NULL,
        `problem_id4` int(11) NOT NULL,
        `problem_id5` int(11) NOT NULL,        
        `pblm_1_score` INT(4) NOT NULL,
        `pblm_2_score` INT(4) NOT NULL,
        `pblm_3_score` INT(4) NOT NULL,
        `pblm_4_score` INT(4) NOT NULL,
        `pblm_5_score` INT(4) NOT NULL,
        `pblm_6_score` INT(4) NOT NULL,
        `response_pblm1` VARCHAR(128),
        `response_pblm2` VARCHAR(128),
        `response_pblm3` VARCHAR(128),
        `response_pblm4` VARCHAR(128),
        `response_pblm5` VARCHAR(128),
        `trynum_pblm1` int(4),
        `trynum_pblm2` int(4),
        `trynum_pblm3` int(4),
        `trynum_pblm4` int(4),
        `trynum_pblm5` int(4),
        `minutes` int(8),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`examactivity_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;   
    
  	ALTER TABLE `Examactivity` 
	ADD `country` VARCHAR(64) AFTER `minutes`,
    ADD `region` VARCHAR(64) AFTER `country`,
    ADD `city` VARCHAR(64) AFTER `region`;
    
   	ALTER TABLE `Examactivity` 
	ADD `extend_time_flag` INT(2) AFTER `suspend_flag`;
    
    
    	
	ALTER TABLE Problem 
		 ADD `reflect` TEXT AFTER `solnaux`,
		 ADD `explore` TEXT AFTER `reflect`,
         ADD `connec_t` TEXT AFTER `explore`,
         ADD `society` TEXT AFTER `connec_t`;
		 
         
         
        
          
	
         
        CREATE TABLE IF NOT EXISTS `Resp` (
		`resp_id` int(11) NOT NULL AUTO_INCREMENT,
        `activity_id` int(11) NOT NULL, 
		`resp_value` DOUBLE NOT NULL,
        `part_name` VARCHAR(2),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`resp_id`),
        CONSTRAINT FOREIGN KEY (`activity_id`) REFERENCES `Activity` (`activity_id`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;   
        
        
        ALTER TABLE tableName CHANGE `oldcolname` `newcolname` datatype(length);
        
        ALTER TABLE `Activity` 
		 CHANGE `help_coins_used` `progress` INT(2);
         
        ALTER TABLE Activity 
		 CHANGE `assist_coins_gained`  `completed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
    
     ALTER TABLE Activity 
		 CHANGE `completed_at` `last_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
    
    ALTER TABLE Activity 
		 ADD `count_tot` INT AFTER `score`;
    
      
        CREATE TABLE IF NOT EXISTS `Bc_resp` (
		`bc_resp_id` int(11) NOT NULL AUTO_INCREMENT,
        `activity_id` int(11) NOT NULL, 
		`resp_value` DOUBLE NOT NULL,
        `part_name` VARCHAR(2),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`bc_resp_id`),
        CONSTRAINT FOREIGN KEY (`activity_id`) REFERENCES `Activity` (`activity_id`) )
        ENGINE=InnoDB DEFAULT CHARSET=utf8;   
        
        ALTER TABLE Activity 
		 ADD `correct_a` int(2) AFTER `university`,
		 ADD `correct_b` int(2) AFTER `correct_a`,
         ADD `correct_c` int(2) AFTER `correct_b`,
		 ADD `correct_d` int(2) AFTER `correct_c`,
         ADD `correct_e` int(2) AFTER `correct_d`,
		 ADD `correct_f` int(2) AFTER `correct_e`,
         ADD `correct_g` int(2) AFTER `correct_f`,
		 ADD `correct_h` int(2) AFTER `correct_g`,
		 ADD `correct_i` int(2) AFTER `correct_h`,
		 ADD `correct_j` int(2) AFTER `correct_i`;
         
         
  CREATE TABLE IF NOT EXISTS `Student` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
   `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
	`school_email` varchar(100) NOT NULL,
    `email2` varchar(100),
	`forgot_pswd` varchar(255) NOT NULL,
   `password` varchar(255) NOT NULL,
   `university` VARCHAR(100) NOT NULL,
   `currentclass_id_1` int(11) NOT NULL,
   `currentclass_id_2` int(11) NOT NULL,
   `currentclass_id_3` int(11) NOT NULL,
   `currentclass_id_4` int(11) NOT NULL,
   `currentclass_id_5` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  	`exp_date` DATETIME,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        
   CREATE TABLE StudentCurrentClassConnect (
	currentclass_id INTEGER,
	student_id INTEGER,
    pin INTEGER,
	CONSTRAINT FOREIGN KEY (currentclass_id) REFERENCES CurrentClass (currentclass_id),
	CONSTRAINT FOREIGN KEY (student_id) REFERENCES Student (student_id),
	PRIMARY KEY (`currentclass_id`,`student_id`)
) ENGINE=InnoDB CHARACTER SET = utf8;     

 ALTER TABLE Activity 
		 ADD `student_id` INT AFTER `dex`;
         
         
         
  -- this is the stuff for qrblood --       
     CREATE TABLE IF NOT EXISTS `Users` (
  `users_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
   `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
	`email` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  	`exp_date` DATETIME,
  PRIMARY KEY (`users_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;     

    CREATE TABLE IF NOT EXISTS `Activity` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
   `users_id` int(11) ,
  `blood_sugar` int(11) NOT NULL,
   `systolic` int(11) NOT NULL,
    `diastolic` int(11) NOT NULL,
	`pulse` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`activity_id`),
  CONSTRAINT FOREIGN KEY (users_id) REFERENCES Users (users_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;     
         
 
       
        
  ALTER TABLE Users 
		 ADD `email6` VARCHAR(100) AFTER `email5`,
         ADD `email7` VARCHAR(100) AFTER `email6`,
         ADD `email8` VARCHAR(100) AFTER `email7`,
         ADD `birthdate` DATE AFTER `email8`;    

ALTER TABLE Users 
		 ADD `users_sophistication` int(1) AFTER `last_name`;
         
 -- this is the stuff for qrblood --      
         
  ALTER TABLE Activity 
		 ADD `short_acting_insulin` INT(8) AFTER `blood_sugar`,
         ADD `long_acting_insulin` INT(8) AFTER `short_acting_insulin`;
             
         
      ALTER TABLE Users 
		 ADD `time_zone` VARCHAR(100) AFTER `last_name`;
        
    ALTER TABLE Activity 
		 ADD `comment` VARCHAR(200) AFTER `updated_at`;
                     
        CREATE TABLE IF NOT EXISTS `Monitors` (
  `monitors_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
   `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
	`email` varchar(100) NOT NULL,
    `phone1` varchar(100) NOT NULL,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  	`exp_date` DATETIME,
  PRIMARY KEY (`monitors_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;        


-- email_mode 0 = off, 1 = email all 2 = email extreem values  phone_mode is similar--
CREATE TABLE UserMonitorConnect (
	users_id INTEGER,
	monitors_id INTEGER,
    email_mode INTEGER,
    phone_mode INTEGER,
	CONSTRAINT FOREIGN KEY (users_id) REFERENCES Users (users_id),
	CONSTRAINT FOREIGN KEY (monitors_id) REFERENCES Monitors (monitors_id),
	PRIMARY KEY (`users_id`,`monitors_id`)
) ENGINE=InnoDB CHARACTER SET = utf8;
-- this is the stuff for qrblood --      
    ALTER TABLE Users 
		 ADD `sugar_base` INT(4) AFTER `time_zone`,
         ADD `sugar_p_unit` INT(4) AFTER `sugar_base`, 
         ADD `carb_p_unit` INT(4) AFTER `sugar_p_unit`;
         
      ALTER TABLE Activity 
		 ADD `carbs` int(5) AFTER `long_acting_insulin`;  

ALTER TABLE Activity ADD UNIQUE INDEX(blood_sugar, created_at);
ALTER TABLE Activity ADD UNIQUE INDEX(users_id, created_at);


-- back to the homework system--
ALTER TABLE Activity 
		 ADD `bc_correct_a` INT(2) AFTER `university`,
         ADD `bc_correct_b` INT(2) AFTER `bc_correct_a`,
         ADD `bc_correct_c` INT(2) AFTER `bc_correct_b`,
         ADD `bc_correct_d` INT(2) AFTER `bc_correct_c`,
         ADD `bc_correct_e` INT(2) AFTER `bc_correct_d`,
         ADD `bc_correct_f` INT(2) AFTER `bc_correct_e`,
         ADD `bc_correct_g` INT(2) AFTER `bc_correct_f`,
         ADD `bc_correct_h` INT(2) AFTER `bc_correct_g`,
         ADD `bc_correct_i` INT(2) AFTER `bc_correct_h`,
         ADD `bc_correct_j` INT(2) AFTER `bc_correct_i`;
         
 ALTER TABLE Assigntime 
		     
           
   CREATE TABLE IF NOT EXISTS `Assigntime` (
		`assigntime_id` int(11) NOT NULL AUTO_INCREMENT,
		`assign_num` int(11) NOT NULL,
		`iid` int(11) NOT NULL,
        `currentclass_id` int(11) NOT NULL,
         `work_flow` VARCHAR(10),
         `bc_ans_n` INT(4) ,
         `bc_ans_t` INT(6),
         `p_bc_n` INT(4),
         `p_bc_t` INT(6),
         `help_n_stu` INT(4),
         `help_t_stu` INT(4),
         `help_n_ta` INT(4),
         `help_t_ta` INT(4),         
         `help_n_instruct` INT(4),
         `help_t_instruct` INT(4),   
        `work_time_per_problem` INT(4),
          `max_attempts_per_problem` INT(3),
          `window_opens` DATETIME,
           `due_date` DATETIME,
         `window_closes`  DATETIME,
         `credit` VARCHAR(20),
         `late_points` VARCHAR(30),
         `fixed_percent_decline` INT(4),
         `perc_ec_max_p_assign` INT(3),
         `perc_ec_max_p_pblm` INT(3),
         `perc_ec_max_person_to_person` INT(3),
         `ec_daysb4due_elgible` INT(3),
         `perc_ec_base_video` INT(3),
         `perc_ec_base_audio` INT(3),
         `perc_ec_base_written` INT(3),
         `peer_refl_t` INT(3),
         `peer_refl_n` INT(3),
         `perc_1` INT(3),
         `perc_a_1` INT(3),
         `perc_b_1` INT(3),
         `perc_c_1` INT(3),
         `perc_d_1` INT(3),
         `perc_e_1` INT(3),
         `perc_f_1` INT(3),
         `perc_g_1` INT(3),
         `perc_h_1` INT(3),
         `perc_i_1` INT(3),
         `perc_j_1` INT(3),
         `perc_pp1_1` INT(3),
         `perc_pp2_1` INT(3),
         `perc_pp3_1` INT(3),
         `perc_ref_1` INT(3),
         `perc_exp_1` INT(3),
         `perc_con_1` INT(3),
         `perc_soc_1` INT(3),
         `perc_any1_ref_1` INT(3),
         `perc_any2_ref_1` INT(3),
         `perc_any3_ref_1` INT(3),
         `survey_1` INT(3),
         `perc_2` INT(3),
         `perc_a_2` INT(3),
         `perc_b_2` INT(3),
         `perc_c_2` INT(3),
         `perc_d_2` INT(3),
         `perc_e_2` INT(3),
         `perc_f_2` INT(3),
         `perc_g_2` INT(3),
         `perc_h_2` INT(3),
         `perc_i_2` INT(3),
         `perc_j_2` INT(3),
         `perc_pp1_2` INT(3),
         `perc_pp2_2` INT(3),
         `perc_pp3_2` INT(3),
         `perc_ref_2` INT(3),
         `perc_exp_2` INT(3),
         `perc_con_2` INT(3),
         `perc_soc_2` INT(3),
         `perc_any1_ref_2` INT(3),
         `perc_any2_ref_2` INT(3),
         `perc_any3_ref_2` INT(3),      
         `survey_2` INT(3),
         `perc_3` INT(3),
         `perc_a_3` INT(3),
         `perc_b_3` INT(3),
         `perc_c_3` INT(3),
         `perc_d_3` INT(3),
         `perc_e_3` INT(3),
         `perc_f_3` INT(3),
         `perc_g_3` INT(3),
         `perc_h_3` INT(3),
         `perc_i_3` INT(3),
         `perc_j_3` INT(3),
         `perc_pp1_3` INT(3),
         `perc_pp2_3` INT(3),
         `perc_pp3_3` INT(3),
         `perc_ref_3` INT(3),
         `perc_exp_3` INT(3),
         `perc_con_3` INT(3),
         `perc_soc_3` INT(3),
         `perc_any1_ref_3` INT(3),
         `perc_any2_ref_3` INT(3),
         `perc_any3_ref_3` INT(3), 
         `survey_3` INT(3),
         `perc_4` INT(3),
         `perc_a_4` INT(3),
         `perc_b_4` INT(3),
         `perc_c_4` INT(3),
         `perc_d_4` INT(3),
         `perc_e_4` INT(3),
         `perc_f_4` INT(3),
         `perc_g_4` INT(3),
         `perc_h_4` INT(3),
         `perc_i_4` INT(3),
         `perc_j_4` INT(3),
         `perc_pp1_4` INT(3),
         `perc_pp2_4` INT(3),
         `perc_pp3_4` INT(3),
         `perc_ref_4` INT(3),
         `perc_exp_4` INT(3),
         `perc_con_4` INT(3),
         `perc_soc_4` INT(3),
         `perc_any1_ref_4` INT(3),
         `perc_any2_ref_4` INT(3),
         `perc_any3_ref_4` INT(3),
         `survey_4` INT(3),
         `perc_5` INT(3),
         `perc_a_5` INT(3),
         `perc_b_5` INT(3),
         `perc_c_5` INT(3),
         `perc_d_5` INT(3),
         `perc_e_5` INT(3),
         `perc_f_5` INT(3),
         `perc_g_5` INT(3),
         `perc_h_5` INT(3),
         `perc_i_5` INT(3),
         `perc_j_5` INT(3),
         `perc_pp1_5` INT(3),
         `perc_pp2_5` INT(3),
         `perc_pp3_5` INT(3),
         `perc_ref_5` INT(3),
         `perc_exp_5` INT(3),
         `perc_con_5` INT(3),
         `perc_soc_5` INT(3),
         `survey_5` INT(3),
         `perc_any1_ref_5` INT(3),
         `perc_any2_ref_5` INT(3),
         `perc_any3_ref_5` INT(3),     
         `perc_6` INT(3),
         `perc_a_6` INT(3),
         `perc_b_6` INT(3),
         `perc_c_6` INT(3),
         `perc_d_6` INT(3),
         `perc_e_6` INT(3),
         `perc_f_6` INT(3),
         `perc_g_6` INT(3),
         `perc_h_6` INT(3),
         `perc_i_6` INT(3),
         `perc_j_6` INT(3),
         `perc_pp1_6` INT(3),
         `perc_pp2_6` INT(3),
         `perc_pp3_6` INT(3),
         `perc_ref_6` INT(3),
         `perc_exp_6` INT(3),
         `perc_con_6` INT(3),
         `perc_soc_6` INT(3),
         `perc_any1_ref_6` INT(3),
         `perc_any2_ref_6` INT(3),
         `perc_any3_ref_6` INT(3), 
         `survey_6` INT(3),         
         `perc_7` INT(3),
         `perc_a_7` INT(3),
         `perc_b_7` INT(3),
         `perc_c_7` INT(3),
         `perc_d_7` INT(3),
         `perc_e_7` INT(3),
         `perc_f_7` INT(3),
         `perc_g_7` INT(3),
         `perc_h_7` INT(3),
         `perc_i_7` INT(3),
         `perc_j_7` INT(3),
         `perc_pp1_7` INT(3),
         `perc_pp2_7` INT(3),
         `perc_pp3_7` INT(3),
         `perc_ref_7` INT(3),
         `perc_exp_7` INT(3),
         `perc_con_7` INT(3),
         `perc_soc_7` INT(3),
         `perc_any1_ref_7` INT(3),
         `perc_any2_ref_7` INT(3),
         `perc_any3_ref_7` INT(3), 
         `survey_7` INT(3),         
         `perc_8` INT(3),
         `perc_a_8` INT(3),
         `perc_b_8` INT(3),
         `perc_c_8` INT(3),
         `perc_d_8` INT(3),
         `perc_e_8` INT(3),
         `perc_f_8` INT(3),
         `perc_g_8` INT(3),
         `perc_h_8` INT(3),
         `perc_i_8` INT(3),
         `perc_j_8` INT(3),
         `perc_pp1_8` INT(3),
         `perc_pp2_8` INT(3),
         `perc_pp3_8` INT(3),
         `perc_ref_8` INT(3),
         `perc_exp_8` INT(3),
         `perc_con_8` INT(3),
         `perc_soc_8` INT(3),
         `perc_any1_ref_8` INT(3),
         `perc_any2_ref_8` INT(3),
         `perc_any3_ref_8` INT(3), 
         `survey_8` INT(3),         
         `perc_9` INT(3),
         `perc_a_9` INT(3),
         `perc_b_9` INT(3),
         `perc_c_9` INT(3),
         `perc_d_9` INT(3),
         `perc_e_9` INT(3),
         `perc_f_9` INT(3),
         `perc_g_9` INT(3),
         `perc_h_9` INT(3),
         `perc_i_9` INT(3),
         `perc_j_9` INT(3),
         `perc_pp1_9` INT(3),
         `perc_pp2_9` INT(3),
         `perc_pp3_9` INT(3),
         `perc_ref_9` INT(3),
         `perc_exp_9` INT(3),
         `perc_con_9` INT(3),
         `perc_soc_9` INT(3),
         `perc_any1_ref_9` INT(3),
         `perc_any2_ref_9` INT(3),
         `perc_any3_ref_9` INT(3),
         `survey_9` INT(3),         
         `perc_10` INT(3),
         `perc_a_10` INT(3),
         `perc_b_10` INT(3),
         `perc_c_10` INT(3),
         `perc_d_10` INT(3),
         `perc_e_10` INT(3),
         `perc_f_10` INT(3),
         `perc_g_10` INT(3),
         `perc_h_10` INT(3),
         `perc_i_10` INT(3),
         `perc_j_10` INT(3),
         `perc_pp1_10` INT(3),
         `perc_pp2_10` INT(3),
         `perc_pp3_10` INT(3),
         `perc_ref_10` INT(3),
         `perc_exp_10` INT(3),
         `perc_con_10` INT(3),
         `perc_soc_10` INT(3),
         `perc_any1_ref_10` INT(3),
         `perc_any2_ref_10` INT(3),
         `perc_any3_ref_10` INT(3),  
         `survey_10` INT(3),         
         `perc_11` INT(3),
         `perc_a_11` INT(3),
         `perc_b_11` INT(3),
         `perc_c_11` INT(3),
         `perc_d_11` INT(3),
         `perc_e_11` INT(3),
         `perc_f_11` INT(3),
         `perc_g_11` INT(3),
         `perc_h_11` INT(3),
         `perc_i_11` INT(3),
         `perc_j_11` INT(3),
         `perc_pp1_11` INT(3),
         `perc_pp2_11` INT(3),
         `perc_pp3_11` INT(3),
         `perc_ref_11` INT(3),
         `perc_exp_11` INT(3),
         `perc_con_11` INT(3),
         `perc_soc_11` INT(3),
         `perc_any1_ref_11` INT(3),
         `perc_any2_ref_11` INT(3),
         `perc_any3_ref_11` INT(3),
         `survey_11` INT(3),         
         `perc_12` INT(3),
         `perc_a_12` INT(3),
         `perc_b_12` INT(3),
         `perc_c_12` INT(3),
         `perc_d_12` INT(3),
         `perc_e_12` INT(3),
         `perc_f_12` INT(3),
         `perc_g_12` INT(3),
         `perc_h_12` INT(3),
         `perc_i_12` INT(3),
         `perc_j_12` INT(3),
         `perc_pp1_12` INT(3),
         `perc_pp2_12` INT(3),
         `perc_pp3_12` INT(3),
         `perc_ref_12` INT(3),
         `perc_exp_12` INT(3),
         `perc_con_12` INT(3),
         `perc_soc_12` INT(3),
         `perc_any1_ref_12` INT(3),
         `perc_any2_ref_12` INT(3),
         `perc_any3_ref_12` INT(3),  
         `survey_12` INT(3),         
         `perc_13` INT(3),
         `perc_a_13` INT(3),
         `perc_b_13` INT(3),
         `perc_c_13` INT(3),
         `perc_d_13` INT(3),
         `perc_e_13` INT(3),
         `perc_f_13` INT(3),
         `perc_g_13` INT(3),
         `perc_h_13` INT(3),
         `perc_i_13` INT(3),
         `perc_j_13` INT(3),
         `perc_pp1_13` INT(3),
         `perc_pp2_13` INT(3),
         `perc_pp3_13` INT(3),
         `perc_ref_13` INT(3),
         `perc_exp_13` INT(3),
         `perc_con_13` INT(3),
         `perc_soc_13` INT(3),
         `perc_any1_ref_13` INT(3),
         `perc_any2_ref_13` INT(3),
         `perc_any3_ref_13` INT(3),
         `survey_13` INT(3),         
         `perc_14` INT(3),
         `perc_a_14` INT(3),
         `perc_b_14` INT(3),
         `perc_c_14` INT(3),
         `perc_d_14` INT(3),
         `perc_e_14` INT(3),
         `perc_f_14` INT(3),
         `perc_g_14` INT(3),
         `perc_h_14` INT(3),
         `perc_i_14` INT(3),
         `perc_j_14` INT(3),
         `perc_pp1_14` INT(3),
         `perc_pp2_14` INT(3),
         `perc_pp3_14` INT(3),
         `perc_ref_14` INT(3),
         `perc_exp_14` INT(3),
         `perc_con_14` INT(3),
         `perc_soc_14` INT(3),
         `perc_any1_ref_14` INT(3),
         `perc_any2_ref_14` INT(3),
         `perc_any3_ref_14` INT(3),
         `survey_14` INT(3),         
         `perc_15` INT(3),
         `perc_a_15` INT(3),
         `perc_b_15` INT(3),
         `perc_c_15` INT(3),
         `perc_d_15` INT(3),
         `perc_e_15` INT(3),
         `perc_f_15` INT(3),
         `perc_g_15` INT(3),
         `perc_h_15` INT(3),
         `perc_i_15` INT(3),
         `perc_j_15` INT(3),
         `perc_pp1_15` INT(3),
         `perc_pp2_15` INT(3),
         `perc_pp3_15` INT(3),
         `perc_ref_15` INT(3),
         `perc_exp_15` INT(3),
         `perc_con_15` INT(3),
         `perc_soc_15` INT(3),
         `perc_any1_ref_15` INT(3),
         `perc_any2_ref_15` INT(3),
         `perc_any3_ref_15` INT(3), 
         `survey_15` INT(3),         
         `perc_16` INT(3),
         `perc_a_16` INT(3),
         `perc_b_16` INT(3),
         `perc_c_16` INT(3),
         `perc_d_16` INT(3),
         `perc_e_16` INT(3),
         `perc_f_16` INT(3),
         `perc_g_16` INT(3),
         `perc_h_16` INT(3),
         `perc_i_16` INT(3),
         `perc_j_16` INT(3),
         `perc_pp1_16` INT(3),
         `perc_pp2_16` INT(3),
         `perc_pp3_16` INT(3),
         `perc_ref_16` INT(3),
         `perc_exp_16` INT(3),
         `perc_con_16` INT(3),
         `perc_soc_16` INT(3),
         `perc_any1_ref_16` INT(3),
         `perc_any2_ref_16` INT(3),
         `perc_any3_ref_16` INT(3), 
         `survey_16` INT(3),         
         `perc_17` INT(3),
         `perc_a_17` INT(3),
         `perc_b_17` INT(3),
         `perc_c_17` INT(3),
         `perc_d_17` INT(3),
         `perc_e_17` INT(3),
         `perc_f_17` INT(3),
         `perc_g_17` INT(3),
         `perc_h_17` INT(3),
         `perc_i_17` INT(3),
         `perc_j_17` INT(3),
         `perc_pp1_17` INT(3),
         `perc_pp2_17` INT(3),
         `perc_pp3_17` INT(3),
         `perc_ref_17` INT(3),
         `perc_exp_17` INT(3),
         `perc_con_17` INT(3),
         `perc_soc_17` INT(3),
         `perc_any1_ref_17` INT(3),
         `perc_any2_ref_17` INT(3),
         `perc_any3_ref_17` INT(3), 
         `survey_17` INT(3),         
         `perc_18` INT(3),
         `perc_a_18` INT(3),
         `perc_b_18` INT(3),
         `perc_c_18` INT(3),
         `perc_d_18` INT(3),
         `perc_e_18` INT(3),
         `perc_f_18` INT(3),
         `perc_g_18` INT(3),
         `perc_h_18` INT(3),
         `perc_i_18` INT(3),
         `perc_j_18` INT(3),
         `perc_pp1_18` INT(3),
         `perc_pp2_18` INT(3),
         `perc_pp3_18` INT(3),
         `perc_ref_18` INT(3),
         `perc_exp_18` INT(3),
         `perc_con_18` INT(3),
         `perc_soc_18` INT(3),
         `perc_any1_ref_18` INT(3),
         `perc_any2_ref_18` INT(3),
         `perc_any3_ref_18` INT(3),
         `survey_18` INT(3),         
         `perc_19` INT(3),
         `perc_a_19` INT(3),
         `perc_b_19` INT(3),
         `perc_c_19` INT(3),
         `perc_d_19` INT(3),
         `perc_e_19` INT(3),
         `perc_f_19` INT(3),
         `perc_g_19` INT(3),
         `perc_h_19` INT(3),
         `perc_i_19` INT(3),
         `perc_j_19` INT(3),
         `perc_pp1_19` INT(3),
         `perc_pp2_19` INT(3),
         `perc_pp3_19` INT(3),
         `perc_ref_19` INT(3),
         `perc_exp_19` INT(3),
         `perc_con_19` INT(3),
         `perc_soc_19` INT(3),
         `perc_any1_ref_19` INT(3),
         `perc_any2_ref_19` INT(3),
         `perc_any3_ref_19` INT(3), 
         `survey_19` INT(3),         
         `perc_20` INT(3),
         `perc_a_20` INT(3),
         `perc_b_20` INT(3),
         `perc_c_20` INT(3),
         `perc_d_20` INT(3),
         `perc_e_20` INT(3),
         `perc_f_20` INT(3),
         `perc_g_20` INT(3),
         `perc_h_20` INT(3),
         `perc_i_20` INT(3),
         `perc_j_20` INT(3),
         `perc_pp1_20` INT(3),
         `perc_pp2_20` INT(3),
         `perc_pp3_20` INT(3),
         `perc_ref_20` INT(3),
         `perc_exp_20` INT(3),
         `perc_con_20` INT(3),
         `perc_soc_20` INT(3),
         `perc_any1_ref_20` INT(3),
         `perc_any2_ref_20` INT(3),
         `perc_any3_ref_20` INT(3),
         `survey_20` INT(3),         
         `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`assigntime_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;           
         
     ALTER TABLE Activity 
		 ADD `assigntime_id` INT(11) AFTER `assign_id`,
         ADD `p_num_score_raw` INT(4) AFTER `score`,
         ADD `late_penalty` INT(4) AFTER `p_num_score_raw`,
         ADD `p_num_score_net` INT(4) AFTER `late_penalty`,
         ADD `survey_pts` INT(4) AFTER `p_num_score_net`,
         ADD `ec_elgible_flag` INT(4) AFTER `survey_pts`,
         ADD `wants_ec` INT(4) AFTER `ec_elgible_flag`,
         ADD `ec_pts` INT(4) AFTER `wants_ec`,
         ADD `reflect_pts` INT(4) AFTER `ec_pts`,
         ADD `explore_pts` INT(4) AFTER `reflect_pts`,
         ADD `connect_pts` INT(4) AFTER `explore_pts`,
         ADD `society_pts` INT(4) AFTER `connect_pts`,
         ADD `pp1_pts` INT(4) AFTER `society_pts`,
         ADD `pp2_pts` INT(4) AFTER `pp1_pts`,
         ADD `pp3_pts` INT(4) AFTER `pp2_pts`,
        ADD `wcount_bc_a` INT(4) AFTER `count_tot`,
        ADD `wcount_bc_b` INT(4) AFTER `wcount_bc_a`,
        ADD `wcount_bc_c` INT(4) AFTER `wcount_bc_b`,
        ADD `wcount_bc_d` INT(4) AFTER `wcount_bc_c`,
        ADD `wcount_bc_e` INT(4) AFTER `wcount_bc_d`,
        ADD `wcount_bc_f` INT(4) AFTER `wcount_bc_e`,
        ADD `wcount_bc_g` INT(4) AFTER `wcount_bc_f`,
        ADD `wcount_bc_h` INT(4) AFTER `wcount_bc_g`,
        ADD `wcount_bc_i` INT(4) AFTER `wcount_bc_h`,
        ADD `wcount_bc_j` INT(4) AFTER `wcount_bc_i`,
        ADD `wcount_a` INT(4) AFTER `wcount_bc_j`,
        ADD `wcount_b` INT(4) AFTER `wcount_a`,
        ADD `wcount_c` INT(4) AFTER `wcount_b`,
        ADD `wcount_d` INT(4) AFTER `wcount_c`,
        ADD `wcount_e` INT(4) AFTER `wcount_d`,
        ADD `wcount_f` INT(4) AFTER `wcount_e`,
        ADD `wcount_g` INT(4) AFTER `wcount_f`,
        ADD `wcount_h` INT(4) AFTER `wcount_g`,
        ADD `wcount_i` INT(4) AFTER `wcount_h`,
        ADD `wcount_j` INT(4) AFTER `wcount_i`,
         ADD `switch_to_bc` INT(2) AFTER `wcount_j`,
        ADD `reflect_text` VARCHAR(3000) AFTER `switch_to_bc`,
           ADD `reflect_review_count` INT(3)  DEFAULT 0 AFTER `reflect_text`,
        ADD `explore_text` VARCHAR(3000) AFTER `reflect_review_count`,
         ADD `explore_review_count` INT(3) AFTER `explore_text`,
        ADD `connect_text` VARCHAR(3000) AFTER `explore_review_count`,
         ADD `connect_review_count` INT(3) AFTER `connect_text`,
        ADD `society_text` VARCHAR(3000) AFTER `connect_review_count`,
         ADD `society_review_count` INT(3) AFTER `society_text`;
       




     ALTER TABLE Activity 
		  ADD `wants_ec` INT(4) AFTER `ec_elgible_flag`;   
         
      
     ALTER TABLE Activity 
		  ADD `switch_to_bc` INT(2) AFTER `wcount_j`;    
         
   


   ALTER TABLE Activity
      ADD `reflect_review_count` INT(3) DEFAULT 0 AFTER `reflect_text`,
       ADD `explore_review_count` INT(3) DEFAULT 0 AFTER `explore_text`,
        ADD `connect_review_count` INT(3) DEFAULT 0 AFTER `connect_text`,
       ADD `society_review_count` INT(3) DEFAULT 0 AFTER `society_text`;
    

  CREATE TABLE IF NOT EXISTS `Rating` (
  `rating_id` INT(11) NOT NULL AUTO_INCREMENT,
  `activity_id` INT(11) NOT NULL,
   `assign_id` INT(11) NOT NULL,
    `refl_type` VARCHAR(20) NOT NULL,
   `rator_student_id` INT(11) NOT NULL,
    `ratee_student_id` INT(11) NOT NULL,
	`rating` INT(2) NOT NULL,
    `ranking` INT(3),
     `ranking_out_of` INT(3),
     `exp_date` DATETIME,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rating_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;     


 ALTER TABLE Assign
      ADD `reflect_pr_flag` INT(3) DEFAULT 0 AFTER `society_flag`,
       ADD `explore_pr_flag` INT(3) DEFAULT 0 AFTER `reflect_pr_flag`,
        ADD `connect_pr_flag` INT(3) DEFAULT 0 AFTER `explore_pr_flag`,
       ADD `society_pr_flag` INT(3) DEFAULT 0 AFTER `connect_pr_flag`;

 
CREATE TABLE Assignscore (
	`assignscore_id` INT(11) NOT NULL AUTO_INCREMENT,
	`student_id` INT(11),
	`assigntime_id` INT(11),
    `qr_tot` INT(3),
	`other_pblm` INT(3),
	`assign_ec` INT(3),
	`assign_tot` INT(3),
	CONSTRAINT FOREIGN KEY (`student_id`) REFERENCES Student (`student_id`),
	PRIMARY KEY (`assignscore_id`),
    UNIQUE KEY (`student_id`,`assigntime_id`)
) ENGINE=InnoDB CHARACTER SET = utf8;

   
  ALTER TABLE Activity
      ADD `fb_p_num_score_net` INT(3) AFTER `society_pts`,
      ADD `fb_probtot_pts` INT(3) AFTER `fb_p_num_score_net`,
      ADD `fb_reflect` VARCHAR(1000) AFTER `fb_probtot_pts`,
      ADD `fb_explore` VARCHAR(1000) AFTER `fb_reflect`,
      ADD `fb_connect` VARCHAR(1000) AFTER `fb_explore`,
      ADD `fb_society` VARCHAR(1000) AFTER `fb_connect`;
  

  
   ALTER TABLE Activity
      ADD `fb_problem` VARCHAR(1000) AFTER `fb_society`; 
   
    
   ALTER TABLE Examtime
      ADD `ans_n` INT(3) AFTER `num_attempts`, 
      ADD `ans_t` INT(3) AFTER `ans_n`; 
   
   ALTER TABLE Examactivity
      ADD `display_ans_pblm1` VARCHAR(64) AFTER `response_pblm5`, 
      ADD `display_ans_pblm2` VARCHAR(64) AFTER `display_ans_pblm1`, 
      ADD `display_ans_pblm3` VARCHAR(64) AFTER `display_ans_pblm2`, 
      ADD `display_ans_pblm4` VARCHAR(64) AFTER `display_ans_pblm3`, 
      ADD `display_ans_pblm5` VARCHAR(64) AFTER `display_ans_pblm4`;
   
CREATE TABLE IF NOT EXISTS `Eexamnow` (  -- this has the current 
	`eexamnow_id` int(11) NOT NULL AUTO_INCREMENT,
    `globephase` INT(2) NOT NULL DEFAULT 0,
    `eexamtime_id` int(11),
    `exam_code` int(11), 
    `end_of_phase`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
   `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY ( `eexamtime_id` ) REFERENCES Eexamtime (`eexamtime_id`) ON DELETE CASCADE ON UPDATE CASCADE,
		PRIMARY KEY (`eexamnow_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;     


             
   CREATE TABLE IF NOT EXISTS `Eexamtime` (
		`eexamtime_id` int(11) NOT NULL AUTO_INCREMENT,
		`exam_num` int(11) NOT NULL,
		`iid` int(11) NOT NULL,
        `currentclass_id` int(11) NOT NULL,
         `work_flow` VARCHAR(10),
         `nom_time` INT(6),
         `attempt_type` INT(2),
         `game_flag` INT(2),
         `number_teams` INT(3),
         `num_attempts` INT(4),
         `ans_n` INT(3),
         `ans_t` INT(3),
        `work_time` INT(4),
         `bc_ans_n` INT(4) ,
         `bc_ans_t` INT(6),
         `p_bc_n` INT(4),
         `p_bc_t` INT(6),
         `help_n_stu` INT(4),
         `help_t_stu` INT(4),
         `help_n_ta` INT(4),
         `help_t_ta` INT(4),         
         `help_n_instruct` INT(4),
         `help_t_instruct` INT(4),   
        `work_time_per_problem` INT(4),
          `max_attempts_per_problem` INT(3),
          `window_opens` DATETIME,
           `due_date` DATETIME,
         `window_closes`  DATETIME,
         `credit` VARCHAR(20),
         `late_points` VARCHAR(30),
         `fixed_percent_decline` INT(4),
         `perc_ec_max_p_assign` INT(3),
         `perc_ec_max_p_pblm` INT(3),
         `perc_ec_max_person_to_person` INT(3),
         `ec_daysb4due_elgible` INT(3),
         `perc_ec_base_video` INT(3),
         `perc_ec_base_audio` INT(3),
         `perc_ec_base_written` INT(3),
         `peer_refl_t` INT(3),
         `peer_refl_n` INT(3),
         `perc_1` INT(3),
         `perc_a_1` INT(3),
         `perc_b_1` INT(3),
         `perc_c_1` INT(3),
         `perc_d_1` INT(3),
         `perc_e_1` INT(3),
         `perc_f_1` INT(3),
         `perc_g_1` INT(3),
         `perc_h_1` INT(3),
         `perc_i_1` INT(3),
         `perc_j_1` INT(3),
         `perc_pp1_1` INT(3),
         `perc_pp2_1` INT(3),
         `perc_pp3_1` INT(3),
         `perc_ref_1` INT(3),
         `perc_exp_1` INT(3),
         `perc_con_1` INT(3),
         `perc_soc_1` INT(3),
         `perc_any1_ref_1` INT(3),
         `perc_any2_ref_1` INT(3),
         `perc_any3_ref_1` INT(3),
         `survey_1` INT(3),
         `perc_2` INT(3),
         `perc_a_2` INT(3),
         `perc_b_2` INT(3),
         `perc_c_2` INT(3),
         `perc_d_2` INT(3),
         `perc_e_2` INT(3),
         `perc_f_2` INT(3),
         `perc_g_2` INT(3),
         `perc_h_2` INT(3),
         `perc_i_2` INT(3),
         `perc_j_2` INT(3),
         `perc_pp1_2` INT(3),
         `perc_pp2_2` INT(3),
         `perc_pp3_2` INT(3),
         `perc_ref_2` INT(3),
         `perc_exp_2` INT(3),
         `perc_con_2` INT(3),
         `perc_soc_2` INT(3),
         `perc_any1_ref_2` INT(3),
         `perc_any2_ref_2` INT(3),
         `perc_any3_ref_2` INT(3),      
         `survey_2` INT(3),
         `perc_3` INT(3),
         `perc_a_3` INT(3),
         `perc_b_3` INT(3),
         `perc_c_3` INT(3),
         `perc_d_3` INT(3),
         `perc_e_3` INT(3),
         `perc_f_3` INT(3),
         `perc_g_3` INT(3),
         `perc_h_3` INT(3),
         `perc_i_3` INT(3),
         `perc_j_3` INT(3),
         `perc_pp1_3` INT(3),
         `perc_pp2_3` INT(3),
         `perc_pp3_3` INT(3),
         `perc_ref_3` INT(3),
         `perc_exp_3` INT(3),
         `perc_con_3` INT(3),
         `perc_soc_3` INT(3),
         `perc_any1_ref_3` INT(3),
         `perc_any2_ref_3` INT(3),
         `perc_any3_ref_3` INT(3), 
         `survey_3` INT(3),
         `perc_4` INT(3),
         `perc_a_4` INT(3),
         `perc_b_4` INT(3),
         `perc_c_4` INT(3),
         `perc_d_4` INT(3),
         `perc_e_4` INT(3),
         `perc_f_4` INT(3),
         `perc_g_4` INT(3),
         `perc_h_4` INT(3),
         `perc_i_4` INT(3),
         `perc_j_4` INT(3),
         `perc_pp1_4` INT(3),
         `perc_pp2_4` INT(3),
         `perc_pp3_4` INT(3),
         `perc_ref_4` INT(3),
         `perc_exp_4` INT(3),
         `perc_con_4` INT(3),
         `perc_soc_4` INT(3),
         `perc_any1_ref_4` INT(3),
         `perc_any2_ref_4` INT(3),
         `perc_any3_ref_4` INT(3),
         `survey_4` INT(3),
         `perc_5` INT(3),
         `perc_a_5` INT(3),
         `perc_b_5` INT(3),
         `perc_c_5` INT(3),
         `perc_d_5` INT(3),
         `perc_e_5` INT(3),
         `perc_f_5` INT(3),
         `perc_g_5` INT(3),
         `perc_h_5` INT(3),
         `perc_i_5` INT(3),
         `perc_j_5` INT(3),
         `perc_pp1_5` INT(3),
         `perc_pp2_5` INT(3),
         `perc_pp3_5` INT(3),
         `perc_ref_5` INT(3),
         `perc_exp_5` INT(3),
         `perc_con_5` INT(3),
         `perc_soc_5` INT(3),
         `survey_5` INT(3),
         `perc_any1_ref_5` INT(3),
         `perc_any2_ref_5` INT(3),
         `perc_any3_ref_5` INT(3),     
         `perc_6` INT(3),
         `perc_a_6` INT(3),
         `perc_b_6` INT(3),
         `perc_c_6` INT(3),
         `perc_d_6` INT(3),
         `perc_e_6` INT(3),
         `perc_f_6` INT(3),
         `perc_g_6` INT(3),
         `perc_h_6` INT(3),
         `perc_i_6` INT(3),
         `perc_j_6` INT(3),
         `perc_pp1_6` INT(3),
         `perc_pp2_6` INT(3),
         `perc_pp3_6` INT(3),
         `perc_ref_6` INT(3),
         `perc_exp_6` INT(3),
         `perc_con_6` INT(3),
         `perc_soc_6` INT(3),
         `perc_any1_ref_6` INT(3),
         `perc_any2_ref_6` INT(3),
         `perc_any3_ref_6` INT(3), 
         `survey_6` INT(3),         
         `perc_7` INT(3),
         `perc_a_7` INT(3),
         `perc_b_7` INT(3),
         `perc_c_7` INT(3),
         `perc_d_7` INT(3),
         `perc_e_7` INT(3),
         `perc_f_7` INT(3),
         `perc_g_7` INT(3),
         `perc_h_7` INT(3),
         `perc_i_7` INT(3),
         `perc_j_7` INT(3),
         `perc_pp1_7` INT(3),
         `perc_pp2_7` INT(3),
         `perc_pp3_7` INT(3),
         `perc_ref_7` INT(3),
         `perc_exp_7` INT(3),
         `perc_con_7` INT(3),
         `perc_soc_7` INT(3),
         `perc_any1_ref_7` INT(3),
         `perc_any2_ref_7` INT(3),
         `perc_any3_ref_7` INT(3), 
         `survey_7` INT(3),         
         `perc_8` INT(3),
         `perc_a_8` INT(3),
         `perc_b_8` INT(3),
         `perc_c_8` INT(3),
         `perc_d_8` INT(3),
         `perc_e_8` INT(3),
         `perc_f_8` INT(3),
         `perc_g_8` INT(3),
         `perc_h_8` INT(3),
         `perc_i_8` INT(3),
         `perc_j_8` INT(3),
         `perc_pp1_8` INT(3),
         `perc_pp2_8` INT(3),
         `perc_pp3_8` INT(3),
         `perc_ref_8` INT(3),
         `perc_exp_8` INT(3),
         `perc_con_8` INT(3),
         `perc_soc_8` INT(3),
         `perc_any1_ref_8` INT(3),
         `perc_any2_ref_8` INT(3),
         `perc_any3_ref_8` INT(3), 
         `survey_8` INT(3),         
         `perc_9` INT(3),
         `perc_a_9` INT(3),
         `perc_b_9` INT(3),
         `perc_c_9` INT(3),
         `perc_d_9` INT(3),
         `perc_e_9` INT(3),
         `perc_f_9` INT(3),
         `perc_g_9` INT(3),
         `perc_h_9` INT(3),
         `perc_i_9` INT(3),
         `perc_j_9` INT(3),
         `perc_pp1_9` INT(3),
         `perc_pp2_9` INT(3),
         `perc_pp3_9` INT(3),
         `perc_ref_9` INT(3),
         `perc_exp_9` INT(3),
         `perc_con_9` INT(3),
         `perc_soc_9` INT(3),
         `perc_any1_ref_9` INT(3),
         `perc_any2_ref_9` INT(3),
         `perc_any3_ref_9` INT(3),
         `survey_9` INT(3),         
         `perc_10` INT(3),
         `perc_a_10` INT(3),
         `perc_b_10` INT(3),
         `perc_c_10` INT(3),
         `perc_d_10` INT(3),
         `perc_e_10` INT(3),
         `perc_f_10` INT(3),
         `perc_g_10` INT(3),
         `perc_h_10` INT(3),
         `perc_i_10` INT(3),
         `perc_j_10` INT(3),
         `perc_pp1_10` INT(3),
         `perc_pp2_10` INT(3),
         `perc_pp3_10` INT(3),
         `perc_ref_10` INT(3),
         `perc_exp_10` INT(3),
         `perc_con_10` INT(3),
         `perc_soc_10` INT(3),
         `perc_any1_ref_10` INT(3),
         `perc_any2_ref_10` INT(3),
         `perc_any3_ref_10` INT(3),  
         `survey_10` INT(3),         
         `perc_11` INT(3),
         `perc_a_11` INT(3),
         `perc_b_11` INT(3),
         `perc_c_11` INT(3),
         `perc_d_11` INT(3),
         `perc_e_11` INT(3),
         `perc_f_11` INT(3),
         `perc_g_11` INT(3),
         `perc_h_11` INT(3),
         `perc_i_11` INT(3),
         `perc_j_11` INT(3),
         `perc_pp1_11` INT(3),
         `perc_pp2_11` INT(3),
         `perc_pp3_11` INT(3),
         `perc_ref_11` INT(3),
         `perc_exp_11` INT(3),
         `perc_con_11` INT(3),
         `perc_soc_11` INT(3),
         `perc_any1_ref_11` INT(3),
         `perc_any2_ref_11` INT(3),
         `perc_any3_ref_11` INT(3),
         `survey_11` INT(3),         
         `perc_12` INT(3),
         `perc_a_12` INT(3),
         `perc_b_12` INT(3),
         `perc_c_12` INT(3),
         `perc_d_12` INT(3),
         `perc_e_12` INT(3),
         `perc_f_12` INT(3),
         `perc_g_12` INT(3),
         `perc_h_12` INT(3),
         `perc_i_12` INT(3),
         `perc_j_12` INT(3),
         `perc_pp1_12` INT(3),
         `perc_pp2_12` INT(3),
         `perc_pp3_12` INT(3),
         `perc_ref_12` INT(3),
         `perc_exp_12` INT(3),
         `perc_con_12` INT(3),
         `perc_soc_12` INT(3),
         `perc_any1_ref_12` INT(3),
         `perc_any2_ref_12` INT(3),
         `perc_any3_ref_12` INT(3),  
         `survey_12` INT(3),         
         `perc_13` INT(3),
         `perc_a_13` INT(3),
         `perc_b_13` INT(3),
         `perc_c_13` INT(3),
         `perc_d_13` INT(3),
         `perc_e_13` INT(3),
         `perc_f_13` INT(3),
         `perc_g_13` INT(3),
         `perc_h_13` INT(3),
         `perc_i_13` INT(3),
         `perc_j_13` INT(3),
         `perc_pp1_13` INT(3),
         `perc_pp2_13` INT(3),
         `perc_pp3_13` INT(3),
         `perc_ref_13` INT(3),
         `perc_exp_13` INT(3),
         `perc_con_13` INT(3),
         `perc_soc_13` INT(3),
         `perc_any1_ref_13` INT(3),
         `perc_any2_ref_13` INT(3),
         `perc_any3_ref_13` INT(3),
         `survey_13` INT(3),         
         `perc_14` INT(3),
         `perc_a_14` INT(3),
         `perc_b_14` INT(3),
         `perc_c_14` INT(3),
         `perc_d_14` INT(3),
         `perc_e_14` INT(3),
         `perc_f_14` INT(3),
         `perc_g_14` INT(3),
         `perc_h_14` INT(3),
         `perc_i_14` INT(3),
         `perc_j_14` INT(3),
         `perc_pp1_14` INT(3),
         `perc_pp2_14` INT(3),
         `perc_pp3_14` INT(3),
         `perc_ref_14` INT(3),
         `perc_exp_14` INT(3),
         `perc_con_14` INT(3),
         `perc_soc_14` INT(3),
         `perc_any1_ref_14` INT(3),
         `perc_any2_ref_14` INT(3),
         `perc_any3_ref_14` INT(3),
         `survey_14` INT(3),         
         `perc_15` INT(3),
         `perc_a_15` INT(3),
         `perc_b_15` INT(3),
         `perc_c_15` INT(3),
         `perc_d_15` INT(3),
         `perc_e_15` INT(3),
         `perc_f_15` INT(3),
         `perc_g_15` INT(3),
         `perc_h_15` INT(3),
         `perc_i_15` INT(3),
         `perc_j_15` INT(3),
         `perc_pp1_15` INT(3),
         `perc_pp2_15` INT(3),
         `perc_pp3_15` INT(3),
         `perc_ref_15` INT(3),
         `perc_exp_15` INT(3),
         `perc_con_15` INT(3),
         `perc_soc_15` INT(3),
         `perc_any1_ref_15` INT(3),
         `perc_any2_ref_15` INT(3),
         `perc_any3_ref_15` INT(3), 
         `survey_15` INT(3),         
         `perc_16` INT(3),
         `perc_a_16` INT(3),
         `perc_b_16` INT(3),
         `perc_c_16` INT(3),
         `perc_d_16` INT(3),
         `perc_e_16` INT(3),
         `perc_f_16` INT(3),
         `perc_g_16` INT(3),
         `perc_h_16` INT(3),
         `perc_i_16` INT(3),
         `perc_j_16` INT(3),
         `perc_pp1_16` INT(3),
         `perc_pp2_16` INT(3),
         `perc_pp3_16` INT(3),
         `perc_ref_16` INT(3),
         `perc_exp_16` INT(3),
         `perc_con_16` INT(3),
         `perc_soc_16` INT(3),
         `perc_any1_ref_16` INT(3),
         `perc_any2_ref_16` INT(3),
         `perc_any3_ref_16` INT(3), 
         `survey_16` INT(3),         
         `perc_17` INT(3),
         `perc_a_17` INT(3),
         `perc_b_17` INT(3),
         `perc_c_17` INT(3),
         `perc_d_17` INT(3),
         `perc_e_17` INT(3),
         `perc_f_17` INT(3),
         `perc_g_17` INT(3),
         `perc_h_17` INT(3),
         `perc_i_17` INT(3),
         `perc_j_17` INT(3),
         `perc_pp1_17` INT(3),
         `perc_pp2_17` INT(3),
         `perc_pp3_17` INT(3),
         `perc_ref_17` INT(3),
         `perc_exp_17` INT(3),
         `perc_con_17` INT(3),
         `perc_soc_17` INT(3),
         `perc_any1_ref_17` INT(3),
         `perc_any2_ref_17` INT(3),
         `perc_any3_ref_17` INT(3), 
         `survey_17` INT(3),         
         `perc_18` INT(3),
         `perc_a_18` INT(3),
         `perc_b_18` INT(3),
         `perc_c_18` INT(3),
         `perc_d_18` INT(3),
         `perc_e_18` INT(3),
         `perc_f_18` INT(3),
         `perc_g_18` INT(3),
         `perc_h_18` INT(3),
         `perc_i_18` INT(3),
         `perc_j_18` INT(3),
         `perc_pp1_18` INT(3),
         `perc_pp2_18` INT(3),
         `perc_pp3_18` INT(3),
         `perc_ref_18` INT(3),
         `perc_exp_18` INT(3),
         `perc_con_18` INT(3),
         `perc_soc_18` INT(3),
         `perc_any1_ref_18` INT(3),
         `perc_any2_ref_18` INT(3),
         `perc_any3_ref_18` INT(3),
         `survey_18` INT(3),         
         `perc_19` INT(3),
         `perc_a_19` INT(3),
         `perc_b_19` INT(3),
         `perc_c_19` INT(3),
         `perc_d_19` INT(3),
         `perc_e_19` INT(3),
         `perc_f_19` INT(3),
         `perc_g_19` INT(3),
         `perc_h_19` INT(3),
         `perc_i_19` INT(3),
         `perc_j_19` INT(3),
         `perc_pp1_19` INT(3),
         `perc_pp2_19` INT(3),
         `perc_pp3_19` INT(3),
         `perc_ref_19` INT(3),
         `perc_exp_19` INT(3),
         `perc_con_19` INT(3),
         `perc_soc_19` INT(3),
         `perc_any1_ref_19` INT(3),
         `perc_any2_ref_19` INT(3),
         `perc_any3_ref_19` INT(3), 
         `survey_19` INT(3),         
         `perc_20` INT(3),
         `perc_a_20` INT(3),
         `perc_b_20` INT(3),
         `perc_c_20` INT(3),
         `perc_d_20` INT(3),
         `perc_e_20` INT(3),
         `perc_f_20` INT(3),
         `perc_g_20` INT(3),
         `perc_h_20` INT(3),
         `perc_i_20` INT(3),
         `perc_j_20` INT(3),
         `perc_pp1_20` INT(3),
         `perc_pp2_20` INT(3),
         `perc_pp3_20` INT(3),
         `perc_ref_20` INT(3),
         `perc_exp_20` INT(3),
         `perc_con_20` INT(3),
         `perc_soc_20` INT(3),
         `perc_any1_ref_20` INT(3),
         `perc_any2_ref_20` INT(3),
         `perc_any3_ref_20` INT(3),
         `survey_20` INT(3),         
         `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`eexamtime_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;     

  CREATE TABLE Eexamscore (
	`eexamscore_id` INT(11) NOT NULL AUTO_INCREMENT,
	`student_id` INT(11),
	`eexamtime_id` INT(11),
    `qr_tot` INT(3),
	`other_pblm` INT(3),
	`exam_ec` INT(3),
	`exam_tot` INT(3),
	CONSTRAINT FOREIGN KEY (`student_id`) REFERENCES Student (`student_id`)  ON UPDATE CASCADE,
  CONSTRAINT FOREIGN KEY (`eexamtime_id`) REFERENCES Eexamtime (`eexamtime_id`)  ON UPDATE CASCADE,
	PRIMARY KEY (`eexamscore_id`),
    UNIQUE KEY (`student_id`,`eexamtime_id`)
) ENGINE=InnoDB CHARACTER SET = utf8;  


CREATE TABLE IF NOT EXISTS `Eexam` (
  `eexam_id` int(11) NOT NULL AUTO_INCREMENT,
  `instr_last` varchar(50) NOT NULL,
   `iid` int(11) NOT NULL,
   `university` varchar(255) NOT NULL,
    exam_num int,
   `alias_num` INT,  
   currentclass_id INT,
   problem_id int,
   proctor_id1 int,
   proctor_id2 int,
   proctor_id3 int,
	reflect_flag int,
	explore_flag int,
	connect_flag int,
	society_flag int,
	 postp_flag1 int,
	 postp_flag2 int,
	 postp_flag3 int,
   grader_id1 INT, 
	 grader_id2 INT, 
	 grader_id3 INT,
   `exp_date` DATETIME,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY ( `currentclass_id` ) REFERENCES CurrentClass (`currentclass_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`eexam_id`)
  );


CREATE TABLE IF NOT EXISTS Eactivity (
  eactivity_id int(11) NOT NULL AUTO_INCREMENT,
	problem_id int,
	eregistration_id INT NOT NULL,
  eexamnow_id INT NOT NULL,
   `currentclass_id` INT,
   `student_id` INT,
   `alias_num` INT,
  `bc_correct_a` int(2),
   `bc_correct_b` int(2),
   `bc_correct_c` int(2),
   `bc_correct_d` int(2),
   `bc_correct_e` int(2),
   `bc_correct_f` int(2),
   `bc_correct_g` int(2),
   `bc_correct_h` int(2),
   `bc_correct_i` int(2),
   `bc_correct_j` int(2),
   `correct_a` int(2),
   `correct_b` int(2),
   `correct_c` int(2),
   `correct_d` int(2),
   `correct_e` int(2),
   `correct_f` int(2),
   `correct_g` int(2),
   `correct_h` int(2),
   `correct_i` int(2),
   `correct_j` int(2),
     score int,
     p_num_score_raw INT(4),
     late_penalty INT(4),
     P_num_score_net INT(4),
     P_num_score_net_bc INT(4),
     survey_pts INT(4),
     ec_pts INT(4),
     reflect_pts INT(4),
     explore_pts INT(4),
     connect_pts INT(4),
     society_pts INT(4),
     fb_p_num_score_net INT(4),
     fb_probtot_pts INT(4),
     fb_reflect VARCHAR(1000),
     fb_explore VARCHAR(1000),
     fb_connect VARCHAR(1000),
     fb_society VARCHAR(1000),
     fb_problem VARCHAR(1000),
     count_tot INT(11),
     wcount_bc_a INT(4),
     wcount_bc_b INT(4),
     wcount_bc_c INT(4),
     wcount_bc_d INT(4),
     wcount_bc_e INT(4),
     wcount_bc_f INT(4),
     wcount_bc_g INT(4),
     wcount_bc_h INT(4),
     wcount_bc_i INT(4),
     wcount_bc_j INT(4),
     wcount_a INT(4),
     wcount_b INT(4),
     wcount_c INT(4),
     wcount_d INT(4),
     wcount_e INT(4),
     wcount_f INT(4),
     wcount_g INT(4),
     wcount_h INT(4),
     wcount_i INT(4),
     wcount_j INT(4),
     display_ans_a int(1) DEFAULT 0,
     display_ans_b int(1) DEFAULT 0,
     display_ans_c int(1) DEFAULT 0,
     display_ans_d int(1) DEFAULT 0,
     display_ans_e int(1) DEFAULT 0,
     display_ans_f int(1) DEFAULT 0,
     display_ans_g int(1) DEFAULT 0,
     display_ans_h int(1) DEFAULT 0,
     display_ans_i int(1) DEFAULT 0,
     display_ans_j int(1) DEFAULT 0,
     display_bc_ans_a int(1) DEFAULT 0,
     display_bc_ans_b int(1) DEFAULT 0,
     display_bc_ans_c int(1) DEFAULT 0,
     display_bc_ans_d int(1) DEFAULT 0,
     display_bc_ans_e int(1) DEFAULT 0,
     display_bc_ans_f int(1) DEFAULT 0,
     display_bc_ans_g int(1) DEFAULT 0,
     display_bc_ans_h int(1) DEFAULT 0,
     display_bc_ans_i int(1) DEFAULT 0,
     display_bc_ans_j int(1) DEFAULT 0,
     switch_to_bc INT(4),
     reflect_text VARCHAR(3000),
     explore_text VARCHAR(3000),
     connect_text VARCHAR(3000),
     society_text VARCHAR(3000),
     reflect_review_count INT(3),
     explore_review_count INT(3),
     connect_review_count INT(3),
     society_review_count INT(3),
     progress INT(2) DEFAULT 0,
     num_try INT(5),
   `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`eactivity_id`))
ENGINE=InnoDB CHARACTER SET = utf8;

  CREATE TABLE IF NOT EXISTS `Eregistration` (
		`eregistration_id` INT(11) NOT NULL AUTO_INCREMENT,
    `eexamnow_id` INT(11) NOT NULL, 
		`student_id` INT (11) NOT NULL,
    `dex` INT(3),
    `exam_code` INT(8),
    `checker_only` INT(1),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT FOREIGN KEY (`student_id`) REFERENCES `Student`(`student_id`),
  CONSTRAINT FOREIGN KEY (`eexamnow_id`) REFERENCES `Eexamnow`(eexamnow_id),
	PRIMARY KEY (`eregistration_id`))
    ENGINE=InnoDB DEFAULT CHARSET=utf8;   


        CREATE TABLE IF NOT EXISTS `Eresp` (
		`eresp_id` int(11) NOT NULL AUTO_INCREMENT,
        `eactivity_id` int(11) NOT NULL, 
		`resp_value` DOUBLE NOT NULL,
        `part_name` VARCHAR(2),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`eresp_id`),
        CONSTRAINT FOREIGN KEY (`eactivity_id`) REFERENCES `Eactivity` (`eactivity_id`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;   


     CREATE TABLE IF NOT EXISTS `Ebc_resp` (
		`ebc_resp_id` int(11) NOT NULL AUTO_INCREMENT,
        `eactivity_id` int(11) NOT NULL, 
		`resp_value` DOUBLE NOT NULL,
        `part_name` VARCHAR(2),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`ebc_resp_id`),
        CONSTRAINT FOREIGN KEY (`eactivity_id`) REFERENCES `Eactivity` (`eactivity_id`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;   


     CREATE TABLE IF NOT EXISTS `Team` (
		`team_id` INT(11) NOT NULL AUTO_INCREMENT,
        `team_num` INT(3) NOT NULL, 
        `team_name` VARCHAR(50),
        `currentclass_id`  INT(3), 
        `eexamnow_id`  INT(3), 
        `team_score`  INT(3), 
        `team_range`  INT(3), 
        `team_sd`  INT(3), 
        `team_cohesivity_avg`  INT(3), 
        `team_cohesivity_inst`  INT(3), 
         `counter`  INT(11) DEFAULT 0, 
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	    	PRIMARY KEY (`team_id`),
        CONSTRAINT FOREIGN KEY (`currentclass_id`) REFERENCES `CurrentClass` (`currentclass_id`),
        CONSTRAINT FOREIGN KEY (`eexamnow_id`) REFERENCES `Eexamnow` (`eexamnow_id`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;   


    CREATE TABLE IF NOT EXISTS TeamStudentConnect (
      `student_id` INT(11) NOT NULL,
      `eexamnow_id` INT(11) NOT NULL,
      `team_id` INT(11) NOT NULL,
      `team_num` INT(3) NOT NULL, 
      `dex` INT(4) NOT NULL,
      `team_cap` INT(2) DEFAULT 0,
      CONSTRAINT FOREIGN KEY (team_id) REFERENCES Team (team_id),
      CONSTRAINT FOREIGN KEY (student_id) REFERENCES Student (student_id),
      CONSTRAINT FOREIGN KEY (eexamnow_id) REFERENCES Eexamnow (eexamnow_id),
      PRIMARY KEY (`student_id`,`eexamnow_id`)
    ) ENGINE=InnoDB CHARACTER SET = utf8;    
    


    
     CREATE TABLE IF NOT EXISTS `GameAction` ( /*  these are the action cards for the board game of this*/
		`gameaction_id` INT(11) NOT NULL AUTO_INCREMENT,
        `game_action_title` VARCHAR(50) NOT NULL, 
        `action_image_file`  VARCHAR(50), 
        `action_html_file`  VARCHAR(50), 
        `action_video_file`  VARCHAR(50), 
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
         CONSTRAINT UNIQUE (game_action_title),
	    	PRIMARY KEY (`gameaction_id`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;   
       
       
         CREATE TABLE IF NOT EXISTS `GameChaos` (
	    	`gamechaos_id` INT(11) NOT NULL AUTO_INCREMENT,
        `game_chaos_title` VARCHAR(50) NOT NULL, 
        `chaos_image_file`  VARCHAR(50), 
        `chaos_html_file`  VARCHAR(50), 
        `chaos_video_file`  VARCHAR(50), 
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT UNIQUE (game_chaos_title),
	    	PRIMARY KEY (`gamechaos_id`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;   

         CREATE TABLE IF NOT EXISTS `GamePolitical` (
	    	`gamepolitical_id` INT(11) NOT NULL AUTO_INCREMENT,
        `game_political_title` VARCHAR(50) NOT NULL, 
        `fin_wt`  INT(5), 
        `env_wt`  INT(5), 
        `soc_wt`  INT(5), 
        `political_image_file`  VARCHAR(50), 
        `political_html_file`  VARCHAR(50), 
        `political_video_file`  VARCHAR(50), 
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	    	PRIMARY KEY (`gamepolitical_id`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;   



         CREATE TABLE IF NOT EXISTS `GameBoard` (
	    	`gameboard_id` INT(11) NOT NULL AUTO_INCREMENT,
        `game_board_title` VARCHAR(50) NOT NULL, /* e.g. QRpropylene  or QRtown  */
        `board_catagory` VARCHAR(20) NOT NULL,   /* e.g. for material balances or  peralgebra class  */
        `board_image_file`  VARCHAR(50), 
        `board_html_file`  VARCHAR(50), 
        `board_video_file`  VARCHAR(50), 
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	    	PRIMARY KEY (`gameboard_id`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;   

      CREATE TABLE IF NOT EXISTS GameBoardGameActionConnect (
            `gameboardgameactionconnect_id` INT(11) NOT NULL AUTO_INCREMENT,
            `gameaction_id` INT(11) NOT NULL,
            `gameboard_id` INT(11) NOT NULL,
             `cost` INT(6) NOT NULL, 
            `fin_benefit` int(6),
            `env_benefit`  INT(6), 
            `soc_benefit`  INT(6), 
            `fin_block`  INT(6), 
            `env_block`  INT(6), 
            `soc_block`  INT(6), 
            `max_select`  INT(3), 
            CONSTRAINT FOREIGN KEY (`gameaction_id`) REFERENCES GameAction (gameaction_id),
            CONSTRAINT FOREIGN KEY (`gameboard_id`) REFERENCES GameBoard ( `gameboard_id`),
            CONSTRAINT UNIQUE (gameaction_id,gameboard_id),
            PRIMARY KEY (gameboardgameactionconnect_id)
          ) ENGINE=InnoDB CHARACTER SET = utf8;    

      CREATE TABLE IF NOT EXISTS GameBoardGameChaosConnect (
            `gameboardgamechaosconnect_id` INT(11) NOT NULL AUTO_INCREMENT,
            `gamechaos_id` INT(11) NOT NULL,
            `gameboard_id` INT(11) NOT NULL,
           `chaos_main_effect` VARCHAR(20) NOT NULL, 
            `cost` int(6),
            `fin_hit`  INT(5), 
            `env_hit`  INT(5), 
            `soc_hit`  INT(5), 
             `max_select`  INT(3) DEFAULT 1, -- maximum number of times card can be selected in a round
            CONSTRAINT FOREIGN KEY (`gamechaos_id`) REFERENCES GameChaos (gamechaos_id),
            CONSTRAINT FOREIGN KEY (`gameboard_id`) REFERENCES GameBoard ( `gameboard_id`),
            CONSTRAINT UNIQUE (gamechaos_id,gameboard_id),
            PRIMARY KEY (gameboardgamechaosconnect_id)
          ) ENGINE=InnoDB CHARACTER SET = utf8;    



  -- CREATE TABLE IF NOT EXISTS GameActionGameDevelopmentConnect (
  --   	`gameactiongamedevelopmentconnect_id` INT(11) NOT NULL AUTO_INCREMENT,
  --     `gameaction_id` INT(11) NOT NULL,
  --     `gamedevelopment_id` INT(11) NOT NULL,
  --     `gameboard_id` INT(11) NOT NULL,
  --     `blocking_effect` INT(11) DEFAULT 0,
  --     CONSTRAINT FOREIGN KEY (`gameaction_id`) REFERENCES GameAction (gameaction_id),
  --     CONSTRAINT FOREIGN KEY (`gamedevelopment_id`) REFERENCES GameDevelopment ( `gamedevelopment_id`),
  --     CONSTRAINT FOREIGN KEY (`gameboard_id`) REFERENCES GameBoard ( `gameboard_id`),
  --     CONSTRAINT UNIQUE (gamedevelopment_id,gameaction_id,gameboard_id),
  --     PRIMARY KEY (gameactiongamedevelopmentconnect_id)
  --   ) ENGINE=InnoDB CHARACTER SET = utf8;    


    --  CREATE TABLE IF NOT EXISTS `GameBoardSetup` ( 
		-- `gameboardsetup_id` INT(11) NOT NULL AUTO_INCREMENT,
    --     `gameboard_id` INT(11) NOT NULL, 
    --     `round` INT(3) NOT NULL, 
    --     `num_concept_questions` INT(3),
    --     `max_concept_points`  INT(5), 
    --     `concept_weighting`  INT(3), 
    --     `quant_weighting`  INT(3),  
    --     `reflection_weighting`INT(3), 
    --     `percent_to_know_point` INT(3), 
    --     `know_to_capacity` INT(3), 
    --     `max_cap_inc` INT(3), 
    --     `development_intesity_factor` INT(3), 
    --     `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    --     `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  --   	PRIMARY KEY (`gameboardsetup_id`))
    --     ENGINE=InnoDB DEFAULT CHARSET=utf8;   


  ALTER TABLE `Problem` 
	ADD `tol_a_type` INT (2) DEFAULT 0 AFTER `tol_j`, 
	ADD `tol_b_type` INT (2)  DEFAULT 0  AFTER `tol_a_type`, 
	ADD `tol_c_type` INT (2)  DEFAULT 0  AFTER `tol_b_type`, 
	ADD `tol_d_type` INT (2)  DEFAULT 0  AFTER `tol_c_type`, 
	ADD `tol_e_type` INT (2)  DEFAULT 0  AFTER `tol_d_type`, 
	ADD `tol_f_type` INT (2)  DEFAULT 0  AFTER `tol_e_type`, 
	ADD `tol_g_type` INT (2)  DEFAULT 0  AFTER `tol_f_type`, 
	ADD `tol_h_type` INT (2)  DEFAULT 0  AFTER `tol_g_type`, 
	ADD `tol_i_type` INT (2)  DEFAULT 0  AFTER `tol_h_type`, 
	ADD `tol_j_type` INT (2)  DEFAULT 0  AFTER `tol_i_type`


  ALTER TABLE `Problem` 
	ADD `videonm1` VARCHAR(128) AFTER `htmlfilenm`, 
	ADD `videonm2` VARCHAR(128) AFTER `videonm1`, 
	ADD `videonm3` VARCHAR(128) AFTER `videonm2`, 
	ADD `problem_type` int(2) AFTER `videonm3`

  ALTER TABLE `Assigntime` 
	ADD `time_sleep1_trip` int(8) AFTER `work_flow`, 
	ADD `time_sleep1` int(8) AFTER `time_sleep1_trip`, 
	ADD `time_sleep2_trip` int(8) AFTER `time_sleep1`, 
	ADD `time_sleep2` int(8) AFTER `time_sleep2_trip` 

    ALTER TABLE `Team` 
	ADD `chaos_team` int(1) DEFAULT 0 AFTER `team_name` 
	

     ALTER TABLE `Eexamtime` 
	ADD `gameboard_id` int(5) DEFAULT 0 AFTER `game_flag` 


     ALTER TABLE `Student` 
	ADD `game_name` VARCHAR(16) DEFAULT NULL AFTER `last_name` 

     ALTER TABLE `Eregistration` 
	ADD `kahoot_points` INT(16) DEFAULT 0 AFTER `exam_code` 

      ALTER TABLE `Team` 
	ADD `fin_score` int(5) DEFAULT 0 AFTER `counter`, 
	ADD `env_score` int(5) DEFAULT 0 AFTER `fin_score`, 
	ADD `soc_score` int(5) DEFAULT 0 AFTER `env_score`, 
	ADD `fin_block` int(5) DEFAULT 0 AFTER `soc_score`, 
	ADD `env_block` int(5) DEFAULT 0 AFTER `fin_block`, 
	ADD `soc_block` int(5) DEFAULT 0 AFTER `env_block`, 
	ADD `fin_hit` int(5) DEFAULT 0 AFTER `soc_block`, 
	ADD `env_hit` int(5) DEFAULT 0 AFTER `fin_hit`, 
	ADD `soc_hit` int(5) DEFAULT 0 AFTER `env_hit`, 
	ADD `pol_points` int(5) DEFAULT 0 AFTER `soc_hit`, 
	ADD `gamepolitical_id` int(5) DEFAULT 0 AFTER `pol_points`, 
	ADD `final_score` int(5) DEFAULT 0 AFTER `soc_hit`




-- this connects all three talbes if we want to have the certain actions block certain chaos
      CREATE TABLE IF NOT EXISTS GameBoardChaosActionConnect (
            `gameboardchaosactionconnect_id` INT(11) NOT NULL AUTO_INCREMENT,
            `gamechaos_id` INT(11) NOT NULL,
            `gameboard_id` INT(11) NOT NULL,
            `gameaction_id` INT(11) NOT NULL,
            `chaos_action_block` INT(5) NOT NULL,
            CONSTRAINT FOREIGN KEY (`gamechaos_id`) REFERENCES GameChaos (gamechaos_id),
            CONSTRAINT FOREIGN KEY (`gameboard_id`) REFERENCES GameBoard ( `gameboard_id`),
            CONSTRAINT FOREIGN KEY (`gameaction_id`) REFERENCES GameAction ( `gameaction_id`),
            CONSTRAINT UNIQUE (gamechaos_id,gameboard_id,gameaction_id),
            PRIMARY KEY (gameboardchaosactionconnect_id)
          ) ENGINE=InnoDB CHARACTER SET = utf8;    


  ALTER TABLE `Assign` 
	ADD `sequential` int(2) DEFAULT 1 AFTER `exp_date`, 
  ADD `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `sequential` ,
  ADD `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`
  
  
  ALTER TABLE `Problem` 
	ADD `sequential` int(2) DEFAULT 1 AFTER `society`, 
  ADD `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `sequential` ,
  ADD `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`


    ALTER TABLE `Assigntime` 
	ADD `help_n_hint` int(4) AFTER `help_t_instruct`, 
	ADD `help_t_hint` int(4) AFTER `help_n_hint`

    ALTER TABLE `Assigntime` 
	ADD `ec_open_daysb4due_elgible` int(4) AFTER `ec_daysb4due_elgible` 



ALTER TABLE `Activity`
 CHANGE `time_pp1` `time_pp1` TIMESTAMP NULL DEFAULT NULL, 
CHANGE `time_pp2` `time_pp2` TIMESTAMP NULL DEFAULT NULL, 
CHANGE `time_pp3` `time_pp3` TIMESTAMP NULL DEFAULT NULL, 
CHANGE `time_pp4` `time_pp4` TIMESTAMP NULL DEFAULT NULL, 
CHANGE `time_post_pblm1` `time_post_pblm1`  TIMESTAMP NULL DEFAULT NULL, 
CHANGE `time_post_pblm2` `time_post_pblm2`  TIMESTAMP NULL DEFAULT NULL, 
CHANGE `time_post_pblm3` `time_post_pblm3`  TIMESTAMP NULL DEFAULT NULL, 
CHANGE `time_complete` `time_complete`  TIMESTAMP NULL DEFAULT NULL







    ALTER TABLE `Activity` 
  ADD `time_a_correct` TIMESTAMP NULL DEFAULT NULL AFTER `correct_a`, 
  ADD `time_b_correct` TIMESTAMP NULL DEFAULT NULL AFTER `correct_b`, 
  ADD `time_c_correct` TIMESTAMP NULL DEFAULT NULL AFTER `correct_c`, 
  ADD `time_d_correct` TIMESTAMP NULL DEFAULT NULL AFTER `correct_d`, 
  ADD `time_e_correct` TIMESTAMP NULL DEFAULT NULL AFTER `correct_e`, 
  ADD `time_f_correct` TIMESTAMP NULL DEFAULT NULL AFTER `correct_f`, 
  ADD `time_g_correct` TIMESTAMP NULL DEFAULT NULL AFTER `correct_g`, 
  ADD `time_h_correct` TIMESTAMP NULL DEFAULT NULL AFTER `correct_h`, 
  ADD `time_i_correct` TIMESTAMP NULL DEFAULT NULL AFTER `correct_i`, 
  ADD `time_j_correct` TIMESTAMP NULL DEFAULT NULL AFTER `correct_j`


Alter TABLE `Assigntime`
ADD UNIQUE `unique_index`(`iid`,`currentclass_id`,`assign_num`)

