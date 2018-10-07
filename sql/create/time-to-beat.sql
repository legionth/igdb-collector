CREATE TABLE game_engine_relation(
  id INT(8) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  game_id INT(6) UNSIGNED NOT NULL,
  time_to_beat INT(6) UNSIGNED,
  type VARCHAR(255),
  FOREIGN KEY (game_id) REFERENCES games(id)
);