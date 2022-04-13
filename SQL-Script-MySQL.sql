drop table Player;
drop table Game;
drop table Clue;
drop table Category;
drop table Board;
drop table Creator;

CREATE TABLE Creator (
  userId int NOT NULL AUTO_INCREMENT,
  username varchar(50),
  password varchar(50),
  email varchar(50),
  primary key (userId),
  UNIQUE (username)
);

insert into Creator values (1,'farnsworth','farnpass','farn@mail.com');

CREATE TABLE Board (
  boardId int NOT NULL AUTO_INCREMENT,
  title varchar(50),
  userId int,
  primary key (boardId),
  foreign key (userId) references Creator(userId)
);

insert into Board values (1, 'Weather', 1);

CREATE TABLE Category (
  categoryId int NOT NULL AUTO_INCREMENT,
  text varchar(50),
  boardId int,
  primary key (categoryId),
  foreign key (boardId) references Board(boardId)
);

insert into Category values (1, 'Clouds and Layers', '1');
insert into Category values (2, 'Weather Facts', '1');
insert into Category values (3, 'Fronts and Masses Facts', '1');
insert into Category values (4, 'Weather Tools', '1');
insert into Category values (5, 'Severe weather/gases', '1');

CREATE TABLE Clue (
  clueId int NOT NULL AUTO_INCREMENT,
  points int,
  text varchar(100),
  response varchar(100),
  categoryId int,
  primary key (clueId),
  foreign key (categoryId) REFERENCES Category(categoryId)
);

insert into Clue values (1, 100, 'These make up clouds.', 'What is water vapor and dust particles?', 1);
insert into Clue values (2, 100, 'The Earths rotation makes the wind curve.', 'What is the Coriolis effect?', 2);
insert into Clue values (3, 100, 'The symbol for a cold front is a blue curved line with these shapes on it', 'What are triangles?', 3);
insert into Clue values (4, 100, 'I measure the speed of wind', 'What is a anemometer', 4);
insert into Clue values (5, 100, 'The severity of a hurricane is ranked using these on the Saffir-Simpson scale', 'What are numbers?', 5);
insert into Clue values (6, 200, 'We live in this atmospheric layer', 'What is the troposphere?', 1);
insert into Clue values (7, 200, 'This symbol is shown on a weather map', 'What is clear weather?', 2);
insert into Clue values (8, 200, 'A curved red line with semi circles on is the symbol of this type of weather', 'What is a warm front?', 3);
insert into Clue values (9, 200, 'If you have a fever or to measure the temperature of the air, use me', 'What is a thermometer', 4);
insert into Clue values (10, 200, 'I am a big storm that gets my energy from warm ocean waters.', 'What is a hurricane?', 5);
insert into Clue values (11, 300, 'This layer is where meteors burn up.', 'What is the mesosphere?', 1);
insert into Clue values (12, 300, 'Warm air rises over the land, moves to the ocean/sea, then back to the land again.', 'What is an ocean/sea breeze?', 2);
insert into Clue values (14, 300, 'mT', 'What is maritime tropical?', 3);
insert into Clue values (13, 300, 'Use this to measure air pressure.', 'What is a barometer?', 4);
insert into Clue values (15, 300, 'The most common gas in the atmosphere.', 'What is nitrogen?', 5);
insert into Clue values (16, 400, 'A type of cloud that appears as a low gray blanket covering the sky.', 'What is stratus?', 1);
insert into Clue values (17, 400, 'Differences in temperature and elevation make this happen for local winds.', 'What are mountain and valley breezes?', 2);
insert into Clue values (18, 400, 'A huge body of air that has similar temperature, humidity, and air pressure at any given height.', 'What is maritime tropical?', 3);
insert into Clue values (19, 400, 'Curious about how much rain fell in a certain period of time? Use me to measure it', 'What is a rain gauge?', 4);
insert into Clue values (20, 400, 'The gas that has the ability to trap heat in the atmosphere.', 'What is ozone?', 5);
insert into Clue values (21, 500, 'These clouds form and make big thunderstorms.', 'What are cumulonimbus clouds?', 1);
insert into Clue values (22, 500, 'During a hurricane, this produces most of the damage and can cause people to drown.', 'What is a storm surge or flooding?', 2);
insert into Clue values (23, 500, 'A pair of air masses, neither of which is strong enough to replace the other.', 'What is stationary front?', 3);
insert into Clue values (24, 500, 'This person uses tools to predict the weather.', 'What is a meteorologist?', 4);
insert into Clue values (25, 500, 'This is the trait a gas has that contributes to the green house effect.', 'What is trapping heat??', 5);


CREATE TABLE Game (
  gameId int NOT NULL AUTO_INCREMENT,
  date varchar(12),
  number_of_teams int,
  userId int,
  primary key (gameId),
  foreign key (userId) references Creator(userId)
);

insert into Game values (1, '17-8-21', 1, 1);

CREATE TABLE Player (
  playerId int NOT NULL AUTO_INCREMENT,
  name varchar(50),
  totalPoints int,
  gameId int,
  primary key (playerId),
  foreign key (gameId) references Game(gameId)
);

insert into Player values (1, 'Jeff', 0, 1);
