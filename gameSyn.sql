/* this is just to set up a synchonzied timer and eventually the game to coordinate all of the phones.
The first step is just to make a simple count down timer that can be used across phones/*

CREATE TABLE IF NOT EXISTS `game` (
  `game_id` int(15) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `roomNum` Int(7) NOT NULL ,
  `groupNum` int(5) NOT NULL,
   `studentNum` int(5) NOT NULL 
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;