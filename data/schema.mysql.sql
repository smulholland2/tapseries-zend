-- To create a new database, run MySQL client:
--   mysql -u root -p
-- Then in MySQL client command line, type the following (replace <password> with password string):
--   create database tapapp;
--   grant all privileges on tapapp.* to developer@localhost identified by '<password>';
--   quit
-- Then, in shell command line, type:
--   mysql -u root -p tapapp < schema.mysql.sql

set names 'utf8';

-- Post
CREATE TABLE `page` (     
  `id` int(11) PRIMARY KEY AUTO_INCREMENT, -- Unique ID
  `title` text NOT NULL,      -- Title  
  `content` text NOT NULL,    -- Text 
  `status` int(11) NOT NULL,  -- Status  
  `date_created` datetime NOT NULL -- Creation date    
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';

-- Comment
CREATE TABLE `comment` (     
  `id` int(11) PRIMARY KEY AUTO_INCREMENT, -- Unique ID  
  `post_id` int(11) NOT NULL,     -- Page ID this comment belongs to  
  `content` text NOT NULL,        -- Text
  `author` varchar(128) NOT NULL, -- Author's name who created the comment  
  `date_created` datetime NOT NULL -- Creation date          
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';

-- Tag
CREATE TABLE `tag` (     
  `id` int(11) PRIMARY KEY AUTO_INCREMENT, -- Unique ID.  
  `name` VARCHAR(128) NOT NULL,            -- Tag name.  
  UNIQUE KEY `name_key` (`name`)          -- Tag names must be unique.      
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';

-- Page-Tag
CREATE TABLE `page_tag` (     
  `id` int(11) PRIMARY KEY AUTO_INCREMENT, -- Unique ID  
  `post_id` int(11),                       -- Page id
  `tag_id` int(11),                        -- Tag id
   UNIQUE KEY `unique_key` (`post_id`, `tag_id`), -- Tag names must be unique.
   KEY `post_id_key` (`post_id`),
   KEY `tag_id_key` (`tag_id`)      
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';