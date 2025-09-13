l-- Connect to the database
\c skeleton
-- Grant privileges to the user
GRANT CONNECT ON DATABASE skeleton TO mis_user;
-- Grant usage on the public schema
GRANT USAGE ON SCHEMA public TO mis_user;
-- Grant privileges on all tables
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO mis_user;
-- Grant privileges on all sequences (if any)
GRANT USAGE, SELECT, UPDATE ON ALL SEQUENCES IN SCHEMA public TO mis_user;
-- Optional: Grant privileges on future tables and sequences
ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO mis_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT USAGE, SELECT, UPDATE ON SEQUENCES TO mis_user;



php artisan make:model Menu
