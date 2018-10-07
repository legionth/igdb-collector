CREATE TABLE game_standalone_expansion_relation(
  id INT(8) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  game_id INT(8) NOT NULL,
  standalone_expansion TEXT NOT NULL,
  FOREIGN KEY (game_id) REFERENCES games(id)
);