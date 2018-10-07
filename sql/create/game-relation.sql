CREATE TABLE game_bundle_relation
(
  game_id INT(8) UNSIGNED NOT NULL,
  reference_game_id INT(8) UNSIGNED NOT NULL,
  FOREIGN KEY (game_id) REFERENCES games(id),
  FOREIGN KEY (reference_game_id) REFERENCES games(id),
  PRIMARY KEY (id, reference_game_id)
);