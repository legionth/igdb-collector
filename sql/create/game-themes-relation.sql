CREATE TABLE game_theme_relation(
  id INT(8) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  game_id INT(6) UNSIGNED,
  theme_id INT(6) UNSIGNED,
  FOREIGN KEY (game_id) REFERENCES games(id)
);