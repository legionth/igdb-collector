CREATE TABLE alternative_names(
  id INT(8) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  game_id INT(8) NOT NULL,
  name TEXT NOT NULL,
  comment TEXT,
  FOREIGN KEY (game_id) REFERENCES games(id)
);