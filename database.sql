CREATE TABLE IF NOT EXISTS `blogattachments` (
  `fileID` bigint(20) NOT NULL auto_increment,
  `topicID` bigint(20) NOT NULL,
  `fileTitle` varchar(255) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `fileNameIs` varchar(10) NOT NULL,
  `fileHits` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`fileID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='List of all attachments' AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `blogreplies` (
  `replyID` bigint(20) NOT NULL auto_increment,
  `topicID` bigint(20) NOT NULL,
  `userID` bigint(20) NOT NULL,
  `replyTitle` varchar(255) NOT NULL,
  `replyText` text NOT NULL,
  `replyDate` datetime NOT NULL,
  PRIMARY KEY  (`replyID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='List of all replies/comments' AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `blogtopics` (
  `topicID` bigint(20) NOT NULL auto_increment,
  `userID` int(11) NOT NULL,
  `catID` bigint(20) NOT NULL,
  `topicTitle` varchar(255) NOT NULL,
  `topicText` longtext NOT NULL,
  `topicCreated` datetime NOT NULL,
  `topicUpdated` datetime NOT NULL,
  PRIMARY KEY  (`topicID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='List of all blog topics' AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `catlist` (
  `catID` bigint(20) NOT NULL auto_increment,
  `catTitle` varchar(255) NOT NULL,
  `catDesc` text NOT NULL,
  PRIMARY KEY  (`catID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='List of all categories' AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `siteconfig` (
  `configName` varchar(255) NOT NULL,
  `configValue` text NOT NULL,
  PRIMARY KEY  (`configName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='List of all site configurations';


INSERT INTO `siteconfig` (`configName`, `configValue`) VALUES
('siteName', 'Simple Blog'),
('siteURL', 'http://localhost/master/work/Simple-Blog/'),
('allowRegistration', '1'),
('requireVerification', '1'),
('allowGuest', '0'),
('allowBBCode', '1'),
('maxWords', '500'),
('registerTemplate', 'Hello %userName%,\r\n\r\nYou have successfully registered at %siteName%. Your registration info are:\r\n\r\nUsername: %userName%\r\nPassword: %userPassword%\r\n\r\nHowever, before posting. You must verify this email by clicking on:\r\n\r\n%siteURL%?verificationCode=%userStatus%\r\n\r\nThanks,'),
('forgotTemplate', 'Hello %userName%,\r\n\r\nYou have requested that your password be retrieved.\r\n\r\nYour password is: %userPassword%\r\n\r\nYou can visit %siteName% by going to:\r\n%siteURL%\r\n\r\nThanks,'),
('siteEmail', 'azizsaleh@gmail.com'),
('registerTemplateSubject', 'Thanks for registering at %siteName%'),
('forgotTemplateSubject', 'Your password for %siteName%'),
('fileLocation', 'files/'),
('allowSearch', '1'),
('showCats', '1'),
('showDates', '1');


CREATE TABLE IF NOT EXISTS `userlist` (
  `userID` bigint(20) NOT NULL auto_increment,
  `userName` varchar(50) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPassword` varchar(50) NOT NULL,
  `userStatus` varchar(10) NOT NULL,
  `registerDate` datetime NOT NULL,
  PRIMARY KEY  (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='List of all users' AUTO_INCREMENT=8 ;


INSERT INTO `userlist` (`userID`, `userName`, `userEmail`, `userPassword`, `userStatus`, `registerDate`) VALUES
(1, 'admin', 'azizsaleh@gmail.com', 'azizsaleh', '1', '2009-08-31 15:44:44'),
(2, 'Guest', 'azizsaleh@gmail.com', 'Guest', '1', '2009-09-01 13:03:55');


CREATE TABLE IF NOT EXISTS `usersessions` (
  `sessionID` varchar(20) NOT NULL,
  `userID` bigint(20) NOT NULL,
  `sessionTime` datetime NOT NULL,
  PRIMARY KEY  (`sessionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='List of user login sessions';
