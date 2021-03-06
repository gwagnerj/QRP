
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
    
    
    
    
    