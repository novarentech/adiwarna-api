<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Transactional Trait
 * 
 * Provides database transaction handling capabilities to Models.
 * Migrated from BaseService to support RCMR pattern.
 */
trait Transactional
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

        Log::error('Transaction Exception: ' . $e->getMessage(), [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    /**
     * Execute operation within transaction
     * 
     * @param callable $callback
     * @return mixed
     * @throws Exception
     */
    protected static function executeInTransaction(callable $callback): mixed
    {
        try {
            DB::beginTransaction();
            $result = $callback();
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Transaction Exception: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }
}
