<?php
include 'db.php';

$sql = "
    -- 1. Rensa gamla tabeller (viktigt att börja om nu när vi ändrat strukturen)
    DROP TABLE IF EXISTS loans;
    DROP TABLE IF EXISTS books;
    DROP TABLE IF EXISTS authors;

    -- 2. Aktivera UUID-stöd för ID-kolumnerna
    CREATE EXTENSION IF NOT EXISTS \"pgcrypto\";

    -- 3. Skapa tabeller
    CREATE TABLE authors (
        id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
        name text NOT NULL,
        description text
    );
      
    CREATE TABLE books (
        id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
        isbn text NOT NULL,
        author_id uuid NOT NULL REFERENCES authors(id) ON DELETE RESTRICT,
        title text NOT NULL,
        year int,
        description text,
        genre text
    );

    CREATE TABLE loans (
        id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
        book_id uuid NOT NULL REFERENCES books(id) ON DELETE RESTRICT,
        auth0_sub text NOT NULL, -- Här sparar vi Auth0-ID:t direkt som text
        loaned_at timestamptz NOT NULL DEFAULT now(),
        due_at timestamptz NOT NULL,
        returned_at timestamptz
    );

    -- 4. Lägg in Mockdata
    DO $$
    DECLARE
        v_author_id uuid;
        v_book_id uuid;
    BEGIN
        -- Skapa en författare
        INSERT INTO authors (name, description) 
        VALUES ('Astrid Lindgren', 'Sveriges mest kända barnboksförfattare') 
        RETURNING id INTO v_author_id;

        -- Skapa en bok kopplad till författaren
        INSERT INTO books (isbn, author_id, title, year, genre, description) 
        VALUES ('9789129657784', v_author_id, 'Bröderna Lejonhjärta', 1973, 'Fantasy', 'En saga om döden och frihet.')
        RETURNING id INTO v_book_id;

        -- Skapa ett lån (vi hittar på ett auth0_sub för att testa)
        INSERT INTO loans (book_id, auth0_sub, due_at) 
        VALUES (v_book_id, 'auth0|mock_user_123', now() + interval '14 days');

        -- En till författare och bok
        INSERT INTO authors (name, description) 
        VALUES ('J.R.R. Tolkien', 'Skaparen av Midgård') 
        RETURNING id INTO v_author_id;

        INSERT INTO books (isbn, author_id, title, year, genre, description) 
        VALUES ('9780261103252', v_author_id, 'The Fellowship of the Ring', 1954, 'Fantasy', 'The first part of Lord of the Rings.');
    END $$;
";

$result = pg_query($db, $sql);

if ($result) {
    echo "<h2>Succé! Databasen är uppdaterad för Auth0.</h2>";
    echo "<p>Tabeller skapade: authors, books, loans (med auth0_sub).</p>";
    echo "<p><a href='index.php'>Gå till startsidan</a></p>";
} else {
    echo "<h2>Något gick fel:</h2>";
    echo "<pre>" . pg_last_error($db) . "</pre>";
}
?>