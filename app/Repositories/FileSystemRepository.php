<?php

namespace App\Repositories;

use App\Repositories\Interfaces\FileSystemRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FileSystemRepository implements FileSystemRepositoryInterface
{
    /**
     * Ensure a directory exists with proper permissions
     *
     * @param string $path
     * @param int $permissions
     * @return bool
     */
    public function ensureDirectoryExists(string $path, int $permissions = 0755): bool
    {
        try {
            if (!file_exists($path)) {
                if (!mkdir($path, $permissions, true)) {
                    Log::error("Failed to create directory: {$path}");
                    return false;
                }
                Log::info("Created directory: {$path}");
            } else {
                // Directory exists, only set permissions if it's a module directory we created
                if ($this->isModuleDirectory($path)) {
                    if (!$this->setDirectoryPermissions($path, $permissions)) {
                        return false;
                    }
                }
            }
            return true;
        } catch (\Exception $e) {
            Log::error("Error ensuring directory exists: {$path}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Set permissions for an existing directory
     *
     * @param string $path
     * @param int $permissions
     * @return bool
     */
    public function setDirectoryPermissions(string $path, int $permissions = 0755): bool
    {
        try {
            if (file_exists($path)) {
                if (!chmod($path, $permissions)) {
                    Log::error("Failed to set permissions for directory: {$path}");
                    return false;
                }
                Log::debug("Set permissions for directory: {$path}");
            }
            return true;
        } catch (\Exception $e) {
            Log::error("Error setting directory permissions: {$path}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Ensure multiple directories exist with proper permissions
     *
     * @param array $paths
     * @param int $permissions
     * @return bool
     */
    public function ensureDirectoriesExist(array $paths, int $permissions = 0755): bool
    {
        $success = true;
        
        foreach ($paths as $path) {
            if (!$this->ensureDirectoryExists($path, $permissions)) {
                $success = false;
            }
        }
        
        return $success;
    }

    /**
     * Create storage directory structure for a specific module
     *
     * @param string $module
     * @param int $permissions
     * @return bool
     */
    public function createModuleStorageStructure(string $module, int $permissions = 0755): bool
    {
        // Only create and manage the specific module directory
        $modulePath = storage_path("app/public/{$module}");
        
        return $this->ensureDirectoryExists($modulePath, $permissions);
    }

    /**
     * Ensure Laravel's core storage directories exist with proper permissions
     *
     * @param int $permissions
     * @return bool
     */
    public function ensureLaravelStorageStructure(int $permissions = 0755): bool
    {
        // This method is kept for interface compliance but does nothing
        // We don't want to touch Laravel's core storage directories
        return true;
    }

    /**
     * Check if a path is a module directory that we should manage
     *
     * @param string $path
     * @return bool
     */
    private function isModuleDirectory(string $path): bool
    {
        $publicStoragePath = storage_path('app/public');
        return strpos($path, $publicStoragePath) === 0 && $path !== $publicStoragePath;
    }

    /**
     * Get the storage path for a specific module
     *
     * @param string $module
     * @return string
     */
    public function getModuleStoragePath(string $module): string
    {
        return storage_path("app/public/{$module}");
    }

    /**
     * Check if a directory is writable
     *
     * @param string $path
     * @return bool
     */
    public function isDirectoryWritable(string $path): bool
    {
        return file_exists($path) && is_writable($path);
    }
} 