<?php

namespace App\Repositories\Interfaces;

interface FileSystemRepositoryInterface
{
    /**
     * Ensure a directory exists with proper permissions
     *
     * @param string $path
     * @param int $permissions
     * @return bool
     */
    public function ensureDirectoryExists(string $path, int $permissions = 0755): bool;

    /**
     * Set permissions for an existing directory
     *
     * @param string $path
     * @param int $permissions
     * @return bool
     */
    public function setDirectoryPermissions(string $path, int $permissions = 0755): bool;

    /**
     * Ensure multiple directories exist with proper permissions
     *
     * @param array $paths
     * @param int $permissions
     * @return bool
     */
    public function ensureDirectoriesExist(array $paths, int $permissions = 0755): bool;

    /**
     * Create storage directory structure for a specific module
     *
     * @param string $module
     * @param int $permissions
     * @return bool
     */
    public function createModuleStorageStructure(string $module, int $permissions = 0755): bool;

    /**
     * Ensure Laravel's core storage directories exist with proper permissions
     *
     * @param int $permissions
     * @return bool
     */
    public function ensureLaravelStorageStructure(int $permissions = 0755): bool;
} 