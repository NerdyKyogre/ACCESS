CREATE SCHEMA IF NOT EXISTS plushies;
GRANT USAGE ON SCHEMA plushies TO apache;

CREATE TABLE IF NOT EXISTS plushies.registry AS (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    species TEXT,
    pronouns TEXT,
    imagePath TEXT UNIQUE,
    heightIn SMALLINT,
    bio TEXT);

-- Updates an existing entry if an ID is passed, otherwise creates a new one
CREATE PROCEDURE IF NOT EXISTS plushies.enterPlushie(
    _name TEXT,
    _species TEXT,
    _pronouns TEXT,
    _imagePath TEXT,
    _heightIn SMALLINT,
    _bio TEXT,
    _id INTEGER = NULL)
LANGUAGE plpgsql
AS $$
BEGIN
    IF _id IS NULL THEN
        INSERT INTO plushies.registry (
            name,
            species,
            pronouns,
            imagePath,
            heightIn,
            bio)
        VALUES(
            _name,
            _species,
            _pronouns,
            _imagePath,
            _heightIn,
            _bio);
    ELSE
        UPDATE plushies.registry
        SET
            name = _name,
            species = _species,
            pronouns = _pronouns,
            imagePath = _imagePath,
            heightIn = _heightIn,
            bio = _bio
        WHERE registry.id = _id;

END; $$;


-- Gets the data for a given entry, or returns the full table if no ID specified
CREATE FUNCTION IF NOT EXISTS plushies.getPlushie(
    _id INTEGER = NULL)
LANGUAGE plpgsql STABLE STRICT PARALLEL SAFE
AS $$
BEGIN

    RETURN QUERY
        SELECT
            id,
            name,
            species,
            pronouns,
            imagePath,
            heightIn,
            bio
        FROM plushies.registry
        WHERE
            _id IS NULL OR
            registry.id = _id;

END; $$;

GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA plushies TO apache;
