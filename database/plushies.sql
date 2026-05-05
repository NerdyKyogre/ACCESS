CREATE SCHEMA IF NOT EXISTS plushies;
GRANT USAGE ON SCHEMA plushies TO apache;

CREATE TABLE IF NOT EXISTS plushies.registry AS (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    species TEXT,
    colour TEXT[],
    pronouns TEXT,
    imagePath TEXT UNIQUE NULLS DISTINCT,
    heightIn SMALLINT,
    bio TEXT);

-- Updates an existing entry if an ID is passed, otherwise creates a new one
CREATE OR REPLACE PROCEDURE plushies.enterPlushie(
    _name TEXT,
    _species TEXT,
    _colour TEXT,
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
            colour,
            pronouns,
            imagePath,
            heightIn,
            bio)
        VALUES(
            _name,
            LOWER(_species),
            LOWER(TRIM(STRING_TO_ARRAY(_colour, ', '))),
            LOWER(_pronouns),
            _imagePath,
            _heightIn,
            _bio);
    ELSE
        UPDATE plushies.registry
        SET
            name = _name,
            species = LOWER(_species),
            colour = LOWER(_colour),
            pronouns = LOWER(_pronouns),
            imagePath = _imagePath,
            heightIn = _heightIn,
            bio = _bio
        WHERE registry.id = _id;

END; $$;

-- Gets the data for a given entry, or returns the full table if no ID specified
CREATE OR REPLACE FUNCTION plushies.getPlushie(
    _id INTEGER = NULL)
RETURNS TABLE (
    id INTEGER,
    name TEXT,
    species TEXT,
    colour TEXT,
    pronouns TEXT,
    imagePath TEXT,
    heightIn SMALLINT,
    bio TEXT)
LANGUAGE plpgsql STABLE STRICT PARALLEL SAFE
AS $$
BEGIN

    RETURN QUERY
        SELECT
            id,
            name,
            species,
            ARRAY_TO_STRING(colour, ', '),
            pronouns,
            imagePath,
            heightIn,
            bio
        FROM plushies.registry
        WHERE
            _id IS NULL OR
            registry.id = _id;

END; $$;

-- Populates filter info for search page, saving this work on the site side
CREATE OR REPLACE FUNCTION plushies.getFilterValues()
RETURNS JSONB
LANGUAGE plpgsql STABLE STRICT PARALLEL SAFE
AS $$
BEGIN

    RETURN QUERY
        SELECT
            JSONB_BUILD_OBJECT(
                'height', JSONB_BUILD_OBJECT(
                    'max', MAX(registry.heightIn),
                    'min', MIN(registry.heightIn)),
                'species', JSONB_AGG(DISTINCT registry.species),
                'colour', JSONB_AGG(DISTINCT UNNEST(registry.colour)),
                'pronouns', JSONB_AGG(DISTINCT registry.pronouns))
        FROM plushies.registry;

END; $$;

GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA plushies TO apache;
