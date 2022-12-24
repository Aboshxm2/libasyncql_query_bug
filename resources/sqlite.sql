-- #!sqlite
-- #{ libasyncql_query_bug.init
CREATE TABLE IF NOT EXISTS example_table (playerName TEXT PRIMARY KEY, kills INTEGER);
-- #}
-- #{ libasyncql_query_bug.select
-- # :playerName string
SELECT * FROM example_table WHERE playerName=:playerName;
-- #}
-- #{ libasyncql_query_bug.insert
-- # :playerName string
-- # :kills int
INSERT INTO example_table (playerName, kills) VALUES (:playerName, :kills)
-- #}
