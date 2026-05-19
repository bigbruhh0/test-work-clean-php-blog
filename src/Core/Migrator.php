<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

class Migrator
{
    public function __construct(
        private readonly Database $database,
        private readonly string $migrationsPath
    ) {
    }

    public function run(): array
    {
        $connection = $this->database->connection();

        $this->ensureMigrationsTable($connection);

        $appliedMigrations = $this->appliedMigrations($connection);
        $files = glob($this->migrationsPath . DIRECTORY_SEPARATOR . '*.sql') ?: [];
        sort($files);

        $executed = [];

        foreach ($files as $file) {
            $migration = basename($file);

            if (in_array($migration, $appliedMigrations, true)) {
                continue;
            }

            $sql = file_get_contents($file);

            if ($sql === false) {
                continue;
            }

            try {
                $connection->exec($sql);

                $statement = $connection->prepare('INSERT INTO migrations (migration) VALUES (:migration)');
                $statement->execute(['migration' => $migration]);
            } catch (\Throwable $exception) {
                throw $exception;
            }

            $executed[] = $migration;
        }

        return $executed;
    }

    private function ensureMigrationsTable(PDO $connection): void
    {
        $connection->exec(
            'CREATE TABLE IF NOT EXISTS migrations (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY migrations_migration_unique (migration)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    }

    private function appliedMigrations(PDO $connection): array
    {
        $statement = $connection->query('SELECT migration FROM migrations ORDER BY id ASC');
        return $statement->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }
}
