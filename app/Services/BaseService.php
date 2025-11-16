<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class BaseService
{
    /**
     * Begin database transaction
     */
    protected function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * Commit database transaction
     */
    protected function commit(): void
    {
        DB::commit();
    }

    /**
     * Rollback database transaction
     */
    protected function rollback(): void
    {
        DB::rollBack();
    }

    /**
     * Handle exception with logging and rollback
     */
    protected function handleException(Exception $e): void
    {
        $this->rollback();

        Log::error('Service Exception: ' . $e->getMessage(), [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    /**
     * Execute operation within transaction
     */
    protected function executeInTransaction(callable $callback): mixed
    {
        try {
            $this->beginTransaction();
            $result = $callback();
            $this->commit();
            return $result;
        } catch (Exception $e) {
            $this->handleException($e);
            throw $e;
        }
    }
}
