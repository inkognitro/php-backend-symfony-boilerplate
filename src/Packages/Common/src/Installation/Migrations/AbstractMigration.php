<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migration;

use App\Packages\Common\Infrastructure\DbalConnection;

abstract class AbstractMigration
{
    protected $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public abstract function getVersion(): int;
    public abstract function up(): void;
    public abstract function down(): void;
}